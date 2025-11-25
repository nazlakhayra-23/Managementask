<?php
/*
Simple Category Management System
This page lets you:
1. View all your categories
2. Add new categories
3. Edit existing categories
4. Delete categories
*/

require_once __DIR__ . '/../includes/db.php';

// Make sure user is logged in
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
    $categoryName = trim($_POST['name'] ?? '');
    
    if ($action === 'add' && $categoryName) {
        // Add new category
        mysqli_query($koneksi, "INSERT INTO categories (user_id, name) VALUES ($userId, '$categoryName')");
        $_SESSION['flash_msg'] = "Category added!";
        header("Location: manage_categories.php");
        exit;
    }
    
    if ($action === 'edit' && $categoryName) {
        // Edit existing category
        $categoryId = (int)$_POST['id'];
        mysqli_query($koneksi, "UPDATE categories SET name='$categoryName' WHERE id=$categoryId AND user_id=$userId");
        $_SESSION['flash_msg'] = "Category updated!";
        header("Location: manage_categories.php");
        exit;
    }
}

// Get list of categories
$categories = mysqli_query($koneksi, "SELECT id, name FROM categories WHERE user_id = $userId ORDER BY name");

$pageTitle = "Manage Categories";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Manage Categories</h4>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <!-- Add Category Form -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add">
            <div class="input-group">
                <input type="text" name="name" class="form-control" 
                       placeholder="Enter category name" required>
                <button class="btn btn-primary">Add Category</button>
            </div>
        </form>

        <!-- Categories List -->
        <div class="list-group">
            <?php if (mysqli_num_rows($categories) === 0): ?>
                <p class="text-muted">No categories yet. Add one above!</p>
            <?php endif; ?>

            <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                <div class="list-group-item">
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        <input type="text" name="name" 
                               value="<?= htmlspecialchars($category['name']) ?>" 
                               class="form-control">
                        <button class="btn btn-sm btn-outline-primary">Save</button>
                        <a href="/personal_task_system/includes/actions/delete_category.php?id=<?= $category['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this category?')">Delete</a>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        
        <a href="tasks.php" class="btn btn-link mt-3">Back to Tasks</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
