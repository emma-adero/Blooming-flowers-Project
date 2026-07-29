<?php
$page_title = "Services - BLOMMING FLOWERS";
require_once 'header.php';

// Check if a specific flower was passed via URL parameter
$selected_flower = isset($_GET['flower']) ? trim($_GET['flower']) : '';

// Pre-fill user data if logged in
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>

<div style="position: relative; z-index: 1;">
    <section class="services-section">
        <h2>Our Services</h2>
        <div class="services-grid">
            <div class="service-item">
                <div class="service-icon">🌸</div>
                <div class="service-content">
                    <h3>Custom Bouquets</h3>
                    <p>Hand-tied arrangements for every occasion, crafted with seasonal blooms.</p>
                </div>
            </div>
            <div class="service-item">
                <div class="service-icon">🌿</div>
                <div class="service-content">
                    <h3>Plant Care &amp; Consultation</h3>
                    <p>Expert advice and care packages to keep your plants thriving.</p>
                </div>
            </div>
            <div class="service-item">
                <div class="service-icon">🚚</div>
                <div class="service-content">
                    <h3>Delivery &amp; Events</h3>
                    <p>Reliable local delivery and event floral design services.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="service-request-section">
        <h2>Service Request</h2>
        <p>Tell us what you need and we will get back to you soon.</p>

        <form class="service-request-form" action="save_service_request.php" method="post">

            <label for="customer_name">Your Name</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($user_name); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required>

            <label for="service">Service/Flower Needed</label>
            <select id="service" name="service" required>
                <option value="">Select a service or flower</option>
                <!-- Standard Services -->
                <option value="Custom Bouquets" <?php echo $selected_flower == 'Custom Bouquets' ? 'selected' : ''; ?>>Custom Bouquets</option>
                <option value="Plant Care" <?php echo $selected_flower == 'Plant Care' ? 'selected' : ''; ?>>Plant Care & Consultation</option>
                <option value="Delivery & Events" <?php echo $selected_flower == 'Delivery & Events' ? 'selected' : ''; ?>>Delivery & Events</option>
                <option value="Wedding Flowers" <?php echo $selected_flower == 'Wedding Flowers' ? 'selected' : ''; ?>>Wedding Flowers</option>
                
                <!-- Specific Flower Catalogue Products -->
                <optgroup label="Catalogue Flowers">
                    <option value="Rose Bouquet" <?php echo $selected_flower == 'Rose Bouquet' ? 'selected' : ''; ?>>Rose Bouquet</option>
                    <option value="Sunflower Bouquet" <?php echo $selected_flower == 'Sunflower Bouquet' ? 'selected' : ''; ?>>Sunflower Bouquet</option>
                    <option value="Orchid Arrangement" <?php echo $selected_flower == 'Orchid Arrangement' ? 'selected' : ''; ?>>Orchid Arrangement</option>
                    <option value="Daisy Bouquet" <?php echo $selected_flower == 'Daisy Bouquet' ? 'selected' : ''; ?>>Daisy Bouquet</option>
                    <option value="Lily Bouquet" <?php echo $selected_flower == 'Lily Bouquet' ? 'selected' : ''; ?>>Lily Bouquet</option>
                </optgroup>
            </select>

            <label for="delivery_date">Delivery Date</label>
            <input type="date" id="delivery_date" name="delivery_date">

            <label for="address">Delivery Address</label>
            <textarea id="address" name="address" rows="3"></textarea>

            <label for="instructions">Special Instructions</label>
            <textarea id="instructions" name="instructions" rows="5"></textarea>

            <button type="submit" class="btn-primary">
                Submit Request
            </button>

        </form>
    </section>
</div>

<?php require_once 'footer.php'; ?>
