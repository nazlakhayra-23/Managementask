<?php
// pages/edit_task.php - very simple & easy to explain
require_once __DIR__ . '/../includes/db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$taskId = (int)$_GET['id'];
$error = '';

// Get task details
$taskQuery = "SELECT * FROM tasks WHERE id = $taskId AND user_id = $userId";
$task = mysqli_fetch_assoc(mysqli_query($koneksi, $taskQuery));

// Make sure task exists and belongs to user
if (!$task) {
    header("Location: tasks.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskTitle = trim($_POST['title']);
    $taskDetails = trim($_POST['description']);
    $categoryId = empty($_POST['category_id']) ? 'NULL' : $_POST['category_id'];
    $priorityId = empty($_POST['priority_id']) ? 'NULL' : $_POST['priority_id'];
    $status = $_POST['status'];

    // Make sure title is not empty
    if (empty($taskTitle)) {
        $error = "Please enter a task title";
    } else {
        // Update task in database
        $sql = "UPDATE tasks 
                SET title = '$taskTitle',
                    description = '$taskDetails',
                    category_id = $categoryId,
                    priority_id = $priorityId,
                    status = '$status'
                WHERE id = $taskId AND user_id = $userId";

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['flash_msg'] = "Task updated successfully!";
            header("Location: tasks.php");
            exit;
        } else {
            $error = "Could not update task";
        }
    }
}

// Get categories and priorities for dropdowns
$categories = mysqli_query($koneksi, "SELECT id, name FROM categories WHERE user_id = $userId");
$priorities = mysqli_query($koneksi, "SELECT id, name FROM priorities WHERE user_id = $userId");

$pageTitle = "Edit Task";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Edit Task</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Task Title -->
            <div class="mb-3">
                <label>Task Title</label>
                <input type="text" name="title" class="form-control" 
                       value="<?= htmlspecialchars($task['title']) ?>" required>
            </div>

            <!-- Task Details -->
            <div class="mb-3">
                <label>Details (Optional)</label>
                <textarea name="description" class="form-control" rows="3"
                    ><?= htmlspecialchars($task['description']) ?></textarea>
            </div>

            <!-- Category Dropdown -->
            <div class="mb-3">
                <label>Category (Optional)</label>
                <select name="category_id" class="form-select">
                    <option value="">No Category</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= ($task['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Priority Dropdown -->
            <div class="mb-3">
                <label>Priority (Optional)</label>
                <select name="priority_id" class="form-select">
                    <option value="">No Priority</option>
                    <?php while ($pri = mysqli_fetch_assoc($priorities)): ?>
                        <option value="<?= $pri['id'] ?>"
                            <?= ($task['priority_id'] == $pri['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pri['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="pending" <?= $task['status']=='pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="done" <?= $task['status']=='done' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <!-- Form Buttons -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="tasks.php" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
