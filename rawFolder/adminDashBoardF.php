<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Welcome Message Section -->
<div class="welcome-message">
    <form action="welcome.php" method="POST">
        <h1>Welcome </h1>
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
    </form>
</div>

        <h1 class="dashboard-title">ADMIN DASHBOARD !!</h1>

        <div class="buttons-section">
            <form action="updateBooksF.php" method="POST">
                <button class="dashboard-button">MANAGE BOOKS HERE</button>
            </form>
            <form action="manage_users.php" method="POST">
                <button class="dashboard-button">MANAGE USERS HERE</button>
            </form>
        </div>
     

        <form action="logout.php" method="POST">
            <button class="logout-button">LOGOUT</button>
        </form>


        <div class="search-container">
            <form action="search_results.php" method="GET">
                <input type="text" placeholder="Search for books..." name="search" class="search-input">
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        
    </div>
</body>
</html>
