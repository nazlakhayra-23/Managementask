<?php
/*
Simple Priority Manager
---------------------
What this page does:
1. Shows all your task priorities (like High, Medium, Low)
2. Lets you add new priorities
3. Lets you edit priority names
4. Lets you delete priorities you don't need

Think of it like creating labels for how important tasks are!
*/

// Connect to database
require_once __DIR__ . '/../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$message = $_SESSION['flash_msg'] ?? '';
unset($_SESSION['flash_msg']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $priorityName = trim($_POST['name'] ?? '');
    
    if ($action === 'add' && $priorityName) {
        // Add new priority
        mysqli_query($koneksi, "INSERT INTO priorities (user_id, name) VALUES ($userId, '$priorityName')");
        $_SESSION['flash_msg'] = "Priority added!";
        header("Location: manage_priorities.php");
        exit;
    }
    
    if ($action === 'edit' && $priorityName) {
        // Edit existing priority
        $priorityId = (int)$_POST['id'];
        mysqli_query($koneksi, "UPDATE priorities SET name='$priorityName' WHERE id=$priorityId AND user_id=$userId");
        $_SESSION['flash_msg'] = "Priority updated!";
        header("Location: manage_priorities.php");
        exit;
    }
}

// Get list of priorities
$priorities = mysqli_query($koneksi, "SELECT id, name FROM priorities WHERE user_id = $userId ORDER BY id");

$pageTitle = "Manage Priorities";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Manage Priorities</h4>
        
        <!-- Show messages -->
        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <!-- Add new priority -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add">
            <div class="input-group">
                <input type="text" name="name" class="form-control" 
                       placeholder="Enter priority (like High, Medium, Low)" required>
                <button class="btn btn-primary">Add Priority</button>
            </div>
        </form>

        <!-- List all priorities -->
        <div class="list-group">
            <?php if (mysqli_num_rows($priorities) === 0): ?>
                <p class="text-muted">No priorities yet. Add one above!</p>
            <?php endif; ?>

            <?php while ($priority = mysqli_fetch_assoc($priorities)): ?>
                <div class="list-group-item">
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $priority['id'] ?>">
                        <input type="text" name="name" 
                               value="<?= htmlspecialchars($priority['name']) ?>" 
                               class="form-control">
                        <button class="btn btn-sm btn-outline-primary">Save</button>
                        <a href="/personal_task_system/includes/actions/delete_priority.php?id=<?= $priority['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this priority?')">Delete</a>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        
        <a href="tasks.php" class="btn btn-link mt-3">Back to Tasks</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
