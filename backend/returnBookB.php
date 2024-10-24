<?php
session_start();


$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "void"; 


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare response
$response = [
    'success' => false,
    'message' => ''
];

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    $response['message'] = "You must log in first.";
    echo json_encode($response);
    exit;
}


$user_email = $_SESSION['email'];

$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

if ($book_id === 0) {
    $response['message'] = "Invalid book ID.";
    echo json_encode($response);
    exit;
}

// Get user id
$query_user = "SELECT id FROM users WHERE email = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_id = $user_data['id'];

    // Check if the user has borrowed the book and hasn't returned it
    $query_borrow = "
        SELECT * FROM borrowed_books
        WHERE id = ? AND book_id = ? AND return_date IS NULL
    ";
    $stmt_borrow = $conn->prepare($query_borrow);
    $stmt_borrow->bind_param("ii", $user_id, $book_id);
    $stmt_borrow->execute();
    $result_borrow = $stmt_borrow->get_result();

    if ($result_borrow->num_rows > 0) {
        // Update return_date in borrowed_books table
        $return_date = date("Y-m-d H:i:s");
        $query_update_borrow = "
            UPDATE borrowed_books
            SET return_date = ?
            WHERE id = ? AND book_id = ?
        ";
        $stmt_update_borrow = $conn->prepare($query_update_borrow);
        if ($stmt_update_borrow) {
            $stmt_update_borrow->bind_param("sii", $return_date, $user_id, $book_id);
            $stmt_update_borrow->execute();

            // Increment available_copies in books table only if the return update was successful
            if ($stmt_update_borrow->affected_rows > 0) {
                $query_update_book = "
                    UPDATE books
                    SET available_copies = available_copies + 1
                    WHERE book_id = ?
                ";
                $stmt_update_book = $conn->prepare($query_update_book);
                if ($stmt_update_book) {
                    $stmt_update_book->bind_param("i", $book_id);
                    $stmt_update_book->execute();

                    if ($stmt_update_book->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = "Book returned successfully and available copies updated.";
                    } else {
                        $response['message'] = "Failed to update available copies.";
                    }
                } else {
                    $response['message'] = "Failed to prepare book update statement.";
                }
            } else {
                $response['message'] = "Failed to update return date.";
            }
        } else {
            $response['message'] = "Failed to prepare return update statement.";
        }
    } else {
        $response['message'] = "You have not borrowed this book or you have already returned it.";
    }
} else {
    $response['message'] = "User not found.";
}


if (isset($stmt_user)) $stmt_user->close();
if (isset($stmt_borrow)) $stmt_borrow->close();
if (isset($stmt_update_borrow)) $stmt_update_borrow->close();
if (isset($stmt_update_book)) $stmt_update_book->close();
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
