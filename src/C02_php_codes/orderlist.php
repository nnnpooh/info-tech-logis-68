<!DOCTYPE html>
<html>

<head>
    <title>Order List</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
</head>

<body>
    <main class="container">
        <h2>Order List</h2>
        <?php
        // Connect to database
        $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iebookstore');
        if ($mysqli->connect_error) {
            die('Connection failed: ' . $mysqli->connect_error);
        }

        // Query all orders joined with book titles
        $query = "SELECT orderdetail.orderID, orderdetail.isbn, booklist.title, orderdetail.quantity
            FROM orderdetail
            JOIN booklist ON orderdetail.isbn = booklist.isbn";
        $result = $mysqli->query($query);

        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Order ID</th><th>ISBN</th><th>Title</th><th>Quantity</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['orderID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            $result->free();
        } else {
            echo "<p>No orders found.</p>";
        }

        $mysqli->close();
        ?>
        <a href="index.php">
            <button type="button">Go to Main Page</button>
        </a>
    </main>
</body>

</html>