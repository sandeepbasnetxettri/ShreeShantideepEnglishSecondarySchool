<?php
session_start();
require_once '../config/db.php';

$active_page = 'results';
$page_title = 'Exam Results Management';
$message = '';

$class_id = $_GET['class_id'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;
$exam_term = $_GET['exam_term'] ?? 'Final Term';

// Handle Add Subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_subject') {
    $subject_name = $_POST['subject_name'];
    $stmt = $pdo->prepare("INSERT INTO subjects (class_id, subject_name) VALUES (?, ?)");
    $stmt->execute([$class_id, $subject_name]);
    $message = "Subject added successfully!";
}

// Handle Save Results
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save_results') {
    $marks = $_POST['marks'] ?? [];
    $total_marks = $_POST['total_marks'] ?? 100;
    
    $pdo->beginTransaction();
    try {
        foreach ($marks as $student_id => $mark) {
            $stmt = $pdo->prepare("INSERT INTO results (student_id, subject_id, exam_term, marks_obtained, total_marks) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE marks_obtained = ?");
            $stmt->execute([$student_id, $subject_id, $exam_term, $mark, $total_marks, $mark]);
        }
        $pdo->commit();
        $message = "Marks saved successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch Classes
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();

// Fetch Subjects for selected class
$subjects = [];
if ($class_id) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
    $stmt->execute([$class_id]);
    $subjects = $stmt->fetchAll();
}

// Fetch Students and existing marks
$students = [];
if ($class_id && $subject_id) {
    $stmt = $pdo->prepare("SELECT s.*, r.marks_obtained FROM students s LEFT JOIN results r ON s.id = r.student_id AND r.subject_id = ? AND r.exam_term = ? WHERE s.class_id = ?");
    $stmt->execute([$subject_id, $exam_term, $class_id]);
    $students = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

<div class="card">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Class</label>
            <select name="class_id" class="form-control" onchange="this.form.subject_id.value=''; this.form.submit()">
                <option value="">-- Select Class --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ($class_id == $c['id']) ? 'selected' : ''; ?>><?php echo $c['class_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if ($class_id): ?>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Subject</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Select Subject --</option>
                <?php foreach ($subjects as $sub): ?>
                    <option value="<?php echo $sub['id']; ?>" <?php echo ($subject_id == $sub['id']) ? 'selected' : ''; ?>><?php echo $sub['subject_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label>Exam Term</label>
            <select name="exam_term" class="form-control" onchange="this.form.submit()">
                <option value="First Term" <?php echo ($exam_term == 'First Term') ? 'selected' : ''; ?>>First Term</option>
                <option value="Second Term" <?php echo ($exam_term == 'Second Term') ? 'selected' : ''; ?>>Second Term</option>
                <option value="Final Term" <?php echo ($exam_term == 'Final Term') ? 'selected' : ''; ?>>Final Term</option>
            </select>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php if ($message): ?>
    <div class="card" style="background: #e0f2fe; color: #0369a1; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($class_id && !$subject_id): ?>
<div class="card">
    <h3>Add New Subject for this Class</h3><br>
    <form method="POST">
        <input type="hidden" name="action" value="add_subject">
        <div class="form-group">
            <input type="text" name="subject_name" class="form-control" placeholder="Subject Name (e.g., Mathematics)" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Subject</button>
    </form>
</div>
<?php endif; ?>

<?php if ($class_id && $subject_id): ?>
<div class="card">
    <form method="POST">
        <input type="hidden" name="action" value="save_results">
        <div class="form-group">
            <label>Total Marks for this Subject</label>
            <input type="number" name="total_marks" class="form-control" value="100" style="width: 100px;">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Marks Obtained</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td>
                        <input type="number" step="0.5" name="marks[<?php echo $s['id']; ?>]" class="form-control" value="<?php echo $s['marks_obtained']; ?>" placeholder="0.0">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit" class="btn btn-primary">Save Results</button>
    </form>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
