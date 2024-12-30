<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];

    $conn = new mysqli("localhost", "root", "", "void");

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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

    $conn->close();
}
?>
