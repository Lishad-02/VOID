<?php
session_start();

if (isset($_POST['login'])) {
    
    $connection = mysqli_connect("localhost", "root", "", "void");

    
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM admin WHERE email = ?";
    
    if ($stmt = mysqli_prepare($connection, $query)) {
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);

        
        if ($row = mysqli_fetch_assoc($result)) {
            
            if (password_verify($password, $row['password'])) {
                
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];

                
                header("Location:adminDashBoardF.php");
                exit();
            } else {
                
                echo "<br><br><center><span class='alert-danger'>Wrong Password. Please try again.</span></center>";
            }
        } else {
            
            echo "<br><br><center><span class='alert-danger'>Email not found. Please check your email and try again.</span></center>";
        }

        
        mysqli_stmt_close($stmt);
    }

    
    mysqli_close($connection);
}
?>
