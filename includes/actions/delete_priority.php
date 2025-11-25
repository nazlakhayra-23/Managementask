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
$priority_id = (int)$_GET['id'];

// Delete the priority
mysqli_query($koneksi, "DELETE FROM priorities WHERE id = $priority_id AND user_id = $user_id");

// Show success message and go back
$_SESSION['flash_msg'] = "Priority deleted";
header("Location: /personal_task_system/pages/manage_priorities.php");
exit;
