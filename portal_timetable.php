<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}
$student_name = "Aarav Sharma";
$student_class = "Class 10";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Timetable - Everest Portal</title>
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

        .container { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.75rem; color: var(--text-main); display: flex; align-items: center; gap: 0.75rem;}
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-weight: 500; }
        .btn-back:hover { color: var(--primary); }

        .timetable-card { background: var(--card-bg); border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 1rem; text-align: center; border: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: var(--text-main); font-weight: 600; }
        .lunch-break { background: #fffbeb; font-weight: 600; color: #d97706; letter-spacing: 2px;}
        .period-sub { font-weight: 500; color: var(--text-main); display: block; margin-bottom: 0.25rem; }
        .period-teacher { font-size: 0.8rem; color: var(--text-muted); display: block; }
    </style>
</head>
<body>

<nav class="portal-nav">
    <a href="portal_dashboard.php" class="portal-brand">
        <i class="fa-solid fa-graduation-cap fa-2x"></i>
        <h2>Everest Portal</h2>
    </a>
    <div class="user-menu">
        <img src="https://ui-avatars.com/api/?name=Aarav+Sharma&background=0D8ABC&color=fff" alt="User">
        <a href="logout_portal.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-regular fa-calendar-days" style="color: var(--primary);"></i> Class Timetable</h1>
        <a href="portal_dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="timetable-card">
        <table>
            <thead>
                <tr>
                    <th>Day / Time</th>
                    <th>10:00 - 11:00</th>
                    <th>11:00 - 11:45</th>
                    <th>11:45 - 12:30</th>
                    <th>12:30 - 1:15</th>
                    <th>1:15 - 2:00</th>
                    <th>2:00 - 2:40</th>
                    <th>2:40 - 3:20</th>
                    <th>3:20 - 4:00</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Sunday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td rowspan="6" class="lunch-break" style="writing-mode: vertical-rl; transform: rotate(180deg);">BREAK (1:15 - 2:00)</td>
                    <td><span class="period-sub">Social Studies</span><span class="period-teacher">Mr. Thapa</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Opt. Mathematics</span><span class="period-teacher">Mr. Kumar</span></td>
                </tr>
                <tr>
                    <th>Monday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Social Studies</span><span class="period-teacher">Mr. Thapa</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Opt. Mathematics</span><span class="period-teacher">Mr. Kumar</span></td>
                </tr>
                <tr>
                    <th>Tuesday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Social Studies</span><span class="period-teacher">Mr. Thapa</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Opt. Mathematics</span><span class="period-teacher">Mr. Kumar</span></td>
                </tr>
                <tr>
                    <th>Wednesday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Social Studies</span><span class="period-teacher">Mr. Thapa</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Opt. Mathematics</span><span class="period-teacher">Mr. Kumar</span></td>
                </tr>
                <tr>
                    <th>Thursday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Social Studies</span><span class="period-teacher">Mr. Thapa</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td><span class="period-sub">Opt. Mathematics</span><span class="period-teacher">Mr. Kumar</span></td>
                </tr>
                <tr>
                    <th>Friday</th>
                    <td><span class="period-sub">Mathematics</span><span class="period-teacher">Mr. Sharma</span></td>
                    <td><span class="period-sub">English</span><span class="period-teacher">Ms. Rai</span></td>
                    <td><span class="period-sub">Science</span><span class="period-teacher">Dr. Patel</span></td>
                    <td><span class="period-sub">Nepali</span><span class="period-teacher">Mrs. Adhikari</span></td>
                    <td colspan="3"><span class="period-sub">Clubs / Extracurricular Activities</span><span class="period-teacher">Various</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
