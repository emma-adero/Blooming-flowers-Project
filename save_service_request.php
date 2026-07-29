<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $delivery_date = $_POST['delivery_date'] ?: null;
    $address = $_POST['address'] ?: null;
    $instructions = $_POST['instructions'] ?: null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $conn = getConnection();

    $stmt = $conn->prepare("INSERT INTO service_requests
    (customer_name, email, phone, service, delivery_date, address, instructions, user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssi",
        $customer_name,
        $email,
        $phone,
        $service,
        $delivery_date,
        $address,
        $instructions,
        $user_id
    );

    if ($stmt->execute()) {
        echo "<h2>Service request saved successfully!</h2>";
        if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
            echo "<a href='my_requests.php'>View My Requests</a>";
        } else {
            echo "<a href='services.php'>Back to Services</a>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>