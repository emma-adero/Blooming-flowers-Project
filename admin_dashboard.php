<?php
$page_title = "Admin Dashboard - BLOMMING FLOWERS";
require_once 'db.php';
require_once 'header.php';

// Auth Check: must be logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = getConnection();

// Fetch Customer Orders
$orders_res = $conn->query("SELECT id, customer_name, email, phone, delivery_date, address, instructions, total_price, status, created_at FROM orders ORDER BY id DESC");



// Fetch Contact Messages
$contact_res = $conn->query("SELECT id, name, email, subject, message, created_at FROM contacts ORDER BY id DESC");
?>

<section class="dashboard-section">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>! Below is the client activity overview.</p>

    <?php if (isset($_SESSION['admin_success_msg'])): ?>
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px 15px; border-radius: 6px; border: 1px solid #c3e6cb; margin: 15px 0 5px 0;">
            <?php echo htmlspecialchars($_SESSION['admin_success_msg']); unset($_SESSION['admin_success_msg']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['admin_error_msg'])): ?>
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; border: 1px solid #f5c6cb; margin: 15px 0 5px 0;">
            <?php echo htmlspecialchars($_SESSION['admin_error_msg']); unset($_SESSION['admin_error_msg']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabbed navigation or quick links -->
    <div class="dashboard-grid">
        <!-- Customer Orders Section -->
        <div class="dashboard-card">
            <h3>Customer Orders</h3>
            <?php if ($orders_res && $orders_res->num_rows > 0): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Details</th>
                                <th>Items Ordered</th>
                                <th>Total Price</th>
                                <th>Delivery Details & Instructions</th>
                                <th>Status / Action</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $orders_res->fetch_assoc()): 
                                // Fetch items for this order
                                $stmt_items = $conn->prepare("SELECT flower_name, quantity, price FROM order_items WHERE order_id = ?");
                                $stmt_items->bind_param("i", $row['id']);
                                $stmt_items->execute();
                                $items_result = $stmt_items->get_result();
                                $items_list = [];
                                while ($item = $items_result->fetch_assoc()) {
                                    $items_list[] = htmlspecialchars($item['flower_name']) . " (" . $item['quantity'] . ")";
                                }
                                $stmt_items->close();
                            ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($row['id']); ?></strong></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($row['email']); ?></small><br>
                                        <small><?php echo htmlspecialchars($row['phone']); ?></small>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9rem; line-height: 1.4;">
                                            <?php echo implode("<br>", $items_list); ?>
                                        </div>
                                    </td>
                                    <td style="font-weight: bold; color: #a44b6f;">KSh <?php echo number_format($row['total_price']); ?></td>
                                    <td>
                                        <strong>Date:</strong> <?php echo htmlspecialchars($row['delivery_date']); ?><br>
                                        <small><strong>Addr:</strong> <?php echo htmlspecialchars($row['address']); ?></small>
                                        <?php if (!empty($row['instructions'])): ?>
                                            <br><small style="color: #666; font-style: italic;">"<?php echo htmlspecialchars($row['instructions']); ?>"</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="update_order_status.php" method="post" style="display: flex; flex-direction: column; gap: 5px;">
                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc; font-size: 0.85rem;">
                                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Processing" <?php echo $row['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn-primary" style="padding: 4px 8px; font-size: 0.8rem; margin: 0; border: none; cursor: pointer;">Update Status</button>
                                        </form>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty-state">No orders have been placed yet.</p>
            <?php endif; ?>
        </div>

        <!-- Contact Messages Section -->
        <div class="dashboard-card" style="margin-top: 30px;">
            <h3>Contact Messages (Saved Messages)</h3>
            <?php if ($contact_res && $contact_res->num_rows > 0): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $contact_res->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($row['email']); ?></small>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($row['subject'] ?: 'No Subject'); ?></strong></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                                    <td><small><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                                    <td>
                                        <a href="delete_contact.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Are you sure you want to delete this message?');">
                                           Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty-state">No contact messages have been saved yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
$conn->close();
require_once 'footer.php';
?>
