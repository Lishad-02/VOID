<?php 
session_start();
date_default_timezone_set('Asia/Dhaka');

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/VOID/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/SMTP.php';

// Configuration constants
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'void');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'voidlibrarymanagement@gmail.com');
define('SMTP_PASSWORD', 'kaoj mras sotp khgl');
define('SMTP_PORT', 465);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle book borrowing
if (isset($_GET['book_id'], $_GET['user_email'])) {
    $book_id = $_GET['book_id'];
    $user_email = $_GET['user_email'];

    $conn = connectToDatabase();
    if (!$conn) {
        echo "Database connection failed.";
        exit();
    }

    $user = getUserDetails($conn, $user_email);
    if ($user) {
        $user_id = $user['id'];
        $user_name = $user['name'];

        $book = getBookDetails($conn, $book_id);
        if ($book && $book['available_copies'] > 0) {
            borrowBook($conn, $book_id, $user_id, $user_email, $user_name, $book['title']);
        } else {
            echo "No available copies to borrow or book not found.";
        }
    } else {
        echo "User not found.";
    }
    $conn->close();
} else {
    echo "Invalid request.";
}

// Database connection function
function connectToDatabase() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        return false;
    }
    return $conn;
}

// Fetch user details
function getUserDetails($conn, $user_email) {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

// Fetch book details
function getBookDetails($conn, $book_id) {
    $stmt = $conn->prepare("SELECT title, available_copies FROM books WHERE book_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

// Borrow book function
function borrowBook($conn, $book_id, $user_id, $user_email, $user_name, $book_name) {
    // Update available copies
    $update_stmt = $conn->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?");
    $update_stmt->bind_param("i", $book_id);
    $update_stmt->execute();

    // Insert into borrowed_books
    $borrow_date = date("Y-m-d H:i:s");
    $due_date = date("Y-m-d H:i:s", strtotime("+9 days"));

    $borrow_stmt = $conn->prepare("INSERT INTO borrowed_books (id, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?)");
    $borrow_stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);
    $borrow_stmt->execute();

    $borrow_id = $conn->insert_id; // Get the borrow ID

    sendBorrowConfirmation($user_name, $user_email, $book_name, $borrow_id, $borrow_date, $due_date);

    echo "!";
}

// Send confirmation email function
function sendBorrowConfirmation($user_name, $email, $book_name, $borrow_id, $borrow_date, $due_date) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'VOID Library');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Borrow Confirmation - VOID Library';
        $mail->Body = "
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
        echo "Your borrow reciept sent successfully to your email!";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

