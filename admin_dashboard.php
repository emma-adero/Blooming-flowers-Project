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

// Fetch Service Requests
$service_res = $conn->query("SELECT id, customer_name, email, phone, service, delivery_date, address, instructions, created_at FROM service_requests ORDER BY id DESC");

// Fetch Contact Messages
$contact_res = $conn->query("SELECT id, name, email, subject, message, created_at FROM contacts ORDER BY id DESC");
?>

<section class="dashboard-section">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>! Below is the client activity overview.</p>

    <!-- Tabbed navigation or quick links -->
    <div class="dashboard-grid">
        <!-- Service Requests Section -->
        <div class="dashboard-card">
            <h3>Service Requests</h3>
            <?php if ($service_res && $service_res->num_rows > 0): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Details</th>
                                <th>Service</th>
                                <th>Delivery Date & Address</th>
                                <th>Instructions</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $service_res->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($row['email']); ?></small><br>
                                        <small><?php echo htmlspecialchars($row['phone']); ?></small>
                                    </td>
                                    <td><span class="badge"><?php echo htmlspecialchars($row['service']); ?></span></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['delivery_date'] ?: 'N/A'); ?><br>
                                        <small><?php echo htmlspecialchars($row['address'] ?: 'N/A'); ?></small>
                                    </td>
                                    <td><small><?php echo nl2br(htmlspecialchars($row['instructions'] ?: 'None')); ?></small></td>
                                    <td><small><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty-state">No service requests have been submitted yet.</p>
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
