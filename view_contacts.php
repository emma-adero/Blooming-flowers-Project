<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Auth Check: must be logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

header("Location: admin_dashboard.php");
exit();
?>
