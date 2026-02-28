<?php
session_start();
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';

$message = '';
// Handle Add Notice
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_notice') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $admin_id = $_SESSION['admin_id'];
    
    // Simple file handling for attachment
    $file_url = null;
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = '../images/uploads/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $filename = time() . '_' . basename($_FILES['attachment']['name']);
        $target_file = $upload_dir . $filename;
        
        if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $file_url = 'images/uploads/' . $filename; // Relative to root
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO notices (title, content, file_url, created_by) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$title, $content, $file_url, $admin_id])) {
        $message = "Notice published successfully!";
    } else {
        $message = "Error publishing notice.";
    }
}

// Handle Delete Notice
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM notices WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: notices_manage.php");
    exit;
}

// Fetch all notices
$stmt = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC");
$notices = $stmt->fetchAll();
?>
<?php
$active_page = 'notices';
$page_title = 'Notice Board Management';
require_once 'includes/header.php';
?>

<style>
    .notice-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }
    .notice-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .btn-publish {
        background: linear-gradient(135deg, var(--primary), #3b82f6);
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    .btn-publish:hover {
        filter: brightness(1.1);
        transform: scale(1.02);
    }
    .notice-row {
        transition: background 0.2s ease;
    }
    .notice-row:hover {
        background-color: #f8fafc;
    }
    .preview-box {
        background: #f1f5f9;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        margin-top: 1rem;
        display: none;
    }
</style>

<?php if($message): ?>
    <div id="statusMessage" style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.5s ease;">
        <i class="fa-solid fa-circle-check"></i>
        <?php echo $message; ?>
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('statusMessage');
            if(msg) msg.style.opacity = '0';
            setTimeout(() => msg?.remove(), 500);
        }, 3000);
    </script>
<?php endif; ?>

<div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
    <!-- Form Section -->
    <div class="card notice-card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 45px; height: 45px; background: #eff6ff; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <h3 style="margin: 0;">Publish New Notice</h3>
        </div>
        
        <form method="POST" enctype="multipart/form-data" id="noticeForm">
            <input type="hidden" name="action" value="add_notice">
            <div class="form-group">
                <label>Notice Title</label>
                <input type="text" name="title" id="noticeTitle" class="form-control" placeholder="E.g., Winter Vacation 2024" required>
            </div>
            <div class="form-group">
                <label>Content Description</label>
                <textarea name="content" id="noticeContent" rows="5" class="form-control" placeholder="Describe the notice details here..." required></textarea>
            </div>
            
            <div class="preview-box" id="noticePreview">
                <small style="color: var(--primary); font-weight: bold; text-transform: uppercase;">Live Preview</small>
                <h4 id="prevTitle" style="margin: 0.5rem 0;"></h4>
                <p id="prevContent" style="font-size: 0.9rem; color: var(--text); white-space: pre-wrap;"></p>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Attachment (PDF/Image)</label>
                <div style="border: 2px dashed #e2e8f0; padding: 1rem; border-radius: 8px; text-align: center; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 1.5rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                    <p style="font-size: 0.85rem; color: #64748b;" id="fileName">Click or Drag to Upload</p>
                    <input type="file" name="attachment" id="fileInput" hidden onchange="document.getElementById('fileName').textContent = this.files[0].name">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-publish" style="width: 100%;">
                <i class="fa-solid fa-paper-plane" style="margin-right: 0.5rem;"></i> Publish Notice
            </button>
        </form>
    </div>

    <!-- List Section -->
    <div class="card notice-card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 45px; height: 45px; background: #fff7ed; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h3 style="margin: 0;">Published Notices</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Notice Details</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notices as $n): ?>
                    <tr class="notice-row">
                        <td style="white-space: nowrap;">
                            <div style="font-weight: 600;"><?php echo date('M d', strtotime($n['created_at'])); ?></div>
                            <small style="color: #94a3b8;"><?php echo date('Y', strtotime($n['created_at'])); ?></small>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--text);"><?php echo htmlspecialchars($n['title']); ?></div>
                            <small style="color: #64748b; display: block; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($n['content']); ?>
                            </small>
                        </td>
                        <td style="text-align: center;">
                            <?php if($n['file_url']): ?>
                                <a href="../<?php echo $n['file_url']; ?>" target="_blank" style="color: var(--primary); font-size: 1.25rem;" title="View Attachment">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            <?php else: ?>
                                <span style="color: #cbd5e1;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $n['id']; ?>" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem;" onclick="return confirm('Archive this notice?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($notices)): ?>
                        <tr><td colspan="4" style="text-align: center; padding: 3rem; color: #94a3b8;">No notices published yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const titleInp = document.getElementById('noticeTitle');
    const contentInp = document.getElementById('noticeContent');
    const previewBox = document.getElementById('noticePreview');
    const prevTitle = document.getElementById('prevTitle');
    const prevContent = document.getElementById('prevContent');

    function updatePreview() {
        if(titleInp.value || contentInp.value) {
            previewBox.style.display = 'block';
            prevTitle.textContent = titleInp.value;
            prevContent.textContent = contentInp.value;
        } else {
            previewBox.style.display = 'none';
        }
    }

    titleInp.addEventListener('input', updatePreview);
    contentInp.addEventListener('input', updatePreview);

    // Form Animation
    document.getElementById('noticeForm').onsubmit = function() {
        const btn = this.querySelector('.btn-publish');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Publishing...';
        btn.style.opacity = '0.7';
    };
</script>

<?php require_once 'includes/header.php'; ?>
