<!DOCTYPE html>
<html>

<head>
    <title>Add New Order</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
</head>

<body>
    <main class="container">
        <h2>Add New Order</h2>
        <form method="post" action="">
            <label for="isbn">Book ISBN:</label>
            <select name="isbn" id="isbn" required>
                <?php
                // Connect to database
                $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iebookstore');
                if ($mysqli->connect_error) {
                    die('Connection failed: ' . $mysqli->connect_error);
                }
                // Query booklist for ISBN and title
                $result = $mysqli->query('SELECT isbn, title FROM booklist');
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['isbn']) . '">'
                            . htmlspecialchars($row['isbn']) . ' - ' . htmlspecialchars($row['title'])
                            . '</option>';
                    }
                    $result->free();
                } else {
                    echo '<option value="">No books found</option>';
                }
                ?>
            </select><br><br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" required><br><br>
            <input type="submit" name="submit" value="Add Order">
        </form>
        <a href="index.php">
            <button type="button">Go to Main Page</button>
        </a>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $isbn = trim($_POST['isbn']);
            $quantity = intval($_POST['quantity']);

            // Use existing connection or create again if not available
            if (!isset($mysqli) || $mysqli->connect_error) {
                $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iebookstore');
                if ($mysqli->connect_error) {
                    die('Connection failed: ' . $mysqli->connect_error);
                }
            }

            // orderID is auto-increment; do not insert manually
            $stmt = $mysqli->prepare('INSERT INTO orderdetail (isbn, quantity) VALUES (?, ?)');
            $stmt->bind_param('si', $isbn, $quantity);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>Order added successfully!</p>";
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