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
    <!-- Load centralized JavaScript files -->
    <script src="navbar.js" defer></script>
    <script src="validation.js" defer></script>
</head>
<body>

    <h1>BLOMMING FLOWERS</h1>
    <nav>
        <ul id="navMenu">
            <li><a href="index.html" id="nav-index">Home</a></li>
            <li><a href="about.html" id="nav-about">About Us</a></li>
            <li><a href="catalogue.html" id="nav-catalogue">Catalogue</a></li>
            <li><a href="services.html" id="nav-services">Services</a></li>
            <li><a href="contact.html" id="nav-contact">Contact</a></li>
            <li><a href="cart.php" id="nav-cart">Cart (<?php echo $cart_count; ?>)</a></li>
        </ul>
    </nav>
