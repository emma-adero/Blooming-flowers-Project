<?php
$page_title = "My Requests - BLOMMING FLOWERS";
require_once 'db.php';
require_once 'header.php';

// Auth Check: must be logged in as customer/user
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Admins should see the full admin dashboard
if ($_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getConnection();

// Fetch requests for this logged-in user
$stmt = $conn->prepare("SELECT id, service, delivery_date, address, instructions, created_at FROM service_requests WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="dashboard-section">
    <h2>My Service Requests</h2>
    <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>. Here is the history of your flower service requests.</p>

    <div class="dashboard-card">
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Service Requested</th>
                            <th>Delivery Date</th>
                            <th>Delivery Address</th>
                            <th>Special Instructions</th>
                            <th>Submitted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td><span class="badge"><?php echo htmlspecialchars($row['service']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['delivery_date'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['address'] ?: 'N/A'); ?></td>
                                <td><small><?php echo nl2br(htmlspecialchars($row['instructions'] ?: 'None')); ?></small></td>
                                <td><small><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>You haven't submitted any service requests yet.</p>
                <a href="services.php" class="btn-primary" style="display: inline-block; margin-top: 15px; text-decoration: none;">Submit a Request Now</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$stmt->close();
$conn->close();
require_once 'footer.php';
?>
