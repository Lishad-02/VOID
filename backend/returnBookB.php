<?php 
session_start();

date_default_timezone_set('Asia/Dhaka');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle the return book process
if (isset($_POST['return'])) {
    $book_id = $_POST['book_id'];
    $user_email = $_SESSION['email'];  // Get user email from session

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "void";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the user_id using the user_email
    $user_query = "SELECT id FROM users WHERE email = ?";
    $user_stmt = $conn->prepare($user_query);
    if (!$user_stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $user_stmt->bind_param("s", $user_email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

        // Check if the book is borrowed by the user
        $borrowed_query = "SELECT * FROM borrowed_books WHERE book_id = ? AND id = ?";
        $borrowed_stmt = $conn->prepare($borrowed_query);
        if (!$borrowed_stmt) {
            die("Preparation failed: " . $conn->error);
        }
        $borrowed_stmt->bind_param("ii", $book_id, $user_id);
        $borrowed_stmt->execute();
        $borrowed_result = $borrowed_stmt->get_result();

        if ($borrowed_result->num_rows > 0) {
            // Remove the book from borrowed_books
            $delete_borrowed = "DELETE FROM borrowed_books WHERE book_id = ? AND id = ?";
            $delete_stmt = $conn->prepare($delete_borrowed);
            if (!$delete_stmt) {
                die("Preparation failed: " . $conn->error);
            }
            $delete_stmt->bind_param("ii", $book_id, $user_id);
            $delete_stmt->execute();

            // Increment available copies of the book
            $update_copies = "UPDATE books SET available_copies = available_copies + 1 WHERE book_id = ?";
            $update_stmt = $conn->prepare($update_copies);
            if (!$update_stmt) {
                die("Preparation failed: " . $conn->error);
            }
            $update_stmt->bind_param("i", $book_id);
            $update_stmt->execute();

            // Check if the book is reserved
            $reservation_query = "SELECT user_email FROM reservations WHERE book_id = ? ORDER BY reservation_date ASC LIMIT 1";
            $reservation_stmt = $conn->prepare($reservation_query);
            if (!$reservation_stmt) {
                die("Preparation failed: " . $conn->error);
            }
            $reservation_stmt->bind_param("i", $book_id);
            $reservation_stmt->execute();
            $reservation_result = $reservation_stmt->get_result();

            if ($reservation_result->num_rows > 0) {
                // Get the first reserver's email
                $reserver = $reservation_result->fetch_assoc();
                $reserver_email = $reserver['user_email'];

                // Delete the reservation for this user and book
                $delete_reservation = "DELETE FROM reservations WHERE book_id = ? AND user_email = ?";
                $delete_res_stmt = $conn->prepare($delete_reservation);
                if (!$delete_res_stmt) {
                    die("Preparation failed: " . $conn->error);
                }
                $delete_res_stmt->bind_param("is", $book_id, $reserver_email);
                $delete_res_stmt->execute();

                // Pass details to borrowBookB.php
                header("Location: BorrowBookBxx.php?book_id=$book_id&user_email=$reserver_email");
                exit();
            } else {
                echo "Book returned successfully! No reservations.";
            }
        } else {
            echo "You have not borrowed this book.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>

<!-- HTML form for returning a book -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
</head>
<body>
    <h1>Return Book</h1>
    <form method="POST" action="">
        <label for="book_id">Enter Book ID to Return:</label>
        <input type="number" id="book_id" name="book_id" required>
        <button type="submit" name="return">Return Book</button>
    </form>
</body>
</html>
