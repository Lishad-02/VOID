<?php

session_start();


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


$mysqli = new mysqli("localhost", "root", "", "void");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


$user_email = $_SESSION['email'];


$search_genres = [];
$search_books = [];

// Query to fetch genres based on search history
$search_query = "SELECT genre FROM search_history WHERE user_email = ? GROUP BY genre";
$search_stmt = $mysqli->prepare($search_query);
$search_stmt->bind_param("s", $user_email);
$search_stmt->execute();
$search_stmt->bind_result($genre);

// Fetch unique genres from search history
while ($search_stmt->fetch()) {
    if (!in_array($genre, $search_genres)) {
        $search_genres[] = $genre;
    }
}
$search_stmt->close();

// Fetch books based on genres from search history
foreach ($search_genres as $genre) {
    $book_query = "SELECT book_id, title, author, available_copies FROM books WHERE genre = ?";
    $book_stmt = $mysqli->prepare($book_query);
    $book_stmt->bind_param("s", $genre);
    $book_stmt->execute();
    $book_stmt->bind_result($book_id, $title, $author, $available_copies);

    while ($book_stmt->fetch()) {
        $search_books[] = [
            'book_id' => $book_id,
            'title' => $title,
            'author' => $author,
            'available_copies' => $available_copies
        ];
    }
    $book_stmt->close();
}

//Fetch Books Based on Borrowing History with Priority 
$borrowed_genre_counts = [];
$high_priority_books = [];
$low_priority_books = [];

// Query to fetch genres based on borrowing history 
$borrow_query = "SELECT b.genre, COUNT(*) as genre_count FROM books b 
                 JOIN borrowed_books bb ON b.book_id = bb.book_id 
                 JOIN users u ON u.id = bb.id WHERE u.email = ? 
                 GROUP BY b.genre ORDER BY genre_count DESC";
$borrow_stmt = $mysqli->prepare($borrow_query);
$borrow_stmt->bind_param("s", $user_email);
$borrow_stmt->execute();
$borrow_stmt->bind_result($genre, $genre_count);

// Store genres in array 
while ($borrow_stmt->fetch()) {
    $borrowed_genre_counts[$genre] = $genre_count;
}
$borrow_stmt->close();

// Separate high and low priority genres
$highest_priority_genre = key($borrowed_genre_counts);
$remaining_genres = array_slice($borrowed_genre_counts, 1, null, true);

// Fetch books in highest priority genre excluding already borrowed
$book_query = "SELECT book_id, title, author, available_copies FROM books 
               WHERE genre = ? AND book_id NOT IN (SELECT book_id FROM borrowed_books 
               JOIN users ON users.id = borrowed_books.id WHERE users.email = ?)";
$book_stmt = $mysqli->prepare($book_query);
$book_stmt->bind_param("ss", $highest_priority_genre, $user_email);
$book_stmt->execute();
$book_stmt->bind_result($book_id, $title, $author, $available_copies);

while ($book_stmt->fetch()) {
    $high_priority_books[] = [
        'book_id' => $book_id,
        'title' => $title,
        'author' => $author,
        'available_copies' => $available_copies
    ];
}
$book_stmt->close();

// Fetch books in remaining low-priority genres excluding already borrowed
foreach ($remaining_genres as $genre => $count) {
    $book_query = "SELECT book_id, title, author, available_copies FROM books 
                   WHERE genre = ? AND book_id NOT IN (SELECT book_id FROM borrowed_books 
                   JOIN users ON users.id = borrowed_books.id WHERE users.email = ?)";
    $book_stmt = $mysqli->prepare($book_query);
    $book_stmt->bind_param("ss", $genre, $user_email);
    $book_stmt->execute();
    $book_stmt->bind_result($book_id, $title, $author, $available_copies);

    while ($book_stmt->fetch()) {
        $low_priority_books[] = [
            'book_id' => $book_id,
            'title' => $title,
            'author' => $author,
            'available_copies' => $available_copies
        ];
    }
    $book_stmt->close();
}


$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Books</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('999.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .overlay {
            padding: 20px;
            border-radius: 10px;
            background: transparent;
        }

        h1,
        h2 {
            text-align: center;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 5px;
        }

        .table-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .action-button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {

            th,
            td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <h1>Recommended Books for You</h1>

    <h2>High Priority Recommendations </h2>
    <?php if (!empty($high_priority_books)): ?>
        <div class="table-container">
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
                    <?php foreach ($high_priority_books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['available_copies']); ?></td>
                            <td>
                                <a href="searchBookDetails.php?book_id=<?php echo urlencode($book['book_id']); ?>">
                                    <button class="action-button">Details</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No high-priority recommendations available based on your borrowing history.</p>
    <?php endif; ?>

    <h2>Low Priority Recommendations </h2>
    <?php if (!empty($low_priority_books)): ?>
        <div class="table-container">
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
                    <?php foreach ($low_priority_books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['available_copies']); ?></td>
                            <td>
                                <a href="searchBookDetails.php?book_id=<?php echo urlencode($book['book_id']); ?>">
                                    <button class="action-button">Details</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No low-priority recommendations available based on your borrowing history.</p>
    <?php endif; ?>

    <!-- Recommendations Based on Search History -->
    <h2>Recommendations Based on Search </h2>
    <?php if (!empty($search_books)): ?>
        <div class="table-container">
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
                    <?php foreach ($search_books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['available_copies']); ?></td>
                            <td>
                                <a href="searchBookDetails.php?book_id=<?php echo urlencode($book['book_id']); ?>">
                                    <button class="action-button">Details</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No recommendations available based on your search history.</p>
    <?php endif; ?>
</body>

</html>