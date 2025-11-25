<?php
// Include database connection
require_once __DIR__ . '/../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /personal_task_system/pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$taskId = (int)($_GET['task_id'] ?? 0);

// Show success message if any
$message = $_SESSION['flash_msg'] ?? '';
unset($_SESSION['flash_msg']);

// Get task info
$taskQuery = "SELECT title FROM tasks WHERE id = $taskId AND user_id = $userId";
$task = mysqli_fetch_assoc(mysqli_query($koneksi, $taskQuery));

// Make sure task exists and belongs to user
if (!$task) {
    header("Location: tasks.php");
    exit;
}

// Handle adding new note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noteText = trim($_POST['note'] ?? '');
    
    if ($noteText) {
        // Add note to database
        $sql = "INSERT INTO personal_notes (task_id, note) VALUES ($taskId, '$noteText')";
        
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['flash_msg'] = "Note added successfully";
            header("Location: notes.php?task_id=" . $taskId);
            exit;
        }
    }
}

// Get all notes for this task
$notesQuery = "SELECT * FROM personal_notes WHERE task_id = $taskId ORDER BY created_at DESC";
$notes = mysqli_query($koneksi, $notesQuery);

$pageTitle = "Task Notes";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-body">
        <h4>Notes for: <?= htmlspecialchars($task['title']) ?></h4>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <!-- Add Note Form -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <textarea name="note" class="form-control" rows="3" 
                          placeholder="Type your note here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Add Note</button>
        </form>

        <!-- Notes List -->
        <div class="list-group">
            <?php if (mysqli_num_rows($notes) === 0): ?>
                <p class="text-muted">No notes yet. Add your first note above!</p>
            <?php endif; ?>

            <?php while ($note = mysqli_fetch_assoc($notes)): ?>
                <div class="list-group-item d-flex justify-content-between">
                    <div>
                        <p class="mb-1"><?= nl2br(htmlspecialchars($note['note'])) ?></p>
                        <small class="text-muted">Added: <?= $note['created_at'] ?></small>
                    </div>
                    <a href="../includes/actions/delete_note.php?id=<?= $note['id'] ?>&task_id=<?= $taskId ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete this note?')">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>

        <a href="tasks.php" class="btn btn-link mt-3">← Back to Tasks</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
