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
            <a href="services.php?flower=Rose%20Bouquet" class="order-btn">Order Now</a>
        </div>

        <div class="flower-card">
            <img src="images/sunflower.jpg" alt="Sunflower Bouquet">
            <h3>Sunflower Bouquet</h3>
            <p>Bright and cheerful sunflower arrangement.</p>
            <h4>KSh 2,000</h4>
            <a href="services.php?flower=Sunflower%20Bouquet" class="order-btn">Order Now</a>
        </div>

        <div class="flower-card">
            <img src="images/cymbidium-orchid-flower.jpg" alt="Orchid Arrangement">
            <h3>Orchid Arrangement</h3>
            <p>Elegant orchid flowers for special occasions.</p>
            <h4>KSh 3,500</h4>
            <a href="services.php?flower=Orchid%20Arrangement" class="order-btn">Order Now</a>
        </div>

        <div class="flower-card">
            <img src="images/daisies.jpg" alt="Daisy Bouquet">
            <h3>Daisy Bouquet</h3>
            <p>Fresh white daisies perfect for gifting.</p>
            <h4>KSh 1,800</h4>
            <a href="services.php?flower=Daisy%20Bouquet" class="order-btn">Order Now</a>
        </div>

        <div class="flower-card">
            <img src="images/lilies.jpg" alt="Lily Bouquet">
            <h3>Lily Bouquet</h3>
            <p>Elegant lilies with a beautiful fragrance.</p>
            <h4>KSh 3,000</h4>
            <a href="services.php?flower=Lily%20Bouquet" class="order-btn">Order Now</a>
        </div>

    </section>
</div>

<?php require_once 'footer.php'; ?>
