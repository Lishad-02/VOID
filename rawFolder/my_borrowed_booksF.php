<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Books</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Borrowed Books</h2>
    <div id="book-list">
        Loading...
    </div>

    <script>
        // Fetch the borrowed books from the backend
        fetch('my_borrowed_booksB.php')
            .then(response => response.json())
            .then(data => {
                const bookList = document.getElementById('book-list');

                if (data.success) {
                    let html = `
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    data.data.forEach(book => {
                        html += `
                            <tr>
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.borrow_date}</td>
                                <td>${book.due_date}</td>
                                <td>${book.return_date}</td>
                            </tr>
                        `;
                    });

                    html += `</tbody></table>`;
                    bookList.innerHTML = html;
                } else {
                    bookList.innerHTML = `<p>${data.message}</p>`;
                }
            })
            .catch(error => {
                document.getElementById('book-list').innerHTML = 'Error loading data.';
                console.error('Error fetching borrowed books:', error);
            });
    </script>

</body>
</html>
