<!DOCTYPE html>
<html>

<head>
    <title>Add New Book</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
</head>

<body>
    <main class="container">
        <h2>Add New Book</h2>
        <form method="post" action="">
            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn" required><br><br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required><br><br>
            <input type="submit" name="submit" value="Add Book">
        </form>
        <a href="index.php">
            <button type="button">Go to Main Page</button>
        </a>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $isbn = trim($_POST['isbn']);
            $title = trim($_POST['title']);

            // Connect to database
            $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iebookstore');

            // Check connection
            if ($mysqli->connect_error) {
                die('Connection failed: ' . $mysqli->connect_error);
            }

            // Prepare and execute the insert statement
            $stmt = $mysqli->prepare('INSERT INTO booklist (isbn, title) VALUES (?, ?)');
            $stmt->bind_param('ss', $isbn, $title);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>New book added successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
            $mysqli->close();
        }
        ?>
    </main>
</body>

</html>