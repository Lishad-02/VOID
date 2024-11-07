<?php
session_start();

date_default_timezone_set('Asia/Dhaka');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/VOID/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/SMTP.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
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

    // Get the user ID and name using the user_email
    $user_query = "SELECT id, name FROM users WHERE email = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("s", $user_email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];
        $user_name = $user['name'];

        // Check if the book is available to borrow
        $book_query = "SELECT title, available_copies FROM books WHERE book_id = ?";
        $book_stmt = $conn->prepare($book_query);
        $book_stmt->bind_param("i", $book_id);
        $book_stmt->execute();
        $book_result = $book_stmt->get_result();

        if ($book_result->num_rows > 0) {
            $book = $book_result->fetch_assoc();
            $book_name = $book['title'];

            if ($book['available_copies'] > 0) {
                // Decrease available copies by 1
                $update_copies = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?";
                $update_stmt = $conn->prepare($update_copies);
                $update_stmt->bind_param("i", $book_id);
                $update_stmt->execute();

                // Insert into borrowed_books table with 9 days due
                $borrow_date = date("Y-m-d H:i:s");
                $due_date = date("Y-m-d H:i:s", strtotime("+9 days"));

                $borrow_query = "INSERT INTO borrowed_books (id, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?)";
                $borrow_stmt = $conn->prepare($borrow_query);
                $borrow_stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);
                $borrow_stmt->execute();

                $borrow_id = $conn->insert_id;  // Get the borrow ID from the last insert

                // Send confirmation email with borrowing details
                sendBorrowConfirmation($user_name, $user_email, $book_name, $borrow_id, $borrow_date, $due_date);

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

// Function to send borrowing confirmation email
function sendBorrowConfirmation($user_name, $email, $book_name, $borrow_id, $borrow_date, $due_date) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'voidlibrarymanagement@gmail.com'; // Library Gmail address
        $mail->Password   = 'kaoj mras sotp khgl'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('voidlibrarymanagement@gmail.com', 'VOID Library');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Borrow Confirmation - VOID Library';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; background-color: #f4f4f9; padding: 20px; border-radius: 10px;'>
                <div style='background-color: #283593; padding: 15px; border-radius: 10px 10px 0 0; text-align: center;'>
                    <h2 style='color: #fff; margin: 0;'>VOID Library Borrowing Receipt</h2>
                </div>
                <div style='padding: 20px;'>
                    <p style='color: #333; font-size: 1.1em; text-align: center;'>Thank you for borrowing from our library, <b>$user_name</b>!</p>
                    <div style='background-color: #e8eaf6; padding: 15px; border-radius: 8px;'>
                        <table style='width: 100%;'>
                            <tr style='font-weight: bold; color: #3949ab;'>
                                <td style='padding: 8px;'>Borrow ID:</td>
                                <td style='padding: 8px; text-align: right;'>$borrow_id</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; color: #333;'>User Email:</td>
                                <td style='padding: 8px; text-align: right; color: #333;'>$email</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; color: #333;'>Book Title:</td>
                                <td style='padding: 8px; text-align: right; color: #333;'>$book_name</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; color: #333;'>Borrow Date:</td>
                                <td style='padding: 8px; text-align: right; color: #333;'>$borrow_date</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; color: #333;'>Due Date:</td>
                                <td style='padding: 8px; text-align: right; color: #333;'>$due_date</td>
                            </tr>
                        </table>
                    </div>
                    <p style='color: #555; text-align: center; margin-top: 20px;'>Please return the book by the due date to avoid late fees.</p>
                    <p style='text-align: center; color: #999;'>Thank you for choosing VOID Library!</p>
                </div>
            </div>
        ";
        $mail->AltBody = "Borrow Confirmation: Borrow ID: $borrow_id, User Email: $email, Book Title: $book_name, Borrow Date: $borrow_date, Due Date: $due_date. Please return by due date to avoid fees.";

        $mail->send();
        echo "Confirmation email sent successfully!";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
