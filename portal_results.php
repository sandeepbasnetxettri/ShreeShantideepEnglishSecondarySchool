<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}
require_once 'config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $pdo->prepare("SELECT s.*, c.class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student profile not found.";
    exit;
}

$student_id = $student['id'];
$exam_term = $_GET['term'] ?? 'Final Term';

// Fetch Results
$stmt = $pdo->prepare("SELECT r.*, sub.subject_name FROM results r JOIN subjects sub ON r.subject_id = sub.id WHERE r.student_id = ? AND r.exam_term = ?");
$stmt->execute([$student_id, $exam_term]);
$results = $stmt->fetchAll();

// Fetch Attendance Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present FROM attendance WHERE student_id = ?");
$stmt->execute([$student_id]);
$attendance = $stmt->fetch();
$att_percent = ($attendance['total'] > 0) ? round(($attendance['present'] / $attendance['total']) * 100) : 0;

function getGrade($marks, $total) {
    if ($total <= 0) return '-';
    $p = ($marks / $total) * 100;
    if ($p >= 90) return 'A+';
    if ($p >= 80) return 'A';
    if ($p >= 70) return 'B+';
    if ($p >= 60) return 'B';
    if ($p >= 50) return 'C+';
    if ($p >= 40) return 'C';
    return 'D';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Results - Everest Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --primary-dark: #1e40af; --bg-color: #f8fafc; --card-bg: #ffffff; --text-main: #1e293b; --text-muted: #64748b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-color); color: var(--text-main); }
        .portal-nav { background: var(--primary-dark); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .portal-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white; }
        .user-menu { display: flex; align-items: center; gap: 1rem; }
        .user-menu img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid white; }
        .btn-logout { background: transparent; border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; transition: 0.2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.1); }

        .container { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.75rem; color: var(--text-main); display: flex; align-items: center; gap: 0.75rem;}
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-weight: 500; }
        .btn-back:hover { color: var(--primary); }

        .report-card { background: var(--card-bg); border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .rc-header { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 2rem; }
        .rc-header h2 { color: var(--primary-dark); font-size: 1.5rem; margin-bottom: 0.5rem;}
        .rc-header p { color: var(--text-muted); }
        
        .student-details { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; background: #f8fafc; padding: 1.5rem; border-radius: 8px; }
        .student-details div { font-size: 0.95rem; }
        .student-details span { font-weight: 600; color: var(--text-main); }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem;}
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.85rem;}
        .grade-a { color: #16a34a; font-weight: 600; }
        .grade-b { color: #2563eb; font-weight: 600; }
        
        .summary-box { display: flex; justify-content: flex-end; gap: 2rem; font-size: 1.1rem; }
        .summary-box span { font-weight: 600; color: var(--primary-dark); }
        
        .print-btn { display: block; width: 100%; text-align: center; padding: 1rem; background: var(--text-main); color: white; text-decoration: none; border-radius: 8px; margin-top: 2rem; font-weight: 500; transition: 0.2s;}
        .print-btn:hover { background: black; }
        
        @media print {
            body { background: white; }
            .portal-nav, .page-header, .print-btn { display: none; }
            .report-card { box-shadow: none; border: none; padding: 0; }
            .container { max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<nav class="portal-nav">
    <a href="portal_dashboard.php" class="portal-brand">
        <i class="fa-solid fa-graduation-cap fa-2x"></i>
        <h2>Everest Portal</h2>
    </a>
    <div class="user-menu">
        <a href="logout_portal.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Academic Results</h1>
        <a href="portal_dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="report-card" id="printableArea">
        <div class="rc-header">
            <h2><?php echo htmlspecialchars($student['name']); ?>'s Report Card</h2>
            <p><?php echo htmlspecialchars($exam_term); ?> Examination</p>
            <h3 style="margin-top: 1rem; font-size: 1.25rem;">Everest International School</h3>
        </div>
        
        <div class="student-details">
            <div>Student Name: <span><?php echo htmlspecialchars($student['name']); ?></span></div>
            <div>Class/Section: <span><?php echo htmlspecialchars($student['class_name']); ?></span></div>
            <div>Roll Number: <span><?php echo htmlspecialchars($student['roll_no']); ?></span></div>
            <div>Attendance: <span><?php echo $att_percent; ?>% (<?php echo $attendance['present']; ?>/<?php echo $attendance['total']; ?> Days)</span></div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Subjects</th>
                    <th>Full Marks</th>
                    <th>Pass Marks</th>
                    <th>Marks Obtained</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_obtained = 0;
                $total_full = 0;
                foreach($results as $r): 
                    $total_obtained += $r['marks_obtained'];
                    $total_full += $r['total_marks'];
                    $grade = getGrade($r['marks_obtained'], $r['total_marks']);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['subject_name']); ?></td>
                    <td><?php echo $r['total_marks']; ?></td>
                    <td><?php echo $r['total_marks'] * 0.4; ?></td>
                    <td><?php echo $r['marks_obtained']; ?></td>
                    <td class="grade-a"><?php echo $grade; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($results)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No results published for this term yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="summary-box">
            <?php if (!empty($results)): ?>
            <div>Total: <span><?php echo $total_obtained; ?> / <?php echo $total_full; ?></span></div>
            <div>Percentage: <span><?php echo round(($total_obtained / $total_full) * 100, 1); ?>%</span></div>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 3rem; display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
            <div style="text-align: center;">
                <div style="border-bottom: 1px solid #94a3b8; width: 150px; margin-bottom: 0.5rem;"></div>
                Class Teacher
            </div>
            <div style="text-align: center;">
                <div style="border-bottom: 1px solid #94a3b8; width: 150px; margin-bottom: 0.5rem; height: 15px;">Dr. Anita Thapa</div>
                Principal
            </div>
            <div style="text-align: center;">
                <div style="border-bottom: 1px solid #94a3b8; width: 150px; margin-bottom: 0.5rem;"></div>
                Guardian Signature
            </div>
        </div>
    </div>
    
    <button onclick="window.print()" class="print-btn"><i class="fa-solid fa-print" style="margin-right: 0.5rem;"></i> Print Report Card</button>
</div>

</body>
</html>
