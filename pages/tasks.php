<?php
require_once __DIR__ . '/../includes/db.php';

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// get tasks with category and priority
$sql = "SELECT t.*, 
               c.name as category_name, 
               p.name as priority_name
        FROM tasks t
        LEFT JOIN categories c ON t.category_id = c.id
        LEFT JOIN priorities p ON t.priority_id = p.id
        WHERE t.user_id = $user_id
        ORDER BY t.created_at DESC";
$result = mysqli_query($koneksi, $sql);

$pageTitle = "Task List";
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Flash Message -->
<?php if (!empty($_SESSION['flash_msg'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['flash_msg']) ?>
    </div>
    <?php unset($_SESSION['flash_msg']); ?>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Task List</h1>
    <a href="add_task.php" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Task
    </a>
</div>

<!-- Task Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$result || mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No tasks found
                        </td>
                    </tr>
                <?php else: while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category_name'] ?? 'None') ?></td>
                        <td><?= htmlspecialchars($row['priority_name'] ?? 'None') ?></td>
                        <td>
                            <span class="badge bg-<?= $row['status'] === 'done' ? 'success' : 'secondary' ?>">
                                <?= $row['status'] === 'done' ? 'Completed' : 'Pending' ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_task.php?id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="notes.php?task_id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-journal-text"></i> Notes
                            </a>
                            <a href="../includes/actions/delete_task.php?id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this task?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
