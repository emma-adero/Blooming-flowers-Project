<?php
$page_title = "Contact - BLOMMING FLOWERS";
require_once 'header.php';

// Pre-fill if logged in
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>

<div style="position: relative; z-index: 1;">
    <section class="contact-section">
        <div class="contact-grid">
            <div class="contact-info">
                <h2>Get in touch</h2>
                <p>Have questions or want to place an order? Reach out to us.</p>
                <ul>
                    <li><strong>Address:</strong> 123 Flower Lane, Nairobi</li>
                    <li><strong>Phone:</strong> (+254 720 154)</li>
                    <li><strong>Email:</strong> info@blommingflowers.com</li>
                    <li><strong>Hours:</strong> Mon - Sat: 9am - 6pm</li>
                </ul>
            </div>

            <form class="contact-form" action="save_contact.php" method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject">

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required></textarea>

                <button type="submit" class="btn-primary">Send Message</button>
            </form>
        </div>
    </section>
</div>

<?php require_once 'footer.php'; ?>
