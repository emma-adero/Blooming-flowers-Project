<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Auth Check: must be logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    $allowed_statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];

    if ($order_id > 0 && in_array($status, $allowed_statuses)) {
        $conn = getConnection();
        
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        
        if ($stmt->execute()) {
            $_SESSION['admin_success_msg'] = "Order #{$order_id} status updated to '{$status}' successfully.";
        } else {
            $_SESSION['admin_error_msg'] = "Failed to update order status: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['admin_error_msg'] = "Invalid order ID or status value.";
    }
}

header("Location: admin_dashboard.php");
exit();
?>
