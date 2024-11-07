<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Full-page background image */
        body {
            background-image: url("black-squares-pattern-background.jpg"); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            font-family: 'Times New Roman', Times, serif, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: green; /* Makes text white by default */
        }

        /* OTP form container styling */
        
        /* Form heading */
        .otp-container h2 {
            margin-bottom: 20px;
            font-size: 38px; /* Increased font size */
            font-weight: bolder; /* Bold text */
            color: ghostwhite; /* White color for better visibility */
        }

        /* Form input styling */
        .otp-container input[type="email"],
        .otp-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid white; /* White border to contrast with the blue background */
            border-radius: 5px;
            font-size: 16px;
            background-color: rgba(0, 0, 0, 0.2); /* Slightly transparent input background */
            color: ghostwhite; /* White text */
        }

        /* Input placeholder styling */
        .otp-container input::placeholder {
            font-size: 24px; /* Increased font size */
            font-weight: bold; /* Bold placeholder text */
            color: rgba(255, 255, 255, 0.8); /* Brighter white */
        }

        /* Submit button styling */
        .otp-container input[type="submit"] {
            background-color: rgba(0, 0, 0, 0.3); /* Light transparent white */
            color: ghostwhite;
            font-size: 24px; /* Increased font size */
            font-weight: bold; /* Bold text */
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .otp-container input[type="submit"]:hover {
            background-color: rgba(255, 255, 255, 0.5); /* Slightly darker on hover */
        }

        /* Success/Error message styling */
        .message {
            margin-top: 15px;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
        }

        .success-message {
            background-color: rgba(40, 167, 69, 0.8); /* Slightly transparent green */
            color: white;
            border: 1px solid rgba(40, 167, 69, 0.9);
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.8); /* Slightly transparent red */
            color: white;
            border: 1px solid rgba(220, 53, 69, 0.9);
        }
    </style>
</head>
<body>

    <!-- OTP Form -->
    <div class="otp-container">
        <h2>OTP Verification</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <input type="submit" value="Verify OTP">
        </form>

        <?php
        // Start the session
        session_start();

        // Database connection
        $mysqli = new mysqli("localhost", "root", "", "void");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $otp = isset($_POST['otp']) ? $_POST['otp'] : ''; // Check if 'otp' key exists

            // Check if email and OTP are provided
            if (!empty($email) && !empty($otp)) {
                // Check the OTP and expiry time from the database
                $stmt = $mysqli->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($dbOTP, $otpExpiry);
                $stmt->fetch();

                // Check if OTP is correct and not expired
                if ($dbOTP === $otp && strtotime($otpExpiry) > time()) {
                    // Store the user's email in the session
                    $_SESSION['email'] = $email;

                    // Redirect to bbbb.php
                    header("Location: userDashboardF.php");
                    exit(); // Ensure no further code is executed after redirect
                } else {
                    echo "<div class='message error-message'>Please enter a valid OTP!</div>";
                }

                $stmt->close();
            } else {
                echo "<div class='message error-message'>Please fill in all the required fields!</div>";
            }
        }

        $mysqli->close();
        ?>
    </div>

</body>
</html>
