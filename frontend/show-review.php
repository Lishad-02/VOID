<?php
// Start session
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

//Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);

// connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get book_id 
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
} else {
    die("Book ID not provided!");
}

// Fetch reviews for the book
$sql = "SELECT book_name, rating, comment FROM reviews WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();


$stmt->close();

// Check if reviews exist or not
if ($result->num_rows > 0) {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $reviews = [];
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reviews</title>
    <link rel="stylesheet" href="show_review.css">
    <style>
        body {
            background-image: url('admin1.jpg');

            background-size: cover;

            background-repeat: no-repeat;

            background-attachment: fixed;

            font-family: Arial, sans-serif;
            margin: 0;
            color: white;

        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);

            border-radius: 10px;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .review {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }

        .stars {
            color: #ffcc00;
            font-size: 20px;
        }

        .review-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .comment {
            font-size: 16px;
            color: #ddd;

        }

        a {
            text-decoration: none;
            color: #00AEEF;

        }

        a:hover {
            text-decoration: underline;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Reviews for "<?php echo htmlspecialchars($reviews[0]['book_name'] ?? 'Unknown Book'); ?>"</h1>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <div class="stars">
                        <?php

                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $review['rating'] ? "&#9733;" : "&#9734;";
                        }
                        ?>
                    </div>
                    <div class="comment">
                        <?php echo htmlspecialchars($review['comment']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews available for this book.</p>
        <?php endif; ?>
        <a href="userDashboardF.php">Back </a>
    </div>
</body>

</html>