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

if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_term = $mysqli->real_escape_string($_POST['search']);
    
   
    $genre = ''; // Fetch the genre from your database or input

    $query = "SELECT book_id, title, author, available_copies, genre FROM books WHERE title LIKE ? OR author LIKE ?";
    $search_term_wildcard = "%" . $search_term . "%";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $search_term_wildcard, $search_term_wildcard);
    $stmt->execute();
    $stmt->bind_result($book_id, $title, $author, $available_copies, $genre);
    $results = [];

    // Fetch results into an array
    while ($stmt->fetch()) {
        $results[] = [
            'book_id' => $book_id,  
            'title' => $title,
            'author' => $author,
            'available_copies' => $available_copies,
            'genre' => $genre // Store genre
        ];
    }
    $stmt->close();

    // Save to search history
    $user_email = $_SESSION['email']; // Assuming email is stored in session
    $insert_query = "INSERT INTO search_history (user_email, search_term, genre) VALUES (?, ?, ?)";
    $insert_stmt = $mysqli->prepare($insert_query);
    foreach ($results as $book) {
        $insert_stmt->bind_param("sss", $user_email, $search_term, $book['genre']);
        $insert_stmt->execute();
    }
    $insert_stmt->close();

    header("Location: searchBookF.php?search_term=" . urlencode($search_term) . "&results=" . urlencode(json_encode($results)));
} else {
    header("Location: searchBookF.php?error=" . urlencode("No search term provided!"));
    exit();
}

$mysqli->close();
?>
