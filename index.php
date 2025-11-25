<?php
// Include database connection
require_once __DIR__ . '/includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get basic stats
$statsQuery = "SELECT COUNT(*) AS total FROM tasks WHERE user_id = $userId";
$totalTasks = mysqli_fetch_assoc(mysqli_query($koneksi, $statsQuery))['total'] ?? 0;

// Get recent tasks
$recentQuery = "SELECT title, status, created_at 
                FROM tasks 
                WHERE user_id = $userId 
                ORDER BY created_at DESC 
                LIMIT 5";
$recentTasks = mysqli_query($koneksi, $recentQuery);

$pageTitle = "Dashboard";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($username) ?></h2>
    <p>Task Management System</p>

    <!-- Task Count -->
    <div class="card mb-4">
        <div class="card-body text-center">
            <h4>Total Tasks</h4>
            <h1><?= $totalTasks ?></h1>
            <a href="pages/tasks.php" class="btn btn-primary">View All Tasks</a>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="card">
        <div class="card-body">
            <h5>Recent Tasks</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($recentTasks) === 0): ?>
                        <tr><td colspan="3" class="text-muted">No tasks found</td></tr>
                    <?php else: while($task = mysqli_fetch_assoc($recentTasks)): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td>
                                <span class="badge bg-<?= $task['status'] === 'done' ? 'success' : 'secondary' ?>">
                                    <?= $task['status'] === 'done' ? 'Completed' : 'Pending' ?>
                                </span>
                            </td>
                            <td><?= $task['created_at'] ?></td>
                        </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
