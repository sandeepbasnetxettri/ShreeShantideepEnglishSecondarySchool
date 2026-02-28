<?php
session_start();
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: portal_dashboard.php");
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    
    if($username) {
        // In a real application, you'd check if the user exists and send an email with a reset token.
        // Since we don't have an email server configured, we will simulate the success message.
        $success = "If an account with that ID exists, a password reset link has been sent to your registered email.";
    } else {
        $error = "Please enter your Username / Roll No.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Everest School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --bg-color: #f8fafc; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem 1rem;}
        
        .reset-card { background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); width: 100%; max-width: 450px; text-align: center; }
        .reset-card h1 { color: #1e293b; margin-bottom: 0.5rem; font-size: 1.75rem; }
        .reset-card p { color: #64748b; margin-bottom: 2rem; font-size: 0.95rem; line-height: 1.5;}
        
        .form-group { margin-bottom: 1.25rem; text-align: left;}
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; color: #475569;}
        input { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc; transition: 0.3s; }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); background: white;}
        
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 1rem; top: 1rem; color: #94a3b8; }
        .input-icon input { padding-left: 2.5rem; }
        
        .btn { width: 100%; padding: 0.85rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.3s; margin-top: 1rem; }
        .btn:hover { background: #1d4ed8; }
        
        .error { color: #ef4444; margin-bottom: 1.5rem; font-size: 0.9rem; background: #fef2f2; padding: 0.75rem; border-radius: 6px; border-left: 4px solid #ef4444; text-align: left;}
        .success { color: #166534; margin-bottom: 1.5rem; font-size: 0.9rem; background: #dcfce7; padding: 0.75rem; border-radius: 6px; border-left: 4px solid #16a34a; text-align: left; line-height: 1.5;}
    </style>
</head>
<body>

<div class="reset-card">
    <i class="fa-solid fa-key fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
    <h1>Forgot Password?</h1>
    <p>No worries! Enter your school ID and we will send you a link to reset your password.</p>
    
    <?php if($error): ?><div class="error"><i class="fa-solid fa-circle-exclamation" style="margin-right: 0.5rem;"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?>
        <div class="success"><i class="fa-solid fa-envelope-circle-check" style="margin-right: 0.5rem;"></i><?php echo htmlspecialchars($success); ?></div>
        <div style="margin-top: 2rem;"><a href="login.php" class="btn" style="text-decoration: none; display: inline-block;">Return to Login</a></div>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username / Roll No.</label>
                <div class="input-icon">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" name="username" placeholder="Enter your ID" required>
                </div>
            </div>
            
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
        
        <div style="margin-top: 1.5rem; font-size: 0.9rem; color: #64748b;">
            Remembered your password? <a href="login.php" style="color: var(--primary); font-weight: 500; text-decoration: none;">Login here</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
