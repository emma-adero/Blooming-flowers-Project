<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Auth Check: must be logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$conn = getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting message.";
    }

    $stmt->close();
}

$conn->close();
?>