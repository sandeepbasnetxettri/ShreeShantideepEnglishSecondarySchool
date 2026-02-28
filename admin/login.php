<?php
session_start();
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/db.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if($username && $password) {
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if($user && (password_verify($password, $user['password']) || $user['password'] === $password)) {
            if($user['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Access denied. Admin privileges required.";
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
    <title>Admin Login - Everest School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --bg-color: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h1 { color: #1e293b; margin-bottom: 0.5rem; font-size: 1.5rem; }
        .login-card p { color: #64748b; margin-bottom: 2rem; font-size: 0.9rem; }
        .form-group { margin-bottom: 1.5rem; text-align: left; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; }
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-family: inherit;
        }
        input:focus { outline: none; border-color: var(--primary); }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn:hover { background: #1d4ed8; }
        .error { color: #ef4444; margin-bottom: 1rem; font-size: 0.9rem; background: #fef2f2; padding: 0.5rem; border-radius: 4px; }
    </style>
</head>
<body>

<div class="login-card">
    <i class="fa-solid fa-graduation-cap fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
    <h1>Admin Portal</h1>
    <p>Sign in to manage Everest School</p>
    
    <?php if($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <div style="margin-top: 1.5rem;">
        <a href="../index.php" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Back to Website</a>
    </div>
</div>

</body>
</html>
