<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $book_id = $_POST['book_id'];
    $book_old_name = $_POST['book_old_name'];
    $book_new_name = $_POST['book_new_name'];

    
    $conn = new mysqli("localhost", "root", "", "void");

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "UPDATE books SET title='$book_new_name' WHERE book_id='$book_id' AND title='$book_old_name'";

    if ($conn->query($sql) === TRUE) {
        echo "Book name updated successfully";
    } else {
        echo "Error updating book name: " . $conn->error;
    }

    
    $conn->close();
}
?>
