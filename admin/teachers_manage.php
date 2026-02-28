<?php
session_start();
require_once '../config/db.php';

$active_page = 'teachers';
$page_title = 'Manage Teachers';
$message = '';

// Handle Add Teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_teacher') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();
        
        // Create user account
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'teacher')");
        $stmt->execute([$username, $password]);
        $user_id = $pdo->lastInsertId();
        
        // Create teacher profile
        $stmt = $pdo->prepare("INSERT INTO teachers (user_id, name, email, phone, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $phone, $department]);
        
        $pdo->commit();
        $message = "Teacher added successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}

// Handle Delete Teacher
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT user_id FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    $user_id = $stmt->fetchColumn();
    
    if ($user_id) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
        $pdo->prepare("DELETE FROM teachers WHERE id = ?")->execute([$id]);
    }
    header("Location: teachers_manage.php");
    exit;
}

// Fetch Teachers
$stmt = $pdo->query("SELECT * FROM teachers ORDER BY name");
$teachers = $stmt->fetchAll();

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
    .teacher-row {
        transition: background 0.2s;
    }
    .teacher-row:hover {
        background: #f1f5f9;
    }
</style>

<?php if ($message): ?>
    <div id="statusMessage" style="background: #0ea5e9; color: white; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.5s ease;">
        <i class="fa-solid fa-chalkboard-user"></i>
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
            <div style="width: 45px; height: 45px; background: #f0fdf4; color: #22c55e; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <h3 style="margin: 0;">Register Teacher</h3>
        </div>

        <form method="POST" id="teacherForm">
            <input type="hidden" name="action" value="add_teacher">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Prof. Alan Turing" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@school.com" required>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" class="form-control" placeholder="e.g. Mathematics">
                </div>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="+977-XXXXXXXXXX">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Portal Username</label>
                    <input type="text" name="username" class="form-control" placeholder="teacher_login" required>
                </div>
                <div class="form-group">
                    <label>Portal Password</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600; background: #22c55e; border: none;">
                <i class="fa-solid fa-shield-halved" style="margin-right: 0.5rem;"></i> Create Teacher Account
            </button>
        </form>
    </div>

    <!-- Teacher List -->
    <div class="card admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: #fff7ed; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <h3 style="margin: 0;">Faculty Registry</h3>
            </div>
            <div style="color: #64748b; font-size: 0.9rem;">
                Total: <span id="teacherCount"><?php echo count($teachers); ?></span>
            </div>
        </div>

        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="teacherSearch" class="form-control" placeholder="Search by name, department, or email...">
        </div>

        <div style="overflow-x: auto;">
            <table class="table" id="teacherTable">
                <thead>
                    <tr>
                        <th>Faculty Member</th>
                        <th>Department</th>
                        <th>Contact info</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $t): ?>
                    <tr class="teacher-row">
                        <td>
                            <div style="font-weight: 600; color: var(--text);"><?php echo htmlspecialchars($t['name']); ?></div>
                            <small style="color: #94a3b8; font-size: 0.75rem;">Staff ID: #T<?php echo $t['id']; ?></small>
                        </td>
                        <td>
                            <span class="badge badge-teacher" style="background: #f0fdf4; color: #16a34a;"><?php echo htmlspecialchars($t['department'] ?: 'General'); ?></span>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; color: #64748b;"><i class="fa-solid fa-envelope" style="width: 20px;"></i> <?php echo htmlspecialchars($t['email']); ?></div>
                            <div style="font-size: 0.85rem; color: #64748b;"><i class="fa-solid fa-phone" style="width: 20px;"></i> <?php echo htmlspecialchars($t['phone'] ?: 'No phone'); ?></div>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $t['id']; ?>" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem;" onclick="return confirm('Archive this teacher record and disable portal access?');">
                                <i class="fa-solid fa-user-slash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($teachers)): ?>
                        <tr><td colspan="4" style="text-align: center; padding: 3rem; color: #94a3b8;">No faculty members registered.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('teacherSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#teacherTable tbody tr');
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
        document.getElementById('teacherCount').textContent = visibleCount;
    });

    document.getElementById('teacherForm').onsubmit = function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Registering...';
        btn.disabled = true;
    };
</script>

<?php require_once 'includes/footer.php'; ?>
