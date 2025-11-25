<?php
/*
Add Task Page
------------
This page does 3 things:
1. Shows a form to add a new task
2. Lets you pick category and priority
3. Saves the task to database
*/

// Include database connection
require_once __DIR__ . '/../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskTitle = trim($_POST['title']);
    $taskDetails = trim($_POST['description']);
    $categoryId = empty($_POST['category_id']) ? 'NULL' : $_POST['category_id'];
    $priorityId = empty($_POST['priority_id']) ? 'NULL' : $_POST['priority_id'];
    
    // Make sure title is not empty
    if (empty($taskTitle)) {
        $error = "Please enter a task title";
    } else {
        // Add task to database
        $sql = "INSERT INTO tasks (user_id, category_id, priority_id, title, description) 
                VALUES ($userId, $categoryId, $priorityId, '$taskTitle', '$taskDetails')";
        
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['flash_msg'] = "Task added successfully!";
            header("Location: tasks.php");
            exit;
        } else {
            $error = "Could not add task";
        }
    }
}

// Get categories and priorities for dropdowns
$categories = mysqli_query($koneksi, "SELECT id, name FROM categories WHERE user_id = $userId");
$priorities = mysqli_query($koneksi, "SELECT id, name FROM priorities WHERE user_id = $userId");

$pageTitle = "Add New Task";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Add New Task</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Task Title -->
            <div class="mb-3">
                <label>Task Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <!-- Task Details -->
            <div class="mb-3">
                <label>Details (Optional)</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <!-- Category Dropdown -->
            <div class="mb-3">
                <label>Category (Optional)</label>
                <select name="category_id" class="form-select">
                    <option value="">No Category</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- Priority Dropdown -->
            <div class="mb-3">
                <label>Priority (Optional)</label>
                <select name="priority_id" class="form-select">
                    <option value="">No Priority</option>
                    <?php while ($pri = mysqli_fetch_assoc($priorities)): ?>
                        <option value="<?= $pri['id'] ?>"><?= htmlspecialchars($pri['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- Form Buttons -->
            <button type="submit" class="btn btn-primary">Add Task</button>
            <a href="tasks.php" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
