<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['book_id'])) {
    echo "No book selected!";
    exit();
}

$book_id = $_GET['book_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get book details
$sql = "SELECT title, available_copies FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "Book not found!";
    exit();
}

// If no copies  available, find the latest due date based on the maximum borrow ID
$next_available_time = null;
if ($book['available_copies'] == 0) {
    $latest_borrow_sql = "SELECT due_date FROM borrowed_books WHERE book_id = ? ORDER BY id DESC LIMIT 1";
    $latest_borrow_stmt = $conn->prepare($latest_borrow_sql);
    $latest_borrow_stmt->bind_param("i", $book_id);
    $latest_borrow_stmt->execute();
    $latest_borrow_result = $latest_borrow_stmt->get_result();

    if ($latest_borrow_result->num_rows > 0) {
        $latest_borrow = $latest_borrow_result->fetch_assoc();
        $next_available_time = $latest_borrow['due_date'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Summary</title>
    <link rel="stylesheet" href="book_summary.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('999.jpg'); /* Add your background image */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
            color: #333;
            line-height: 1.6;
        }

        /* Container for the book summary */
        .summary-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* White background with transparency */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading Styles */
        .summary-container h1 {
            text-align: center;
            color: #007BFF;
        }

        /* Paragraph Styles */
        .summary-container p {
            margin: 15px 0;
            font-size: 18px;
        }

        /* Button Styles */
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Countdown Styles */
        #countdown {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffeb3b;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        /* Link Styles */
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function startCountdown(endTime) {
            const countDownDate = new Date(endTime).getTime();

            const countdownFunction = setInterval(function() {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(countdownFunction);
                    document.getElementById("countdown").innerHTML = "The book is now available!";
                } else {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("countdown").innerHTML = "The next copy will be available after " + 
                        days + " days, " + hours + " hours, " + minutes + " minutes, " + seconds + " seconds.";
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <div class="summary-container">
        <h1>Book Details</h1>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
        <p><strong>Available Copies:</strong> <?php echo htmlspecialchars($book['available_copies']); ?></p>

        
        <?php if ($book['available_copies'] > 0): ?>
            <form method="POST" action="BorrowBookB.php">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <input type="hidden" name="user_email" value="<?php echo $_SESSION['email']; ?>">
                <button type="submit" name="borrow">Borrow Book</button>
            </form>
        <?php else: ?>
            <p><strong>Sorry, no copies are available to borrow at the moment.</strong></p>

            <?php if ($next_available_time): ?>
                <p id="countdown">The next copy will be available after:</p>
                <script>
                    const nextAvailableTime = "<?php echo $next_available_time; ?>";
                    startCountdown(nextAvailableTime);
                </script>
            <?php endif; ?>

        <?php endif; ?>

        <a href="search_book.php">Back to Search Results</a>
    </div>
</body>
</html>