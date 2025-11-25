<?php
// Include database connection
require_once __DIR__ . '/../includes/db.php';

// If already logged in, go to homepage
if (isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/index.php");
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Check username and plain password
        $sql = "SELECT id, password FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($koneksi, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['flash_msg'] = "Welcome back, $username!";
            header("Location: /personal_task_system/index.php");
            exit;
        } else {
            $error = "Wrong username or password";
        }
    } else {
        $error = "Please fill in all fields";
    }
}

$pageTitle = "Login";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Login</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Username field -->
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <!-- Password field -->
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <!-- Form buttons -->
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="register.php" class="btn btn-link">Create Account</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
