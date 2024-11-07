<?php
// Start session
session_start();


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
    <style>
        body {
            background-image: url('path/to/your/background-image.jpg'); /* Set your background image */
            background-size: cover; /* Cover the entire page */
            background-repeat: no-repeat; /* Prevent repeating */
            color: white; /* Change text color for better contrast */
            font-family: Arial, sans-serif; /* Set a font */
        }

        .results-container {
            background-color: rgba(0, 0, 0, 0.7); /* Dark background for better readability */
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 800px; /* Limit the container width */
        }

        table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* Merge borders */
            margin-top: 20px; /* Space above the table */
        }

        th, td {
            border: 1px solid #fff; /* White borders for the table */
            padding: 10px; /* Space inside cells */
            text-align: left; /* Align text to the left */
        }

        th {
            background-color: rgba(255, 255, 255, 0.2); /* Light background for headers */
        }

        button {
            padding: 5px 10px;
            background-color: #007BFF; /* Bootstrap primary color */
            color: white; /* Text color */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        a {
            text-decoration: none; /* Remove underline from links */
        }
    </style>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (json_decode($_GET['results'], true) as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['available_copies']); ?></td>
                                <td>
                                    
                                    <?php if (isset($book['book_id'])): ?>
                                        <a href="searchBookDetails.php?book_id=<?php echo urlencode($book['book_id']); ?>">
                                            <button>Details</button>
                                        </a>
                                    <?php else: ?>
                                        <span>No details available</span>
                                    <?php endif; ?>
                                </td>
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
