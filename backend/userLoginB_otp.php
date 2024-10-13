<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/VOID/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/VOID/PHPMailer/src/SMTP.php';


$mysqli = new mysqli("localhost", "root", "", "void");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


function generateOTP() {
    return rand(100000, 999999);
}

// Function to send the OTP via email
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'voidlibrarymanagement@gmail.com'; // Our Library Gmail address
        $mail->Password   = 'kaoj mras sotp khgl'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        $mail->setFrom('voidlibrarymanagement@gmail.com', 'VOID02');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is <b>' . $otp . '</b>. This code will expire in 5 minutes.';
        $mail->AltBody = 'Your OTP code is ' . $otp . '. This code will expire in 5 minutes.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Store the OTP 
function storeOTPInDatabase($email, $otp, $conn) {
    $expiry = date("Y-m-d H:i:s", strtotime('+5 minutes'));
    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
    
    // Check prepare() failed
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sss", $otp, $expiry, $email);
    if (!$stmt->execute()) {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

//login request

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Checku user  in the database
    $stmt = $mysqli->prepare("SELECT email, password FROM users WHERE email = ?");
    
    // Check if prepare() failed
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dbEmail, $dbPassword);
    $stmt->fetch();

    
    echo "Debug: Email: $dbEmail<br>";
   echo "Debug: DB Password: $dbPassword<br>";

    // Verify password
    if ($stmt->num_rows > 0 && password_verify($password, $dbPassword)) {
        // Generate OTP
        $otp = generateOTP();
        storeOTPInDatabase($email, $otp, $mysqli);

        // Send OTP 
        if (sendOTP($email, $otp)) {
            echo "OTP sent successfully to $email!";
           
            echo "<form id='otpRedirectForm' action='otpVerifyBF.php' method='POST'>
                    <input type='hidden' name='email' value='$email'>
                  </form>
                  <script>document.getElementById('otpRedirectForm').submit();</script>";
        } else {
            echo "Failed to send OTP!";
        }
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
}
$mysqli->close();
?>
