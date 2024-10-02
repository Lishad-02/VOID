<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-page">
        <div class="form-container">
            <h1>Register User</h1>
            <form action="userRegistrationB.php" method="POST"> 
            
                <input type="text" name="name" placeholder="Full Name" required> 
                <input type="email" name="email" placeholder="Email" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="text" name="mobile" placeholder="Mobile Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Register">
            </form>
            <p class="signup">Already have an account? <a href="userLoginF.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
