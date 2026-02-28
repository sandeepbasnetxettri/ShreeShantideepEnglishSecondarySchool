<?php
session_start();
require_once '../config/db.php';

$active_page = 'assignments';
$page_title = 'Manage Assignments';
$message = '';

// Handle Add Assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_assignment') {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    
    $file_url = '';
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
        $target_dir = "../uploads/assignments/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . '_' . basename($_FILES["assignment_file"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["assignment_file"]["tmp_id"], $target_file)) {
            $file_url = "uploads/assignments/" . $file_name;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO assignments (class_id, subject_id, title, description, file_url, due_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$class_id, $subject_id, $title, $description, $file_url, $due_date]);
    $message = "Assignment posted successfully!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM assignments WHERE id = ?")->execute([$id]);
    header("Location: assignments_manage.php");
    exit;
}

// Fetch Classes and Subjects
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();
$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();

// Fetch Assignments
$stmt = $pdo->query("SELECT a.*, c.class_name, s.subject_name FROM assignments a JOIN classes c ON a.class_id = c.id JOIN subjects s ON a.subject_id = s.id ORDER BY a.created_at DESC");
$assignments = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="card">
    <h3>Post New Assignment</h3><br>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_assignment">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Class</label>
                <select name="class_id" class="form-control" required>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['class_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?php echo $s['id']; ?>"><?php echo $s['subject_name']; ?> (<?php echo $s['class_id']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Assignment Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Description / Instructions</label>
                <textarea name="description" rows="3" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Attachment (Optional)</label>
                <input type="file" name="assignment_file" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Post Assignment</button>
    </form>
</div>

<div class="card">
    <h3>Current Assignments</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Due Date</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $a): ?>
            <tr>
                <td><?php echo date('M d', strtotime($a['due_date'])); ?></td>
                <td><?php echo htmlspecialchars($a['class_name']); ?></td>
                <td><?php echo htmlspecialchars($a['subject_name']); ?></td>
                <td><?php echo htmlspecialchars($a['title']); ?></td>
                <td>
                    <a href="?delete=<?php echo $a['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this assignment?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
