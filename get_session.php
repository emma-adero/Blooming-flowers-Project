<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
header('Content-Type: application/json');
echo json_encode([
    'logged_in' => isset($_SESSION['user_id']),
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : '',
    'cart_count' => $cart_count
]);
?>
