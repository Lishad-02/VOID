<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> VOID User Login</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div class="login-page">
        <div class="form-container">
            <h1>User Login</h1>
            <form action="userLoginB_otp.php" method="POST"> 
                <input type="email" name="email" placeholder="Email" required> 
                <input type="password" name="password" placeholder="Password" required> 
                <input type="submit" name="login" value="Login"> 
            </form>
            <p class="signup">Don't have an account? <a href="userRegistrationF.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
