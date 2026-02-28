<?php
session_start();
require_once '../config/db.php';

$active_page = 'students';
$page_title = 'Manage Students';
$message = '';

// Handle Add Student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_student') {
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];
    $class_id = $_POST['class_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();
        
        // Create user account
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
        $stmt->execute([$username, $password]);
        $user_id = $pdo->lastInsertId();
        
        // Create student profile
        $stmt = $pdo->prepare("INSERT INTO students (user_id, roll_no, name, class_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $roll_no, $name, $class_id]);
        
        $pdo->commit();
        $message = "Student added successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}

// Handle Delete Student
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT user_id FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $user_id = $stmt->fetchColumn();
    
    if ($user_id) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
        // Foreign key cascade or manual delete for students table if not cascade
        $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
    }
    header("Location: students_manage.php");
    exit;
}

// Fetch Classes for dropdown
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();

// Fetch Students
$stmt = $pdo->query("SELECT s.*, c.class_name FROM students s JOIN classes c ON s.class_id = c.id ORDER BY c.id, s.roll_no");
$students = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<style>
    .admin-card {
        border: none;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    .admin-card:hover {
        transform: translateY(-5px);
    }
    .search-box {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .search-box input {
        padding-left: 2.75rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .student-row {
        transition: background 0.2s;
    }
    .student-row:hover {
        background: #f1f5f9;
    }
</style>

<?php if ($message): ?>
    <div id="statusMessage" style="background: var(--primary); color: white; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.5s ease;">
        <i class="fa-solid fa-user-check"></i>
        <?php echo $message; ?>
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('statusMessage');
            if(msg) {
                msg.style.transition = 'all 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-20px)';
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
    <!-- Registration Form -->
    <div class="card admin-card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 45px; height: 45px; background: #eff6ff; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h3 style="margin: 0;">Register Student</h3>
        </div>

        <form method="POST" id="studentForm">
            <input type="hidden" name="action" value="add_student">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Roll Number</label>
                    <input type="text" name="roll_no" class="form-control" placeholder="e.g. 101" required>
                </div>
                <div class="form-group">
                    <label>Class</label>
                    <select name="class_id" class="form-control" required>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Portal Username</label>
                <input type="text" name="username" class="form-control" placeholder="portal_login" required>
            </div>
            <div class="form-group">
                <label>Portal Password</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600;">
                <i class="fa-solid fa-id-card" style="margin-right: 0.5rem;"></i> Register Student
            </button>
        </form>
    </div>

    <!-- Student List -->
    <div class="card admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: #fef2f2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
                <h3 style="margin: 0;">Student Records</h3>
            </div>
            <div style="color: #64748b; font-size: 0.9rem;">
                Total: <span id="studentCount"><?php echo count($students); ?></span>
            </div>
        </div>

        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="studentSearch" class="form-control" placeholder="Search by name, roll no, or class...">
        </div>

        <div style="overflow-x: auto;">
            <table class="table" id="studentTable">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr class="student-row">
                        <td style="font-weight: 700; color: var(--primary);"><?php echo htmlspecialchars($s['roll_no']); ?></td>
                        <td>
                            <div style="font-weight: 600; color: var(--text);"><?php echo htmlspecialchars($s['name']); ?></div>
                            <small style="color: #94a3b8; font-size: 0.75rem;">ID: #<?php echo $s['id']; ?></small>
                        </td>
                        <td>
                            <span class="badge badge-student"><?php echo htmlspecialchars($s['class_name']); ?></span>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem;" onclick="return confirm('Permanently remove this student and their login credentials?');">
                                <i class="fa-solid fa-user-minus"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($students)): ?>
                        <tr><td colspan="4" style="text-align: center; padding: 3rem; color: #94a3b8;">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('studentSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#studentTable tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(term)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        document.getElementById('studentCount').textContent = visibleCount;
    });

    document.getElementById('studentForm').onsubmit = function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
        btn.disabled = true;
    };
</script>

<?php require_once 'includes/footer.php'; ?>
