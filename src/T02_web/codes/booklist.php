<!DOCTYPE html>
<html>

<head>
  <title>Book List</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
</head>

<body>
  <main class="container">
    <h1>Book List</h1>
    <?php
    $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iebookstore');

    // Check connection
    if ($mysqli->connect_error) {
      die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
    }

    $query = "SELECT isbn, title FROM booklist";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>ISBN</th><th>Title</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "</tr>";
      }
      echo "</table>";
      $result->free();
    } else {
      echo "<p>No books found.</p>";
    }

    $mysqli->close();
    ?>
    <a href="index.php">
      <button type="button">Go to Main Page</button>
    </a>
  </main>
</body>

</html>