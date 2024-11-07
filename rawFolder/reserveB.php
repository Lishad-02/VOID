<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if book_id is provided
if (!isset($_GET['book_id'])) {
    echo "No book selected!";
    exit();
}

$book_id = $_GET['book_id'];
$user_email = $_SESSION['email'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert a new reservation
$sql = "INSERT INTO reservations (user_email, book_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $user_email, $book_id);

if ($stmt->execute()) {
    echo "Book reserved successfully!";
} else {
    echo "Error: Could not reserve the book. Please try again later.";
}

$stmt->close();
$conn->close();
?>
