<?php
// Include database connection
require_once __DIR__ . '/../includes/db.php';

// If already logged in, go to homepage
if (isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/index.php");
    exit;
}

$error = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Check if username is already taken
        $checkUser = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'");

        if (mysqli_num_rows($checkUser) > 0) {
            $error = "Username already exists";
        } else {
            // Create new user with plain password
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if (mysqli_query($koneksi, $sql)) {
                // Auto login after registration
                $_SESSION['user_id'] = mysqli_insert_id($koneksi);
                $_SESSION['username'] = $username;
                header("Location: /personal_task_system/index.php");
                exit;
            } else {
                $error = "Could not create account";
            }
        }
    } else {
        $error = "Please fill in all fields";
    }
}

$pageTitle = "Register";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Create Account</h4>

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
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="login.php" class="btn btn-link">Back to Login</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
