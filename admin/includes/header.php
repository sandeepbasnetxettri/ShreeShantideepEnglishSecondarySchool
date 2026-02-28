<?php
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Everest Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --sidebar-bg: #1e293b;
            --bg: #f1f5f9;
            --text: #334155;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: white;
            height: 100vh;
            position: fixed;
            padding: 1.5rem 0;
            z-index: 1000;
        }
        .sidebar-brand { text-align: center; padding-bottom: 1.5rem; border-bottom: 1px solid #334155; margin-bottom: 1rem; }
        .nav-link {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            transition: 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--primary);
        }
        .nav-link i { width: 25px; }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 2rem;
            min-height: 100vh;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .btn-logout { background: var(--danger); color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        
        /* Common Layout Elements */
        .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .btn { padding: 0.6rem 1.2rem; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 500; font-size: 0.9rem; transition: 0.2s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: var(--danger); color: white; }
        
        /* Forms */
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-family: inherit; }
        
        /* Tables */
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table th { background: #f8fafc; font-weight: 600; color: #64748b; font-size: 0.85rem; text-transform: uppercase; }
        
        .badge { padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .badge-student { background: #eff6ff; color: #3b82f6; }
        .badge-teacher { background: #f0fdf4; color: #22c55e; }
        .badge-admin { background: #fef2f2; color: #ef4444; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-graduation-cap fa-2x"></i>
        <h2 style="font-size: 1.25rem; margin-top: 0.5rem;">Everest Admin</h2>
    </div>
    <a href="index.php" class="nav-link <?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="notices_manage.php" class="nav-link <?php echo ($active_page == 'notices') ? 'active' : ''; ?>"><i class="fa-solid fa-bullhorn"></i> Manage Notices</a>
    <a href="gallery_manage.php" class="nav-link <?php echo ($active_page == 'gallery') ? 'active' : ''; ?>"><i class="fa-solid fa-images"></i> Manage Gallery</a>
    <a href="attendance_manage.php" class="nav-link <?php echo ($active_page == 'attendance') ? 'active' : ''; ?>"><i class="fa-solid fa-clipboard-user"></i> Attendance</a>
    <a href="results_manage.php" class="nav-link <?php echo ($active_page == 'results') ? 'active' : ''; ?>"><i class="fa-solid fa-square-poll-vertical"></i> Exam Results</a>
    <a href="assignments_manage.php" class="nav-link <?php echo ($active_page == 'assignments') ? 'active' : ''; ?>"><i class="fa-solid fa-book"></i> Assignments</a>
    <a href="students_manage.php" class="nav-link <?php echo ($active_page == 'students') ? 'active' : ''; ?>"><i class="fa-solid fa-users"></i> Students</a>
    <a href="teachers_manage.php" class="nav-link <?php echo ($active_page == 'teachers') ? 'active' : ''; ?>"><i class="fa-solid fa-chalkboard-user"></i> Teachers</a>
    <a href="settings.php" class="nav-link <?php echo ($active_page == 'settings') ? 'active' : ''; ?>"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="main-content">
    <div class="topbar">
        <h2><?php echo $page_title; ?></h2>
        <div>
            <span style="margin-right: 1rem;"><i class="fa-solid fa-user-shield"></i> Admin User</span>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>
