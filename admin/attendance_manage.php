<?php
session_start();
require_once '../config/db.php';

$active_page = 'attendance';
$page_title = 'Attendance Management';
$message = '';

$class_id = $_GET['class_id'] ?? null;
$date = $_GET['date'] ?? date('Y-m-d');

// Handle Saving Attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save_attendance') {
    $statuses = $_POST['status'] ?? [];
    $pdo->beginTransaction();
    try {
        foreach ($statuses as $student_id => $status) {
            $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
            $stmt->execute([$student_id, $date, $status, $status]);
        }
        $pdo->commit();
        $message = "Attendance saved successfully for " . $date;
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch Classes
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();

// Fetch Students for selected class
$students = [];
if ($class_id) {
    $stmt = $pdo->prepare("SELECT s.*, a.status as current_status FROM students s LEFT JOIN attendance a ON s.id = a.student_id AND a.date = ? WHERE s.class_id = ?");
    $stmt->execute([$date, $class_id]);
    $students = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

<div class="card">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Select Class</label>
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Choose Class --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ($class_id == $c['id']) ? 'selected' : ''; ?>><?php echo $c['class_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>" onchange="this.form.submit()">
        </div>
    </form>
</div>

<?php if ($message): ?>
    <div class="card" style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($class_id): ?>
<div class="card">
    <form method="POST">
        <input type="hidden" name="action" value="save_attendance">
        <table class="table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td>
                        <select name="status[<?php echo $s['id']; ?>]" class="form-control" style="width: auto;">
                            <option value="present" <?php echo ($s['current_status'] == 'present') ? 'selected' : ''; ?>>Present</option>
                            <option value="absent" <?php echo ($s['current_status'] == 'absent') ? 'selected' : ''; ?>>Absent</option>
                            <option value="late" <?php echo ($s['current_status'] == 'late') ? 'selected' : ''; ?>>Late</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit" class="btn btn-primary">Save Attendance</button>
    </form>
</div>
<?php else: ?>
    <div class="card" style="text-align: center; color: #64748b;">
        Please select a class to mark attendance.
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
