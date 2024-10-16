<?php
session_start();

// Set timezone to Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
    $user_email = $_SESSION['email'];  // Get user email from session

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "void";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the user_id using the user_email
    $user_query = "SELECT id FROM users WHERE email = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("s", $user_email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

        // Check if the book is available to borrow
        $book_query = "SELECT available_copies FROM books WHERE book_id = ?";
        $book_stmt = $conn->prepare($book_query);
        $book_stmt->bind_param("i", $book_id);
        $book_stmt->execute();
        $book_result = $book_stmt->get_result();

        if ($book_result->num_rows > 0) {
            $book = $book_result->fetch_assoc();

            if ($book['available_copies'] > 0) {
                // Deduct available copies by 1
                $update_copies = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?";
                $update_stmt = $conn->prepare($update_copies);
                $update_stmt->bind_param("i", $book_id);
                $update_stmt->execute();

                // Insert into borrowed_books table with 9 days due date
                $borrow_date = date("Y-m-d H:i:s");
                $due_date = date("Y-m-d H:i:s", strtotime("+9 days"));

                // Use user_id instead of user_email
                $borrow_query = "INSERT INTO borrowed_books (id, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?)";
                $borrow_stmt = $conn->prepare($borrow_query);
                $borrow_stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);
                $borrow_stmt->execute();

                echo "Book borrowed successfully!";
            } else {
                echo "No available copies to borrow.";
            }
        } else {
            echo "Book not found.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>
