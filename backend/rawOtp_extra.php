<?php

// Database connection
$mysqli = new mysqli("localhost", "root", "", "void");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check the OTP and expiry time from the database
    $stmt = $mysqli->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($dbOTP, $otpExpiry);
    $stmt->fetch();

    // Check if OTP is correct and not expired
    if ($dbOTP === $otp && strtotime($otpExpiry) > time()) {
        echo "OTP verified successfully!";
        // Here you can log in the user or redirect them to the dashboard
    } else {
        echo "Invalid or expired OTP!";
    }

    $stmt->close();
}

$mysqli->close();

?>