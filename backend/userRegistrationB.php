<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $connection = mysqli_connect("localhost", "root", "", "void");


    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }


    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    
    if (empty($name) || empty($email) || empty($dob) || empty($mobile) || empty($password)) {
        echo "<script type='text/javascript'>alert('Please fill in all fields.');</script>";
        exit;
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script type='text/javascript'>alert('Invalid email format.');</script>";
        exit;
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $connection->prepare("INSERT INTO users (name, email, dob, mobile, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $dob, $mobile, $hashedPassword);

    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Registration successful! You may now log in.');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    mysqli_close($connection);
}
?>
