<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Request Method and Empty Cart Check
if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Define products map server-side for price resolution
$products = [
    'Rose Bouquet' => 2500,
    'Sunflower Bouquet' => 2000,
    'Orchid Arrangement' => 3500,
    'Daisy Bouquet' => 1800,
    'Lily Bouquet' => 3000
];

// 3. Retrieve and Sanitize Form Inputs
$user_id = $_SESSION['user_id'];
$customer_name = trim($_POST['customer_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$delivery_date = trim($_POST['delivery_date']);
$address = trim($_POST['address']);
$instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';

if (empty($customer_name) || empty($email) || empty($phone) || empty($delivery_date) || empty($address)) {
    die("Error: Please fill in all required fields.");
}

// Calculate total price server-side
$total_price = 0;
$items_to_insert = [];

foreach ($_SESSION['cart'] as $flower => $quantity) {
    if (array_key_exists($flower, $products)) {
        $qty = intval($quantity);
        if ($qty <= 0) continue;
        $price = $products[$flower];
        $subtotal = $price * $qty;
        $total_price += $subtotal;
        $items_to_insert[] = [
            'name' => $flower,
            'quantity' => $qty,
            'price' => $price
        ];
    }
}

if (empty($items_to_insert)) {
    die("Error: Your cart is empty or contains invalid items.");
}

$conn = getConnection();

// Start transaction for database integrity
$conn->begin_transaction();

try {
    // 4. Insert into 'orders' table
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, customer_name, email, phone, delivery_date, address, instructions, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt_order->bind_param("issssssd", $user_id, $customer_name, $email, $phone, $delivery_date, $address, $instructions, $total_price);
    
    if (!$stmt_order->execute()) {
        throw new Exception("Order insertion failed: " . $stmt_order->error);
    }
    
    $order_id = $conn->insert_id;
    $stmt_order->close();

    // 5. Insert into 'order_items' table
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, flower_name, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach ($items_to_insert as $item) {
        $stmt_item->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
        if (!$stmt_item->execute()) {
            throw new Exception("Order item insertion failed: " . $stmt_item->error);
        }
    }
    
    $stmt_item->close();

    // Commit transaction if all inserts succeed
    $conn->commit();

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: order_success.php?id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Rollback changes on error
    $conn->rollback();
    echo "<h2>Failed to place order</h2>";
    echo "<p>Error details: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='checkout.php'>Go back to checkout</a>";
} finally {
    $conn->close();
}
?>
