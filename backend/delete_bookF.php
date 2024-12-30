<?php
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "void";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM prebooked_books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prebooked Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('im10.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        .message {
            text-align: center;
            color: #ff4d4d;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Prebooking for Books</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Pre Booking ID</th>
                        <th>Pre Book ISBN</th>
                        <th>Book Name</th>
                        <th>User Email</th>
                        <th>Prebook Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['prebook_id']; ?></td>
                            <td><?php echo $row['book_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td><?php echo $row['prebook_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="message">No records found in the Prebooked Books table.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>