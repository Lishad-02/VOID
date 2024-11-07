<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
</head>
<body>

    <h2>Return a Book</h2>
    <form id="return-book-form">
        <label for="book_id">Enter Book ID:</label>
        <input type="number" id="book_id" name="book_id" required>
        <button type="submit">Return Book</button>
    </form>

    <div id="response-message"></div>

    <script>
        document.getElementById('return-book-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('returnBook.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('response-message').innerText = data.message;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('response-message').innerText = 'An error occurred while returning the book.';
            });
        });
    </script>

</body>
</html>

