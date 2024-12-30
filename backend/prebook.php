<?php
session_start();


if (!isset($_SESSION['email'])) {
    die("Please log in to pre-book a book.");
}


if (!isset($_GET['title']) || empty($_GET['title'])) {
    die("Invalid book title.");
}

$bookTitle = $_GET['title'];
$userEmail = $_SESSION['email'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT book_id, title FROM upcoming_books WHERE title = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bookTitle);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Book not found.");
}

$book = $result->fetch_assoc();
$bookId = $book['book_id'];
$bookName = $book['title'];


$insertSql = "INSERT INTO prebooked_books (book_id, book_name, user_email, prebook_date) VALUES (?, ?, ?, NOW())";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("iss", $bookId, $bookName, $userEmail);

if ($insertStmt->execute()) {

    echo "<script>
        alert('Pre-booking successful! You have pre-booked: $bookName.');
        window.location.href = 'userDashboardF.php'; // Change 'another_page.php' to your desired page
    </script>";
} else {

    echo "<script>
        alert('Error pre-booking the book: " . addslashes($conn->error) . "');
        window.history.back(); // Redirects back to the previous page
    </script>";
}


$stmt->close();
$insertStmt->close();
$conn->close();
