<!DOCTYPE html>
<html>

<head>
    <title>Order Drink</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.lime.css">
</head>

<body>
    <main class="container">
        <h2>Order Drink</h2>
        <form method="post" action="">
            <label for="customer_name">Your Name</label>
            <input type="text" name="customer_name" id="customer_name" required>
            <article>
                <div class="grid">
                    <div>
                        <label for="drink_name">Drink Name</label>
                        <select name="drink_name" id="drink_name" required>
                            <option value="Coke">Coke</option>
                            <option value="Orange Juice">Orange Juice</option>
                            <option value="Beer">Beer</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" required>
                    </div>
                </div>
                <input type="submit" name="submit" value="Order">
            </article>
            <a href="./drinkorder.php"><kbd style="cursor:pointer;">Restart</kbd></a>

        </form>

        <?php
        // Uncomment to see all error for debugging
        // error_reporting(E_ALL); // Report all errors, warnings, and notices
        // ini_set('display_errors', '1'); // Display errors in the browser
        // ini_set('display_startup_errors', '1'); // Display errors that occur during PHP startup

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $drink_name = trim($_POST['drink_name']);
            $customer_name = trim($_POST['customer_name']);
            $quantity = trim($_POST['quantity']);

            // Connect to database
            $mysqli = new mysqli('127.0.0.1', 'root', '1234', 'iedrink');

            // Check connection
            if ($mysqli->connect_error) {
                die('Connection failed: ' . $mysqli->connect_error);
            }

            // Prepare and execute the insert statement
            $stmt = $mysqli->prepare('INSERT INTO orders (drink_name, customer_name, quantity) VALUES (?, ?, ?)');
            $stmt->bind_param('ssi', $drink_name, $customer_name, $quantity);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>New order added successfully!</p>";

                // Send webhook (Test)
                // $webhookUrlTest = "https://pmX-ctXXX-n8n.iecmu.com/webhook-test/order";
                // sendWebhook($mysqli, $mysqli->insert_id, $webhookUrlTest);
                // Send webhook (Production)
                // $webhookUrlProd = "https://pmX-ctXXX-n8n.iecmu.com/webhook/order";
                // sendWebhook($mysqli, $mysqli->insert_id, $webhookUrlProd);
                // -----------
            } else {
                echo "<p style='color:red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
            $mysqli->close();
        }

        function sendWebhook($mysqli, $order_id, $webhookURL)
        {
            // Get the data from database
            $query = $mysqli->prepare('SELECT id, customer_name, drink_name, quantity, created_at FROM orders WHERE id = ?');
            $query->bind_param('i', $order_id);
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc();

            // Prepare payload for webhook
            $webhookData = [
                'id'            => $row['id'],
                'customer_name' => $row['customer_name'],
                'drink_name'    => $row['drink_name'],
                'quantity'      => $row['quantity'],
                'created_at'    => $row['created_at'],
            ];

            // Send HTTP request using curl
            $ch = curl_init($webhookURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
            $response = curl_exec($ch);

            // Display message
            if ($response === false) {
                echo "<p style='color:orange;'>Webhook failed: " . htmlspecialchars(curl_error($ch)) . "</p>";
            } else {
                echo "<p style='color:green;'>Webhook sent.</p>";
            }
        }
        ?>
    </main>
</body>

</html>