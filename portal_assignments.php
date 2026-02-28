<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}
require_once 'config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch student info
$stmt = $pdo->prepare("SELECT s.*, c.id as class_id, c.class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student profile not found.";
    exit;
}

$class_id = $student['class_id'];

// Fetch Assignments for this class
$stmt = $pdo->prepare("SELECT a.*, s.subject_name FROM assignments a JOIN subjects s ON a.subject_id = s.id WHERE a.class_id = ? ORDER BY a.due_date ASC");
$stmt->execute([$class_id]);
$assignments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework & Assignments - Everest Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --primary-dark: #1e40af; --bg-color: #f8fafc; --card-bg: #ffffff; --text-main: #1e293b; --text-muted: #64748b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-color); color: var(--text-main); }
        .portal-nav { background: var(--primary-dark); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .portal-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white; }
        
        .container { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.75rem; display: flex; align-items: center; gap: 0.75rem;}
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-weight: 500; }
        
        .assignment-card { background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; transition: 0.3s; }
        .assignment-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .as-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .as-tag { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .tag-date { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; }
        .tag-subject { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
        .file-link { margin-top: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary); font-weight: 600; text-decoration: none; }
        .file-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<nav class="portal-nav">
    <a href="portal_dashboard.php" class="portal-brand">
        <i class="fa-solid fa-graduation-cap fa-2x"></i>
        <h2>Everest Portal</h2>
    </a>
</nav>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-book" style="color: var(--primary);"></i> My Assignments</h1>
        <a href="portal_dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <?php if (empty($assignments)): ?>
        <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
            <i class="fa-solid fa-face-smile-wink fa-3x" style="margin-bottom: 1rem;"></i>
            <h3>No active assignments for your class!</h3>
        </div>
    <?php endif; ?>

    <?php foreach ($assignments as $a): ?>
    <div class="assignment-card">
        <div class="as-header">
            <div>
                <span class="as-tag tag-subject"><?php echo htmlspecialchars($a['subject_name']); ?></span>
                <h2 style="margin-top: 0.5rem;"><?php echo htmlspecialchars($a['title']); ?></h2>
            </div>
            <span class="as-tag tag-date">Due: <?php echo date('M d, Y', strtotime($a['due_date'])); ?></span>
        </div>
        <p style="color: #475569; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($a['description'])); ?></p>
        
        <?php if ($a['file_url']): ?>
            <a href="<?php echo $a['file_url']; ?>" target="_blank" class="file-link">
                <i class="fa-solid fa-paperclip"></i> Download Materials
            </a>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
