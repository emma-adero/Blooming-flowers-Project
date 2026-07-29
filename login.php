<?php
$page_title = "Login - BLOMMING FLOWERS";
require_once 'db.php';
require_once 'header.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, username, password, email, fullname, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $db_username, $db_password, $email, $fullname, $role);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                // Password is correct, start session
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                $_SESSION['email'] = $email;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="auth-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="auth-helper">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</section>

<?php require_once 'footer.php'; ?>
