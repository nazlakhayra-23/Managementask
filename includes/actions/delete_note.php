<?php
// Get database connection
require_once __DIR__ . '/../db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

// Get the IDs we need
$user_id = $_SESSION['user_id'];
$note_id = (int)$_GET['id'];
$task_id = (int)$_GET['task_id'];

// Delete the note (only if it belongs to user's task)
mysqli_query($koneksi, "DELETE n FROM personal_notes n 
                       JOIN tasks t ON n.task_id = t.id 
                       WHERE n.id = $note_id AND t.user_id = $user_id");

// Show success message and go back
$_SESSION['flash_msg'] = "Note deleted";
header("Location: /personal_task_system/pages/notes.php?task_id=" . $task_id);
exit;
