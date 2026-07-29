<?php
$page_title = "Catalogue - BLOMMING FLOWERS";
require_once 'header.php';
?>

<div style="position: relative; z-index: 1;">
    <h2 style="text-align: center; font-family: 'Papyrus', cursive; margin-top: 2rem;">Flower Catalogue</h2>
    
    <section class="catalogue">

        <div class="flower-card">
            <img src="images/rose-flowers.jpg" alt="Rose Bouquet">
            <h3>Rose Bouquet</h3>
            <p>Fresh red roses wrapped beautifully.</p>
            <h4>KSh 2,500</h4>
            <form action="cart.php?action=add" method="post" class="add-to-cart-form">
                <input type="hidden" name="flower" value="Rose Bouquet">
                <div class="qty-selector" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <label for="qty_rose">Qty:</label>
                    <input type="number" id="qty_rose" name="quantity" value="1" min="1" max="50" style="width: 50px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                </div>
                <button type="submit" class="order-btn" style="border: none; cursor: pointer; width: 100%; font-family: inherit;">Add to Cart</button>
            </form>
        </div>

        <div class="flower-card">
            <img src="images/sunflower.jpg" alt="Sunflower Bouquet">
            <h3>Sunflower Bouquet</h3>
            <p>Bright and cheerful sunflower arrangement.</p>
            <h4>KSh 2,000</h4>
            <form action="cart.php?action=add" method="post" class="add-to-cart-form">
                <input type="hidden" name="flower" value="Sunflower Bouquet">
                <div class="qty-selector" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <label for="qty_sunflower">Qty:</label>
                    <input type="number" id="qty_sunflower" name="quantity" value="1" min="1" max="50" style="width: 50px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                </div>
                <button type="submit" class="order-btn" style="border: none; cursor: pointer; width: 100%; font-family: inherit;">Add to Cart</button>
            </form>
        </div>

        <div class="flower-card">
            <img src="images/cymbidium-orchid-flower.jpg" alt="Orchid Arrangement">
            <h3>Orchid Arrangement</h3>
            <p>Elegant orchid flowers for special occasions.</p>
            <h4>KSh 3,500</h4>
            <form action="cart.php?action=add" method="post" class="add-to-cart-form">
                <input type="hidden" name="flower" value="Orchid Arrangement">
                <div class="qty-selector" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <label for="qty_orchid">Qty:</label>
                    <input type="number" id="qty_orchid" name="quantity" value="1" min="1" max="50" style="width: 50px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                </div>
                <button type="submit" class="order-btn" style="border: none; cursor: pointer; width: 100%; font-family: inherit;">Add to Cart</button>
            </form>
        </div>

        <div class="flower-card">
            <img src="images/daisies.jpg" alt="Daisy Bouquet">
            <h3>Daisy Bouquet</h3>
            <p>Fresh white daisies perfect for gifting.</p>
            <h4>KSh 1,800</h4>
            <form action="cart.php?action=add" method="post" class="add-to-cart-form">
                <input type="hidden" name="flower" value="Daisy Bouquet">
                <div class="qty-selector" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <label for="qty_daisy">Qty:</label>
                    <input type="number" id="qty_daisy" name="quantity" value="1" min="1" max="50" style="width: 50px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                </div>
                <button type="submit" class="order-btn" style="border: none; cursor: pointer; width: 100%; font-family: inherit;">Add to Cart</button>
            </form>
        </div>

        <div class="flower-card">
            <img src="images/lilies.jpg" alt="Lily Bouquet">
            <h3>Lily Bouquet</h3>
            <p>Elegant lilies with a beautiful fragrance.</p>
            <h4>KSh 3,000</h4>
            <form action="cart.php?action=add" method="post" class="add-to-cart-form">
                <input type="hidden" name="flower" value="Lily Bouquet">
                <div class="qty-selector" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <label for="qty_lily">Qty:</label>
                    <input type="number" id="qty_lily" name="quantity" value="1" min="1" max="50" style="width: 50px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">
                </div>
                <button type="submit" class="order-btn" style="border: none; cursor: pointer; width: 100%; font-family: inherit;">Add to Cart</button>
            </form>
        </div>

    </section>
</div>

<?php require_once 'footer.php'; ?>
