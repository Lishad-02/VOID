<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Review</title>
    <link rel="stylesheet" href="review-style.css">
</head>
<body>
    <!-- Search Bar -->
    <div class="top-search-bar">
        <input id="search-bar" type="text" placeholder="Search for book reviews...">
        <button id="search-btn">Search</button>
    </div>

    <!-- Review Form -->
    <form action="review.php" method="POST" class="review-form" id="reviewForm">
        <h1>Rate this Book</h1>

        
        <input type="hidden" id="book_id" name="book_id" value="">
        <input type="hidden" id="book_name" name="book_name" value="">

        <!-- Star Rating -->
        <div class="stars" id="stars">
            <span data-value="1" class="star">&#9733;</span>
            <span data-value="2" class="star">&#9733;</span>
            <span data-value="3" class="star">&#9733;</span>
            <span data-value="4" class="star">&#9733;</span>
            <span data-value="5" class="star">&#9733;</span>
        </div>

        <!-- Comment Box -->
        <textarea name="comment" id="comment" placeholder="Write your opinion..."></textarea>

        <div class="buttons">
            <button type="submit" id="submitBtn">Submit</button>
            <button type="reset">Cancel</button>
        </div>
    </form>

    <!-- Other Reviews Button -->
    <div class="see-others-container">
        <button id="see-others-btn" onclick="window.location.href='show-review.html'">See Other Book Reviews</button>
    </div>

    <script src="script.js"></script>
</body>
</html>
