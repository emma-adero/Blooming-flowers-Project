<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);

// Count items in cart
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'BLOMMING FLOWERS / Local Floriculture'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=3">
</head>
<body>

    <h1>BLOMMING FLOWERS</h1>
    <nav>
        <ul>
            <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="catalogue.php" class="<?php echo $current_page == 'catalogue.php' ? 'active' : ''; ?>">Catalogue</a></li>
            <li><a href="services.php" class="<?php echo $current_page == 'services.php' ? 'active' : ''; ?>">Services</a></li>
            <li><a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
            <li><a href="cart.php" class="<?php echo $current_page == 'cart.php' ? 'active' : ''; ?>">Cart (<?php echo $cart_count; ?>)</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php" class="<?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>">Admin Dashboard</a></li>
                <?php else: ?>
                    <li><a href="my_orders.php" class="<?php echo $current_page == 'my_orders.php' ? 'active' : ''; ?>">My Orders</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="logout-btn">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php" class="<?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Login</a></li>
                <li><a href="register.php" class="<?php echo $current_page == 'register.php' ? 'active' : ''; ?>">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
