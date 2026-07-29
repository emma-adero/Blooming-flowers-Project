<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'header.php';

// Auth Check: must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Admin redirect
if ($_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getConnection();

// Fetch orders for this customer
$stmt = $conn->prepare("SELECT id, delivery_date, address, instructions, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();

$orders = [];
while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>

<div style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem; position: relative; z-index: 1;">
    <h2 style="text-align: center; font-family: 'Papyrus', cursive; margin-top: 2rem;">My Order History</h2>
    <p style="text-align: center; max-width: 600px; margin: 10px auto 30px auto;">
        Hello, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>. 
        Track your active flower orders and view past purchases below.
    </p>

    <div class="dashboard-card">
        <?php if (!empty($orders)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date Placed</th>
                            <th>Items Ordered</th>
                            <th>Total Price</th>
                            <th>Delivery Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): 
                            // Fetch items for this order
                            $stmt_items = $conn->prepare("SELECT flower_name, quantity, price FROM order_items WHERE order_id = ?");
                            $stmt_items->bind_param("i", $order['id']);
                            $stmt_items->execute();
                            $items_result = $stmt_items->get_result();
                            $items_list = [];
                            while ($item = $items_result->fetch_assoc()) {
                                $items_list[] = htmlspecialchars($item['flower_name']) . " (" . $item['quantity'] . ")";
                            }
                            $stmt_items->close();
                        ?>
                            <tr>
                                <td><strong>#<?php echo $order['id']; ?></strong></td>
                                <td><small><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></small></td>
                                <td>
                                    <div style="font-size: 0.95rem; font-weight: 500;">
                                        <?php echo implode("<br>", $items_list); ?>
                                    </div>
                                </td>
                                <td style="font-weight: bold; color: #a44b6f;">KSh <?php echo number_format($order['total_price']); ?></td>
                                <td>
                                    <div style="font-size: 0.85rem; line-height: 1.4;">
                                        <strong>Date:</strong> <?php echo htmlspecialchars($order['delivery_date']); ?><br>
                                        <strong>Addr:</strong> <?php echo htmlspecialchars($order['address']); ?>
                                        <?php if (!empty($order['instructions'])): ?>
                                            <br><small style="color: #666; font-style: italic;">"<?php echo htmlspecialchars($order['instructions']); ?>"</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $status = strtolower($order['status']);
                                    $badge_class = 'badge';
                                    if ($status === 'pending') {
                                        $badge_class .= ' badge-warning';
                                    } elseif ($status === 'processing') {
                                        $badge_class .= ' badge-info';
                                    } elseif ($status === 'completed') {
                                        $badge_class .= ' badge-success';
                                    } elseif ($status === 'cancelled') {
                                        $badge_class .= ' badge-danger';
                                    }
                                    ?>
                                    <span class="<?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                <p style="font-size: 1.2rem; color: #555;">You haven't placed any orders yet!</p>
                <p>Order fresh, custom-arranged flower bouquets directly to your doorstep.</p>
                <a href="catalogue.php" class="btn-primary" style="display: inline-block; margin-top: 15px; text-decoration: none;">Browse Catalogue</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$conn->close();
require_once 'footer.php'; 
?>
