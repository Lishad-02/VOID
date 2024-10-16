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

// If no copies are available, find the next return time
$next_available_time = null;
if ($book['available_copies'] == 0) {
    // Fetch the next due date only for future dates
    $next_due_sql = "SELECT MIN(due_date) as next_due FROM borrowed_books WHERE book_id = ? AND due_date > NOW()";
    $next_due_stmt = $conn->prepare($next_due_sql);
    $next_due_stmt->bind_param("i", $book_id);
    $next_due_stmt->execute();
    $next_due_result = $next_due_stmt->get_result();

    if ($next_due_result->num_rows > 0) {
        $next_due = $next_due_result->fetch_assoc();
        $next_available_time = $next_due['next_due'];
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

                    // Update the countdown message
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

        <!-- Borrow Book Button -->
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
                    // Pass the next_available_time to the countdown function
                    const nextAvailableTime = "<?php echo $next_available_time; ?>";
                    startCountdown(nextAvailableTime);
                </script>
            <?php endif; ?>

        <?php endif; ?>

        <a href="search_book.php">Back to Search Results</a>
    </div>
</body>
</html>
