<?php
session_start();

// Database connection details
$servername = "localhost"; // or 127.0.0.1
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password is empty
$dbname = "void"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare response
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    $response['message'] = "You must log in first.";
    echo json_encode($response);
    exit;
}

// Get user email from session
$user_email = $_SESSION['email'];

// Fetch the user id based on session email
$query_user = "SELECT id FROM users WHERE email = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_id = $user_data['id'];

    // Fetch borrowed books for the user
    $query = "
        SELECT books.title, books.author, borrowed_books.borrow_date, borrowed_books.due_date, borrowed_books.return_date
        FROM borrowed_books
        INNER JOIN books ON borrowed_books.book_id = books.book_id
        WHERE borrowed_books.id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $borrowed_books = [];

        while ($row = $result->fetch_assoc()) {
            $borrowed_books[] = [
                'title' => htmlspecialchars($row['title']),
                'author' => htmlspecialchars($row['author']),
                'borrow_date' => htmlspecialchars($row['borrow_date']),
                'due_date' => htmlspecialchars($row['due_date']),
                'return_date' => $row['return_date'] ? htmlspecialchars($row['return_date']) : 'Not Returned'
            ];
        }

        $response['success'] = true;
        $response['data'] = $borrowed_books;
    } else {
        $response['message'] = "No borrowed books found.";
    }

} else {
    $response['message'] = "User not found.";
}

$stmt_user->close();
$conn->close();

// Return response as JSON
echo json_encode($response);
?>
