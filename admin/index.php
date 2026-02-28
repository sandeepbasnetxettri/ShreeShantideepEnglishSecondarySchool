<?php
session_start();
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';

// Get counts for dashboard
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$student_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM teachers");
$teacher_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM notices");
$notice_count = $stmt->fetchColumn();
$active_page = 'dashboard';
$page_title = 'Dashboard Overview';
require_once 'includes/header.php';
?>

<style>
    .stat-card {
        border: none;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255,255,255,0.5);
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .grid-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .action-btn {
        flex: 1;
        min-width: 200px;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid #e2e8f0;
        background: white;
        color: var(--text);
    }
    .action-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: scale(1.05);
    }
    .action-btn i { font-size: 1.75rem; }
</style>

<div class="grid-cards">
    <div class="card stat-card">
        <div>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Student Body</p>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-top: 0.5rem;"><?php echo $student_count; ?></h3>
        </div>
        <div class="card-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fa-solid fa-user-graduate"></i></div>
    </div>
    
    <div class="card stat-card">
        <div>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Faculty Crew</p>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-top: 0.5rem;"><?php echo $teacher_count; ?></h3>
        </div>
        <div class="card-icon" style="background: #f0fdf4; color: #22c55e;"><i class="fa-solid fa-chalkboard-teacher"></i></div>
    </div>
    
    <div class="card stat-card">
        <div>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Broadcasted</p>
            <h3 style="font-size: 2.25rem; font-weight: 800; color: #1e293b; margin-top: 0.5rem;"><?php echo $notice_count; ?></h3>
        </div>
        <div class="card-icon" style="background: #f6f3ff; color: #a855f7;"><i class="fa-solid fa-bullhorn rotate-12"></i></div>
    </div>
</div>

<!-- Refined Quick Actions -->
<div class="card admin-card" style="padding: 2rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
        <i class="fa-solid fa-bolt-lightning" style="color: #f59e0b;"></i>
        <h3 style="margin: 0; font-weight: 700;">Instant Management Hub</h3>
    </div>
    
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        <a href="notices_manage.php" class="action-btn">
            <i class="fa-solid fa-paper-plane" style="color: #a855f7;"></i>
            <span>Compose Notice</span>
        </a>
        <a href="gallery_manage.php" class="action-btn">
            <i class="fa-solid fa-photo-film" style="color: #10b981;"></i>
            <span>Gallery Upload</span>
        </a>
        <a href="students_manage.php" class="action-btn">
            <i class="fa-solid fa-user-plus" style="color: #3b82f6;"></i>
            <span>Add Student</span>
        </a>
        <a href="attendance_manage.php" class="action-btn">
            <i class="fa-solid fa-calendar-check" style="color: #ef4444;"></i>
            <span>Attendance</span>
        </a>
        <a href="../index.php" target="_blank" class="action-btn" style="background: #1e293b; color: white; border: none;">
            <i class="fa-solid fa-earth-asia"></i>
            <span>Live Experience</span>
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
