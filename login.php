<?php
session_start();
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/db.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'student';
    
    if($username && $password) {
        // In a real app, you would verify against the hashed password
        // For demonstration, we simply check the username
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ? AND role = ?");
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch();
        
        // This is simplified. Ideally use password_verify($password, $user['password'])
        if($user && (password_verify($password, $user['password']) || $user['password'] === $password)) {
            if ($user['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                header("Location: admin/index.php");
                exit;
            } else {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: portal_dashboard.php");
                exit;
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login - Everest School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #10b981;
            --bg-color: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-wrapper {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }
        .login-image {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover no-repeat;
            position: relative;
        }
        .login-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(37, 99, 235, 0.8), rgba(16, 185, 129, 0.8));
        }
        .login-image-content {
            position: relative;
            z-index: 10;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
        .login-image-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .login-image-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .login-card {
            flex: 1;
            padding: 4rem 3rem;
            background: white;
        }
        .login-card h1 { color: #1e293b; margin-bottom: 0.5rem; font-size: 1.75rem; }
        .login-card p { color: #64748b; margin-bottom: 2rem; font-size: 0.95rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; color: #475569;}
        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: inherit;
            background: #f8fafc;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: white;
        }
        .btn {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 1rem;
        }
        .btn:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .error { color: #ef4444; margin-bottom: 1.5rem; font-size: 0.9rem; background: #fef2f2; padding: 0.75rem; border-radius: 6px; border-left: 4px solid #ef4444;}
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 2rem;
            transition: 0.2s;
        }
        .back-link:hover { color: var(--primary); }
        
        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; margin: 1rem; border-radius: 8px;}
            .login-image { display: none; }
            .login-card { padding: 2.5rem 2rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-image">
        <div class="login-image-content">
            <h2>Everest Portal</h2>
            <p>Access your academic progress, timetables, and personalized resources all in one place.</p>
            <div style="display: flex; gap: 1rem; font-size: 2rem; opacity: 0.8;">
                <i class="fa-solid fa-book-open-reader"></i>
                <i class="fa-solid fa-laptop-code"></i>
                <i class="fa-solid fa-flask"></i>
            </div>
        </div>
    </div>
    
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fa-solid fa-graduation-cap fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
            <h1>Welcome Back</h1>
            <p>Please enter your credentials to continue</p>
        </div>
        
        <?php if($error): ?>
            <div class="error"><i class="fa-solid fa-circle-exclamation" style="margin-right: 0.5rem;"></i><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>I am a...</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-users" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                    <select name="role" required style="padding-left: 2.5rem;">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Username / Roll No.</label>
                <div style="position: relative;">
                    <i class="fa-regular fa-user" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                    <input type="text" name="username" placeholder="Enter your ID" required style="padding-left: 2.5rem;">
                </div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; justify-content: space-between;">
                    Password
                    <a href="forgot_password.php" style="color: var(--primary); text-decoration: none; font-weight: 400; font-size: 0.85rem;">Forgot Password?</a>
                </label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                    <input type="password" name="password" placeholder="••••••••" required style="padding-left: 2.5rem;">
                </div>
            </div>
            
            <button type="submit" class="btn">Sign In to Portal <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></button>
            
            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #64748b;">
                Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 500; text-decoration: none;">Register now</a>
            </div>
        </form>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Return to Homepage</a>
        </div>
    </div>
</div>

</body>
</html>
