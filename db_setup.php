<?php
require_once 'db.php';

$conn = getConnection();

// 1. Create orders table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    customer_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    delivery_date DATE NOT NULL,
    address TEXT NOT NULL,
    instructions TEXT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Create order_items table
$sql_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    flower_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$success = true;
$message = "";

if ($conn->query($sql_orders) === TRUE) {
    $message .= "Table 'orders' checked/created successfully.<br>";
} else {
    $success = false;
    $message .= "Error creating table 'orders': " . $conn->error . "<br>";
}

if ($conn->query($sql_order_items) === TRUE) {
    $message .= "Table 'order_items' checked/created successfully.<br>";
} else {
    $success = false;
    $message .= "Error creating table 'order_items': " . $conn->error . "<br>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Setup - BLOMMING FLOWERS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f9f9f9; }
        .container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        h1 { color: #a44b6f; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .btn { display: inline-block; background-color: #a44b6f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Setup</h1>
        <p>This script sets up the database tables for the Online Ordering and Cart System.</p>
        <hr>
        <p class="<?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
        <?php if ($success): ?>
            <p class="success">Database setup completed successfully!</p>
            <a href="index.php" class="btn">Go to Home</a>
        <?php else: ?>
            <p class="error">There was an error setting up the database. Please check your MySQL server in XAMPP.</p>
        <?php endif; ?>
    </div>
</body>
</html>
