<?php
require_once __DIR__ . '/../db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? 0;

// Delete related notes
mysqli_query($koneksi, "DELETE FROM personal_notes WHERE task_id = $task_id");

// Delete task
$result = mysqli_query($koneksi, "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id");

// Set message and redirect
$_SESSION['flash_msg'] = $result ? "Task deleted successfully" : "Could not delete task";
header("Location: /personal_task_system/pages/tasks.php");
exit;
