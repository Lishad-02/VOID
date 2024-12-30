<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'void';

$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : null;
$book_name = isset($_POST['book_name']) ? trim($_POST['book_name']) : null;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : null;


if (!$book_id || !$book_name || !$rating || !$comment) {
    die("Error: All fields are required.");
}


$sql = "INSERT INTO reviews (book_id, book_name, rating, comment, created_at) 
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmt->bind_param("isis", $book_id, $book_name, $rating, $comment);

if ($stmt->execute()) {
    echo "Review submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
