<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $book_id = $_POST['book_id'];
    $new_total_copies = $_POST['new_copies'];

    $conn = new mysqli("localhost", "root", "", "void");

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT available_copies FROM books WHERE book_id='$book_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $available_copies = $row['available_copies'];

        if ($new_total_copies >= $available_copies) {
            
            $update_sql = "UPDATE books SET total_copies='$new_total_copies' WHERE book_id='$book_id'";

            if ($conn->query($update_sql) === TRUE) {
                echo "Book copies updated successfully";
            } else {
                echo "Error updating copies: " . $conn->error;
            }
        } else {
            echo "Error: Total copies cannot be less than available copies.";
        }
    } else {
        echo "Error: Book not found.";
    }

    
    $conn->close();
}
?>

