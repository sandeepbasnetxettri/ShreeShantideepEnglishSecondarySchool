<?php
session_start();
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: portal_dashboard.php");
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/db.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'student';
    $name = trim($_POST['name'] ?? '');
    
    if($username && $password && $confirm_password && $name) {
        if($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif(strlen($password) < 6) {
             $error = "Password must be at least 6 characters long.";
        } else {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if($stmt->rowCount() > 0) {
                $error = "Username/Roll No. already exists. Please login instead.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                try {
                    $pdo->beginTransaction();
                    
                    // Insert into users
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $hashed_password, $role]);
                    $user_id = $pdo->lastInsertId();
                    
                    // Depending on role, insert into students or teachers (Basic skeleton for now)
                    if($role === 'student') {
                        // Ensure at least one class exists to avoid foreign key errors
                        $stmt_class = $pdo->query("SELECT id FROM classes LIMIT 1");
                        $class = $stmt_class->fetch();
                        $class_id = $class ? $class['id'] : null;
                        
                        if(!$class_id) {
                            $pdo->exec("INSERT INTO classes (class_name) VALUES ('Class 10')");
                            $class_id = $pdo->lastInsertId();
                        }
                        
                        $stmt2 = $pdo->prepare("INSERT INTO students (user_id, roll_no, name, class_id) VALUES (?, ?, ?, ?)");
                        $stmt2->execute([$user_id, $username, $name, $class_id]);
                    } else if ($role === 'teacher') {
                        $stmt2 = $pdo->prepare("INSERT INTO teachers (user_id, name) VALUES (?, ?)");
                        $stmt2->execute([$user_id, $name]);
                    }
                    
                    $pdo->commit();
                    $success = "Registration successful! You can now login.";
                } catch(Exception $e) {
                    $pdo->rollBack();
                    $error = "Registration failed: " . $e->getMessage();
                }
            }
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
    <title>Register - Everest School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --bg-color: #f8fafc; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem 1rem;}
        
        .register-card { background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); width: 100%; max-width: 500px; }
        .register-card h1 { color: #1e293b; margin-bottom: 0.5rem; font-size: 1.75rem; text-align: center;}
        .register-card p { color: #64748b; margin-bottom: 2rem; font-size: 0.95rem; text-align: center;}
        
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; color: #475569;}
        input, select { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc; transition: 0.3s; }
        input:focus, select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); background: white;}
        
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 1rem; top: 1rem; color: #94a3b8; }
        .input-icon input, .input-icon select { padding-left: 2.5rem; }
        
        .btn { width: 100%; padding: 0.85rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.3s; margin-top: 1rem; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #ef4444; margin-bottom: 1.5rem; font-size: 0.9rem; background: #fef2f2; padding: 0.75rem; border-radius: 6px; border-left: 4px solid #ef4444;}
        .success { color: #166534; margin-bottom: 1.5rem; font-size: 0.9rem; background: #dcfce7; padding: 0.75rem; border-radius: 6px; border-left: 4px solid #16a34a;}
    </style>
</head>
<body>

<div class="register-card">
    <div style="text-align: center;">
        <i class="fa-solid fa-user-plus fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
    </div>
    <h1>Create an Account</h1>
    <p>Join the Everest Portal</p>
    
    <?php if($error): ?><div class="error"><i class="fa-solid fa-circle-exclamation" style="margin-right: 0.5rem;"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?>
        <div class="success"><i class="fa-solid fa-circle-check" style="margin-right: 0.5rem;"></i><?php echo htmlspecialchars($success); ?></div>
        <div style="text-align: center; margin-bottom: 2rem;"><a href="login.php" class="btn" style="text-decoration: none; display: inline-block;">Go to Login</a></div>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>I am a...</label>
                <div class="input-icon">
                    <i class="fa-solid fa-users"></i>
                    <select name="role" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Administrator Profile</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-icon">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="name" placeholder="John Doe" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Username / Roll No.</label>
                <div class="input-icon">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" name="username" placeholder="e.g., 10A-45" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Min. 6 characters" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
            </div>
            
            <button type="submit" class="btn">Register Account</button>
        </form>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #64748b;">
        Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 500; text-decoration: none;">Login here</a>
    </div>
</div>

</body>
</html>

