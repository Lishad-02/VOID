<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the input values from the form
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];

    // Establish a database connection (Update with your actual database credentials)
    $conn = new mysqli("localhost", "root", "", "void");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the DELETE query
    $sql = "DELETE FROM books WHERE book_id='$book_id' AND title='$title'";

    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            echo "Book deleted successfully";
        } else {
            echo "No book found with the provided Book ID and Title.";
        }
    } else {
        echo "Error deleting book: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
