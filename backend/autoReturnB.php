<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$response = [
    'success' => false,
    'message' => ''
];


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

                        // Check if there are reservations for this book
                        $reserve_query = "
                            SELECT * FROM reservations
                            WHERE book_id = ? AND is_fulfilled = 0
                            ORDER BY reservation_date ASC LIMIT 1
                        ";
                        $reserve_stmt = $conn->prepare($reserve_query);
                        $reserve_stmt->bind_param("i", $book_id);
                        $reserve_stmt->execute();
                        $reserve_result = $reserve_stmt->get_result();

                        // If there are reservations, pass reserver details to borrowBook.php
                        if ($reserve_result->num_rows > 0) {
                            $reservation = $reserve_result->fetch_assoc();
                            $reserved_user_id = $reservation['user_id'];


                            $update_reservation_query = "UPDATE reservations SET is_fulfilled = 1 WHERE reservation_id = ?";
                            $update_reservation_stmt = $conn->prepare($update_reservation_query);
                            $update_reservation_stmt->bind_param("i", $reservation['reservation_id']);
                            $update_reservation_stmt->execute();

                            // Send reserved user's details to borrowBook.php
                            $borrow_url = "borrowBookxx.php";
                            $borrow_data = [
                                'book_id' => $book_id,
                                'user_id' => $reserved_user_id,
                                'reserved' => true
                            ];
                            $options = [
                                'http' => [
                                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                    'method'  => 'POST',
                                    'content' => http_build_query($borrow_data)
                                ]
                            ];
                            $context  = stream_context_create($options);
                            $result = file_get_contents($borrow_url, false, $context);

                            if ($result === FALSE) {
                                $response['message'] = "Book returned, but could not borrow to reserved user.";
                            } else {
                                $response['message'] .= " Reserved book borrowed to the next user.";
                            }
                        }
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
if (isset($reserve_stmt)) $reserve_stmt->close();
$conn->close();

echo json_encode($response);
