<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Shopping Cart - BLOMMING FLOWERS";
require_once 'header.php';

// Define products map server-side
$products = [
    'Rose Bouquet' => ['price' => 2500, 'image' => 'images/rose-flowers.jpg'],
    'Sunflower Bouquet' => ['price' => 2000, 'image' => 'images/sunflower.jpg'],
    'Orchid Arrangement' => ['price' => 3500, 'image' => 'images/cymbidium-orchid-flower.jpg'],
    'Daisy Bouquet' => ['price' => 1800, 'image' => 'images/daisies.jpg'],
    'Lily Bouquet' => ['price' => 3000, 'image' => 'images/lilies.jpg']
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Process cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $flower = isset($_POST['flower']) ? trim($_POST['flower']) : '';
        $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if (array_key_exists($flower, $products)) {
            if (isset($_SESSION['cart'][$flower])) {
                $_SESSION['cart'][$flower] += $qty;
            } else {
                $_SESSION['cart'][$flower] = $qty;
            }
        }
        header("Location: cart.php");
        exit();
    }
    
    if ($action === 'update') {
        if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $flower => $qty) {
                $qty = intval($qty);
                if (array_key_exists($flower, $products)) {
                    if ($qty <= 0) {
                        unset($_SESSION['cart'][$flower]);
                    } else {
                        $_SESSION['cart'][$flower] = $qty;
                    }
                }
            }
        }
        header("Location: cart.php");
        exit();
    }
}

if ($action === 'remove') {
    $flower = isset($_GET['flower']) ? $_GET['flower'] : '';
    if (isset($_SESSION['cart'][$flower])) {
        unset($_SESSION['cart'][$flower]);
    }
    header("Location: cart.php");
    exit();
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

// Calculate grand total
$grand_total = 0;
?>

<div style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem; position: relative; z-index: 1;">
    <h2 style="text-align: center; font-family: 'Papyrus', cursive; margin-top: 2rem;">Your Shopping Cart</h2>

    <div class="dashboard-card" style="margin-top: 20px;">
        <?php if (!empty($_SESSION['cart'])): ?>
            <form action="cart.php?action=update" method="post">
                <div class="table-wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $flower => $quantity): 
                                if (!array_key_exists($flower, $products)) continue;
                                $item_price = $products[$flower]['price'];
                                $item_total = $item_price * $quantity;
                                $grand_total += $item_total;
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($products[$flower]['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($flower); ?>" 
                                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px;">
                                    </td>
                                    <td style="font-weight: bold;"><?php echo htmlspecialchars($flower); ?></td>
                                    <td>KSh <?php echo number_format($item_price); ?></td>
                                    <td>
                                        <input type="number" name="quantities[<?php echo htmlspecialchars($flower); ?>]" 
                                               value="<?php echo $quantity; ?>" min="1" max="50" 
                                               style="width: 60px; padding: 5px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                                    </td>
                                    <td style="font-weight: bold; color: #a44b6f;">KSh <?php echo number_format($item_total); ?></td>
                                    <td>
                                        <a href="cart.php?action=remove&flower=<?php echo urlencode($flower); ?>" 
                                           class="btn-delete" style="text-decoration: none; padding: 4px 8px;">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <button type="submit" class="btn-primary" style="background-color: #555;">Update Quantities</button>
                        <a href="cart.php?action=clear" class="btn-delete" style="display: inline-block; text-decoration: none; text-align: center;" onclick="return confirm('Clear your entire cart?');">Clear Cart</a>
                    </div>
                    <div style="text-align: right;">
                        <h3 style="margin: 0 0 10px 0; font-size: 1.5rem;">Grand Total: <span style="color: #a44b6f;">KSh <?php echo number_format($grand_total); ?></span></h3>
                        <a href="checkout.php" class="btn-primary" style="display: inline-block; text-decoration: none; text-align: center; font-size: 1.1rem; padding: 12px 24px;">Proceed to Checkout</a>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                <p style="font-size: 1.2rem; color: #555;">Your shopping cart is empty!</p>
                <p>Browse our beautiful collection of fresh flowers and add them to your cart.</p>
                <a href="catalogue.php" class="btn-primary" style="display: inline-block; margin-top: 15px; text-decoration: none;">View Catalogue</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
