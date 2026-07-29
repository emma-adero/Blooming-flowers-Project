<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Checkout - BLOMMING FLOWERS";
require_once 'header.php';

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    ?>
    <div style="max-width: 600px; margin: 4rem auto; padding: 0 1rem; position: relative; z-index: 1; text-align: center;">
        <div class="dashboard-card" style="padding: 40px 20px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.4); background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
            <h2 style="font-family: 'Papyrus', cursive; color: #a44b6f;">Checkout</h2>
            <p style="font-size: 1.1rem; margin-top: 20px;">You must be logged in to place an order.</p>
            <p>Logging in allows you to track your order status and view your full purchase history.</p>
            <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
                <a href="login.php" class="btn-primary" style="text-decoration: none; padding: 10px 20px; border-radius: 30px;">Login Now</a>
                <a href="register.php" class="btn-primary" style="text-decoration: none; background-color: #555; padding: 10px 20px; border-radius: 30px;">Register Account</a>
            </div>
        </div>
    </div>
    <?php
    require_once 'footer.php';
    exit();
}

// 2. Empty Cart Check
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Define products map server-side
$products = [
    'Rose Bouquet' => ['price' => 2500, 'image' => 'images/rose-flowers.jpg'],
    'Sunflower Bouquet' => ['price' => 2000, 'image' => 'images/sunflower.jpg'],
    'Orchid Arrangement' => ['price' => 3500, 'image' => 'images/cymbidium-orchid-flower.jpg'],
    'Daisy Bouquet' => ['price' => 1800, 'image' => 'images/daisies.jpg'],
    'Lily Bouquet' => ['price' => 3000, 'image' => 'images/lilies.jpg']
];

$grand_total = 0;
$checkout_items = [];

foreach ($_SESSION['cart'] as $flower => $quantity) {
    if (array_key_exists($flower, $products)) {
        $price = $products[$flower]['price'];
        $item_total = $price * $quantity;
        $grand_total += $item_total;
        $checkout_items[] = [
            'name' => $flower,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $item_total
        ];
    }
}

// Default values
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$min_delivery_date = date('Y-m-d', strtotime('+1 day')); // Must order at least 1 day in advance
?>

<div style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem; position: relative; z-index: 1;">
    <h2 style="text-align: center; font-family: 'Papyrus', cursive; margin-top: 2rem;">Order Checkout</h2>

    <div class="checkout-layout" style="display: flex; gap: 30px; margin-top: 2rem; flex-wrap: wrap;">
        <!-- Left Side: Order Form -->
        <div style="flex: 1; min-width: 300px;">
            <section class="service-request-section" style="margin: 0; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
                <h3>Delivery Details</h3>
                <p>Please provide the delivery details for your order.</p>

                <form class="service-request-form" action="place_order.php" method="post" style="max-width: 100%; margin-top: 1.5rem;">
                    <label for="customer_name">Recipient Name</label>
                    <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($user_name); ?>" required>

                    <label for="email">Contact Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>

                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" required placeholder="e.g. 0712345678">

                    <label for="delivery_date">Delivery Date</label>
                    <input type="date" id="delivery_date" name="delivery_date" min="<?php echo $min_delivery_date; ?>" required>

                    <label for="address">Delivery Address</label>
                    <textarea id="address" name="address" rows="3" required placeholder="Enter street, house number, neighborhood or city details..."></textarea>

                    <label for="instructions">Special Instructions (Card message, gate code, etc.)</label>
                    <textarea id="instructions" name="instructions" rows="4" placeholder="e.g., Please write 'Happy Anniversary' on the card and leave with the concierge."></textarea>

                    <button type="submit" class="btn-primary" style="margin-top: 1.5rem; font-size: 1.1rem; width: 100%; padding: 12px;">
                        Place Order (KSh <?php echo number_format($grand_total); ?>)
                    </button>
                </form>
            </section>
        </div>

        <!-- Right Side: Order Summary -->
        <div style="flex: 0.8; min-width: 300px;">
            <div class="dashboard-card" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.4); padding: 25px;">
                <h3 style="border-bottom: 2px solid #a44b6f; padding-bottom: 10px; margin-bottom: 15px;">Order Summary</h3>
                
                <div class="checkout-summary-list">
                    <?php foreach ($checkout_items as $item): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem;">
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                <small style="color: #666;">Qty: <?php echo $item['quantity']; ?> &times; KSh <?php echo number_format($item['price']); ?></small>
                            </div>
                            <span style="font-weight: bold; align-self: center;">KSh <?php echo number_format($item['total']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr style="border: 0; border-top: 1px solid #ccc; margin: 15px 0;">

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.2rem; font-weight: bold;">Grand Total</span>
                    <span style="font-size: 1.4rem; font-weight: bold; color: #a44b6f;">KSh <?php echo number_format($grand_total); ?></span>
                </div>

                <div style="margin-top: 25px; text-align: center;">
                    <a href="cart.php" style="color: #a44b6f; text-decoration: none; font-weight: bold; font-size: 0.95rem;">&larr; Back to Shopping Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
