<?php
// Start session
session_start();


if (!isset($_SESSION['email'])) {
    
    header("Location: login.php");
    exit();
}


$mysqli = new mysqli("localhost", "root", "", "void");


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = $_SESSION['email'];


$query = "SELECT name FROM users WHERE email = ?";
$stmt = $mysqli->prepare($query);


if (!$stmt) {
    die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
}

// Bind parameters and execute
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// If username is not found, set a default
if (empty($username)) {
    $username = "User"; // Default name if no username found
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user_dashboard.css">
</head>
<body>
    <!-- Main container for the dashboard -->
    <div class="dashboard-container">
        <!-- Welcome Message Section -->
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        </div>

        <h1 class="dashboard-title">USER DASHBOARD !!</h1>

        <div class="buttons-section">
            <form action="recommendBookF.php" method="POST">
                <button class="dashboard-button">BORROW BOOKS HERE</button>
            </form>
            <form action="my_borrowed_booksF.php" method="POST">
                <button class="dashboard-button">SHOW MY BORROWED BOOKS</button>
            </form>
        </div>

        <form action="returnBookB.php" method="POST">
            <button class="request-button">RETURN BOOK !!</button>
        </form>

        <form action="logout.php" method="POST">
            <button class="logout-button">LOGOUT</button>
        </form>

        <div class="search-container">
            <form action="searchBooksB.php" method="post">
                <input type="text" placeholder="Search for books..." name="search" class="search-input">
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
    </div>
</body>
</html>
