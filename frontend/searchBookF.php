<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="search_results.css"> <!-- Optional CSS -->
</head>
<body>
    <div class="results-container">
        <h1>Search Results</h1>

        <?php if (isset($_GET['search_term'])): ?>
            <h2>Results for "<?php echo htmlspecialchars($_GET['search_term']); ?>"</h2>

            <?php if (isset($_GET['error'])): ?>
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['results']) && !empty($_GET['results'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Available Copies</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (json_decode($_GET['results'], true) as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['available_copies']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No books found.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>No search term provided!</p>
        <?php endif; ?>

        <a href="userDashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
