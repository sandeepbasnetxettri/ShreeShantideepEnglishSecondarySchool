<?php
session_start();
require_once '../config/db.php';

$active_page = 'settings';
$page_title = 'System Settings';
$message = '';

// Handle Update Settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_settings') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    $message = "Settings updated successfully!";
}

// Fetch all settings
$stmt = $pdo->query("SELECT * FROM settings");
$results = $stmt->fetchAll();
$settings = [];
foreach ($results as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

require_once 'includes/header.php';
?>

<style>
    .settings-card {
        border: none;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem;
    }
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .form-group label {
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }
    .form-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
</style>

<?php if ($message): ?>
    <div id="statusMessage" style="background: #f59e0b; color: white; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.5s ease; max-width: 900px; margin-left: auto; margin-right: auto;">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        <?php echo $message; ?>
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('statusMessage');
            if(msg) {
                msg.style.transition = 'all 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-20px)';
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
<?php endif; ?>

<div class="card settings-card">
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="width: 70px; height: 70px; background: #fffbeb; color: #f59e0b; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
            <i class="fa-solid fa-gears"></i>
        </div>
        <h2 style="font-weight: 800; color: #1e293b;">Core Configuration</h2>
        <p style="color: #64748b;">Manage your school's global identity and contact profile.</p>
    </div>

    <form method="POST" id="settingsForm">
        <input type="hidden" name="action" value="update_settings">
        <div class="settings-grid">
            <div class="form-group">
                <label>Institution Name</label>
                <input type="text" name="settings[school_name]" value="<?php echo htmlspecialchars($settings['school_name'] ?? ''); ?>" class="form-control" placeholder="Enter school name">
            </div>
            <div class="form-group">
                <label>Official Support Email</label>
                <input type="email" name="settings[school_email]" value="<?php echo htmlspecialchars($settings['school_email'] ?? ''); ?>" class="form-control" placeholder="contact@yourschool.com">
            </div>
            <div class="form-group">
                <label>Contact Hotline</label>
                <input type="text" name="settings[school_phone]" value="<?php echo htmlspecialchars($settings['school_phone'] ?? ''); ?>" class="form-control" placeholder="+977-XXXXXXXXXX">
            </div>
            <div class="form-group">
                <label>Primary Office Address</label>
                <input type="text" name="settings[school_address]" value="<?php echo htmlspecialchars($settings['school_address'] ?? ''); ?>" class="form-control" placeholder="E.g. Heritage Plaza, Kathmandu">
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-weight: 700; border-radius: 12px; background: #1e293b; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                Update Global Settings
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('settingsForm').onsubmit = function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        btn.style.opacity = '0.8';
    };
</script>

<?php require_once 'includes/footer.php'; ?>
