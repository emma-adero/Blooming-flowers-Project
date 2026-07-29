<?php
$page_title = "Register - BLOMMING FLOWERS";
require_once 'db.php';
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($fullname) && !empty($email) && !empty($username) && !empty($password)) {
        $conn = getConnection();
        
        // Check if username already exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            $stmt_check->close();
            
            // Register user
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer'; // Default registration role is customer
            
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, email, fullname, role) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssss", $username, $hashed_pass, $email, $fullname, $role);
            
            if ($stmt_insert->execute()) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt_insert->close();
        }
        $conn->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Register Account</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
                <br>
                <a href="login.php" class="btn-primary" style="display:inline-block; margin-top:10px; text-decoration:none; text-align:center;">Proceed to Login</a>
            </div>
        <?php else: ?>
            <form action="register.php" method="post" class="auth-form" id="registerForm">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">

                <button type="submit" class="btn-primary">Register</button>
            </form>

            <p class="auth-helper">Already have an account? <a href="login.php">Login here</a></p>
        <?php endif; ?>
    </div>
</section>

<script>
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const fullname = document.getElementById('fullname').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    let errors = [];

    // 1. Full name validation (must have at least two words, first and last name)
    const nameWords = fullname.split(/\s+/);
    if (nameWords.length < 2) {
        errors.push("Please enter your full name (both first and last name).");
    }

    // 2. Username validation (must be at least 4 characters, alphanumeric only)
    const usernameRegex = /^[a-zA-Z0-9]{4,}$/;
    if (!usernameRegex.test(username)) {
        errors.push("Username must be at least 4 characters long and contain only letters and numbers.");
    }

    // 3. Password validation (must be at least 6 characters)
    if (password.length < 6) {
        errors.push("Password must be at least 6 characters long.");
    }

    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>

<?php require_once 'footer.php'; ?>
