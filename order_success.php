<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

$page_title = "Order Confirmed - BLOMMING FLOWERS";
require_once 'header.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($order_id <= 0) {
    die("Invalid Order ID.");
}

$conn = getConnection();

// Fetch order details. Security check: only allow owner or admin to see
if ($user_role === 'admin') {
    $stmt = $conn->prepare("SELECT id, customer_name, email, phone, delivery_date, address, instructions, total_price, status, created_at FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
} else {
    $stmt = $conn->prepare("SELECT id, customer_name, email, phone, delivery_date, address, instructions, total_price, status, created_at FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
}

$stmt->execute();
$order_res = $stmt->get_result();

if ($order_res->num_rows === 0) {
    $stmt->close();
    $conn->close();
    die("Order not found or access denied.");
}

$order = $order_res->fetch_assoc();
$stmt->close();

// Fetch order items
$stmt_items = $conn->prepare("SELECT flower_name, quantity, price FROM order_items WHERE order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_res = $stmt_items->get_result();
$items = [];
while ($row = $items_res->fetch_assoc()) {
    $items[] = $row;
}
$stmt_items->close();
$conn->close();
?>

<div style="max-width: 800px; margin: 3rem auto; padding: 0 1rem; position: relative; z-index: 1;">
    <div class="dashboard-card" style="text-align: center; padding: 40px 30px; border-radius: 12px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.4);">
        <div style="font-size: 4rem; color: #4caf50; margin-bottom: 15px;">✓</div>
        <h2 style="font-family: 'Papyrus', cursive; color: #a44b6f; margin-top: 0;">Order Placed Successfully!</h2>
        <p style="font-size: 1.1rem; color: #555; max-width: 600px; margin: 15px auto;">
            Thank you for your purchase, <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>! 
            Your order has been recorded and is being processed. 
            A confirmation receipt summary is detailed below.
        </p>

        <div style="text-align: left; background: rgba(255, 255, 255, 0.9); border-radius: 8px; border: 1px solid #ddd; padding: 25px; margin: 30px 0;">
            <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #a44b6f; padding-bottom: 10px; margin-bottom: 20px;">
                <span><strong>Order ID:</strong> #<?php echo $order['id']; ?></span>
                <span class="badge" style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;"><?php echo htmlspecialchars($order['status']); ?></span>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1; min-width: 200px;">
                    <h4 style="margin: 0 0 8px 0; color: #a44b6f;">Delivery Information</h4>
                    <p style="margin: 0 0 5px 0; font-size: 0.95rem;"><strong>Recipient:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p style="margin: 0 0 5px 0; font-size: 0.95rem;"><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p style="margin: 0 0 5px 0; font-size: 0.95rem;"><strong>Delivery Date:</strong> <?php echo htmlspecialchars($order['delivery_date']); ?></p>
                    <p style="margin: 0 0 5px 0; font-size: 0.95rem;"><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h4 style="margin: 0 0 8px 0; color: #a44b6f;">Special Instructions</h4>
                    <p style="margin: 0; font-size: 0.95rem; color: #555; font-style: italic;">
                        <?php echo $order['instructions'] ? nl2br(htmlspecialchars($order['instructions'])) : 'None provided.'; ?>
                    </p>
                </div>
            </div>

            <h4 style="margin: 0 0 10px 0; color: #a44b6f; border-bottom: 1px solid #eee; padding-bottom: 5px;">Ordered Items</h4>
            <div style="margin-bottom: 15px;">
                <?php foreach ($items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95rem;">
                        <span>
                            <strong><?php echo htmlspecialchars($item['flower_name']); ?></strong> 
                            <small style="color:#666;">(&times;<?php echo $item['quantity']; ?>)</small>
                        </span>
                        <span style="font-weight: bold;">KSh <?php echo number_format($item['price'] * $item['quantity']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px;">
                <span style="font-size: 1.1rem; font-weight: bold;">Amount Paid:</span>
                <span style="font-size: 1.3rem; font-weight: bold; color: #a44b6f;">KSh <?php echo number_format($order['total_price']); ?></span>
            </div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <?php if ($user_role !== 'admin'): ?>
                <a href="my_orders.php" class="btn-primary" style="text-decoration: none;">View Order History</a>
            <?php else: ?>
                <a href="admin_dashboard.php" class="btn-primary" style="text-decoration: none;">Admin Dashboard</a>
            <?php endif; ?>
            <a href="catalogue.php" class="btn-primary" style="text-decoration: none; background-color: #555;">Continue Shopping</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
