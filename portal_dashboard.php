<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
$student_id = $_SESSION['user_id']; // In a real app, query the 'students' table with this user_id

$user_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $pdo->prepare("SELECT s.*, c.class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student profile not found.";
    exit;
}

$student_name = $student['name'];
$student_class = $student['class_name'];
$roll_no = $student['roll_no'];
$student_id_real = $student['id'];

// Fetch Attendance Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present FROM attendance WHERE student_id = ?");
$stmt->execute([$student_id_real]);
$attendance = $stmt->fetch();
$attendance_percentage = ($attendance['total'] > 0) ? round(($attendance['present'] / $attendance['total']) * 100) : 0;

// Fetch Pending Assignments
$stmt = $pdo->prepare("SELECT COUNT(*) FROM assignments WHERE class_id = ? AND due_date >= CURDATE()");
$stmt->execute([$student['class_id']]);
$pending_assignments = $stmt->fetchColumn();

// Fetch Latest Term Result (dummy grade for now if no results)
$stmt = $pdo->prepare("SELECT marks_obtained, total_marks FROM results WHERE student_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$student_id_real]);
$last_result = $stmt->fetch();
$last_grade = $last_result ? 'Calculated' : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Everest School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #10b981;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-color); color: var(--text-main); }
        
        /* Navbar */
        .portal-nav {
            background: var(--primary-dark);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .portal-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white; }
        .user-menu { display: flex; align-items: center; gap: 1rem; }
        .user-menu img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid white; }
        .btn-logout { background: transparent; border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; transition: 0.2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.1); }

        .container { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }
        
        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: white;
            padding: 2.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .student-info h1 { font-size: 2rem; margin-bottom: 0.5rem; }
        .student-info p { opacity: 0.9; font-size: 1.1rem; }
        
        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .card-header h3 { color: var(--text-main); font-size: 1.25rem; }
        .card-header i { color: var(--primary); font-size: 1.25rem; }
        
        /* Quick Stats items */
        .stat-row { display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px dashed #e2e8f0; }
        .stat-label { color: var(--text-muted); }
        .stat-value { font-weight: 600; color: var(--text-main); }
        
        /* Action buttons */
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.5rem;
            background: var(--bg-color);
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            transition: 0.2s;
            border: 1px solid #e2e8f0;
        }
        .action-btn:hover { background: white; border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); color: var(--primary); }
        .action-btn i { font-size: 1.5rem; color: var(--primary); }
    </style>
</head>
<body>

<nav class="portal-nav">
    <a href="index.php" class="portal-brand">
        <i class="fa-solid fa-graduation-cap fa-2x"></i>
        <h2>Everest Portal</h2>
    </a>
    <div class="user-menu">
        <div style="text-align: right; display: none; @media(min-width: 600px){display: block;}">
            <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($student_name); ?></div>
            <div style="font-size: 0.8rem; opacity: 0.8;">Student | <?php echo htmlspecialchars($student_class); ?></div>
        </div>
        <img src="https://ui-avatars.com/api/?name=Aarav+Sharma&background=0D8ABC&color=fff" alt="User">
        <a href="logout_portal.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</nav>

<div class="container">
    <div class="welcome-banner">
        <div class="student-info">
            <h1>Welcome back, <?php echo htmlspecialchars(explode(' ', $student_name)[0]); ?>!</h1>
            <p><i class="fa-solid fa-id-card" style="margin-right: 0.5rem;"></i> Roll No: <?php echo htmlspecialchars($roll_no); ?> | Class: <?php echo htmlspecialchars($student_class); ?></p>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 1rem 2rem; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: 700;"><?php echo $attendance_percentage; ?>%</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Total Attendance</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Academic Summary -->
        <div class="card">
            <div class="card-header">
                <h3>Academic Progress</h3>
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="stat-row">
                <span class="stat-label">Last Exam (First Term)</span>
                <span class="stat-value" style="color: var(--secondary);">Grade: A</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Pending Assignments</span>
                <span class="stat-value" style="color: #ef4444;"><?php echo $pending_assignments; ?> Due</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Current Rank</span>
                <span class="stat-value">5th</span>
            </div>
            <a href="portal_results.php" class="action-btn" style="width: 100%; margin-top: 1rem; padding: 0.75rem; flex-direction: row; justify-content: center;">
                View Detailed Report Card
            </a>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h3>Student Services</h3>
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="action-grid">
                <a href="portal_assignments.php" class="action-btn">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Assignments</span>
                </a>
                <a href="portal_timetable.php" class="action-btn">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Timetable</span>
                </a>
                <a href="#" class="action-btn">
                    <i class="fa-solid fa-book"></i>
                    <span>E-Library</span>
                </a>
                <a href="#" class="action-btn">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Fee Status</span>
                </a>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <div class="card-header">
                <h3>Latest Notices</h3>
                <i class="fa-regular fa-bell"></i>
            </div>
            <?php
            $stmt = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 2");
            $notices = $stmt->fetchAll();
            foreach ($notices as $n):
            ?>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: #e0f2fe; color: #0369a1; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fa-solid fa-bullhorn"></i></div>
                <div>
                    <h4 style="font-size: 0.95rem; color: var(--text-main);"><?php echo htmlspecialchars($n['title']); ?></h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted);"><?php echo substr(htmlspecialchars($n['content']), 0, 60) . '...'; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($notices)): ?>
                <p style="color: var(--text-muted); text-align: center;">No recent notices.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
