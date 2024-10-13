<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "void");

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if search input is submitted
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_term = $mysqli->real_escape_string($_POST['search']);

    // SQL query to search for books by title or author
    $query = "SELECT title, author, available_copies FROM books WHERE title LIKE ? OR author LIKE ?";
    $search_term_wildcard = "%" . $search_term . "%";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $search_term_wildcard, $search_term_wildcard);
    $stmt->execute();
    $stmt->bind_result($title, $author, $available_copies);
    $results = [];

    // Fetch results into an array
    while ($stmt->fetch()) {
        $results[] = [
            'title' => $title,
            'author' => $author,
            'available_copies' => $available_copies
        ];
    }
    $stmt->close();

    // Redirect with results
    header("Location: searchBookF.php?search_term=" . urlencode($search_term) . "&results=" . urlencode(json_encode($results)));
} else {
    // If no search term is provided, redirect back with an error
    header("Location: searchBookF.php?error=" . urlencode("No search term provided!"));
    exit();
}

// Close the database connection
$mysqli->close();
?>
