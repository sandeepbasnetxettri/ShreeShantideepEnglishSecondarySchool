<?php
session_start();
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';
$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_media') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $type = $_POST['type']; // 'image' or 'video'
    $media_url = '';
    
    // Handle File Upload
    if(isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
        $upload_dir = '../images/gallery/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $filename = time() . '_' . basename($_FILES['media_file']['name']);
        $target_file = $upload_dir . $filename;
        
        // Simple security check can be explicitly added here later for production
        if(move_uploaded_file($_FILES['media_file']['tmp_name'], $target_file)) {
            $media_url = 'images/gallery/' . $filename;
        } else {
             $message = "File upload failed.";
        }
    } elseif(isset($_POST['media_link']) && !empty($_POST['media_link'])) {
         // Allow external URLs (e.g., YouTube embed links)
         $media_url = $_POST['media_link'];
    }
    
    if($media_url !== '') {
        $stmt = $pdo->prepare("INSERT INTO gallery (title, category, type, media_url) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$title, $category, $type, $media_url])) {
            $message = "Media uploaded successfully!";
        } else {
            $message = "Database error occurred.";
        }
    } else {
         $message = "Please provide an image/video file or external link.";
    }
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Option: Delete physical file as well
    $stmt = $pdo->prepare("SELECT media_url FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    
    if($media && strpos($media['media_url'], 'images/gallery') === 0 && file_exists('../' . $media['media_url'])) {
        unlink('../' . $media['media_url']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: gallery_manage.php");
    exit;
}

// Fetch all media
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
$gallery_items = $stmt->fetchAll();
?>
<?php
$active_page = 'gallery';
$page_title = 'Gallery Management';
require_once 'includes/header.php';
?>

<style>
    .upload-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: none;
        transition: transform 0.3s ease;
    }
    .upload-card:hover {
        transform: translateY(-5px);
    }
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    .media-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .media-card:hover {
        transform: scale(1.03);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        z-index: 10;
    }
    .media-card img, .media-card video {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .media-card:hover img {
        transform: scale(1.1);
    }
    .media-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
        color: white;
    }
    .media-card:hover .media-overlay {
        opacity: 1;
    }
    .btn-delete-media {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn-delete-media:hover {
        background: #ef4444;
        transform: rotate(90deg);
    }
    .category-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        background: var(--primary);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
</style>

<?php if($message): ?>
    <div id="statusMessage" style="background: var(--primary); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.5s ease;">
        <i class="fa-solid fa-cloud-check"></i>
        <?php echo htmlspecialchars($message); ?>
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

<div class="card upload-card">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="width: 45px; height: 45px; background: #f0fdf4; color: #10b981; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <h3 style="margin: 0;">Add to Gallery</h3>
    </div>

    <form method="POST" enctype="multipart/form-data" id="uploadForm">
        <input type="hidden" name="action" value="add_media">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div class="form-group">
                <label>Media Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Annual Sports Day" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="school_activities">School Activities</option>
                    <option value="sports">Sports</option>
                    <option value="events">Events</option>
                    <option value="labs">Practical Labs</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" id="mediaType" class="form-control" required>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="form-group">
                <label>Upload File</label>
                <div style="border: 2px dashed #e2e8f0; padding: 0.5rem; border-radius: 4px; position: relative;">
                    <input type="file" name="media_file" id="media_file" class="form-control" accept="image/*,video/*" style="border: none; padding: 0.25rem;">
                </div>
            </div>
        </div>
        
        <div style="margin: 1.5rem 0; display: flex; align-items: center; gap: 1rem;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600;">OR PROVIDE LINK</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <div class="form-group">
            <label>External URL (YouTube / Unsplash / etc.)</label>
            <input type="url" name="media_link" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 600; padding: 1rem;">
            <i class="fa-solid fa-plus-circle" style="margin-right: 0.5rem;"></i> Add to Gallery
        </button>
    </form>
</div>

<style>
    .filter-bar {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    .filter-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-btn:hover {
        background: #f8fafc;
        border-color: var(--primary);
    }
    .filter-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
</style>

<div class="main-header" style="margin-top: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h3 style="margin: 0;">Shared Gallery Assets</h3>
        <div class="filter-bar">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="school_activities">School Activities</button>
            <button class="filter-btn" data-filter="sports">Sports</button>
            <button class="filter-btn" data-filter="events">Events</button>
            <button class="filter-btn" data-filter="labs">Practical Labs</button>
        </div>
    </div>
    <div style="color: #64748b; font-size: 0.9rem; padding-bottom: 0.5rem;">
        <i class="fa-solid fa-layer-group"></i> Total Items: <?php echo count($gallery_items); ?>
    </div>
</div>

<div class="media-grid">
    <?php foreach($gallery_items as $item): ?>
    <div class="media-card" data-category="<?php echo $item['category']; ?>">
        <?php if($item['type'] == 'image'): ?>
            <img src="<?php echo strpos($item['media_url'], 'http') === 0 ? htmlspecialchars($item['media_url']) : '../'.htmlspecialchars($item['media_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
        <?php else: ?>
            <video muted loop onmouseover="this.play()" onmouseout="this.pause()">
                <source src="<?php echo strpos($item['media_url'], 'http') === 0 ? htmlspecialchars($item['media_url']) : '../'.htmlspecialchars($item['media_url']); ?>">
            </video>
        <?php endif; ?>
        
        <div class="media-overlay">
            <span class="category-badge"><?php echo str_replace('_', ' ', $item['category']); ?></span>
            <h4 style="margin: 0; font-weight: 600;"><?php echo htmlspecialchars($item['title']); ?></h4>
            <small style="opacity: 0.8; margin-top: 0.25rem;">Added <?php echo date('M d, Y', strtotime($item['uploaded_at'])); ?></small>
        </div>

        <a href="?delete=<?php echo $item['id']; ?>" class="btn-delete-media" title="Remove" onclick="return confirm('Archive this asset?');">
            <i class="fa-solid fa-trash-can"></i>
        </a>
    </div>
    <?php endforeach; ?>
    
    <div id="noItemsMessage" style="display: none; grid-column: 1/-1; text-align: center; padding: 5rem; background: white; border-radius: 12px; border: 2px dashed #e2e8f0;">
        <i class="fa-solid fa-magnifying-glass fa-3x" style="color: #cbd5e1; margin-bottom: 1rem;"></i>
        <h3 style="color: #64748b;">No matching assets</h3>
        <p style="color: #94a3b8;">Try selecting a different category.</p>
    </div>

    <?php if(empty($gallery_items)): ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 5rem; background: white; border-radius: 12px; border: 2px dashed #e2e8f0;">
            <i class="fa-solid fa-images fa-3x" style="color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h3 style="color: #64748b;">Gallery is empty</h3>
            <p style="color: #94a3b8;">Start by uploading your school's best moments.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Category Filtering Logic
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update Active State
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            const cards = document.querySelectorAll('.media-card');
            let visibleCount = 0;

            cards.forEach(card => {
                if(filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('noItemsMessage').style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
        });
    });
    document.getElementById('uploadForm').onsubmit = function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
        btn.disabled = true;
    };

    // Auto-detect type from URL if possible
    document.querySelector('input[name="media_link"]').addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        const typeSelect = document.getElementById('mediaType');
        if(val.includes('youtube') || val.includes('vimeo') || val.endsWith('.mp4')) {
            typeSelect.value = 'video';
        } else if(val.includes('unsplash') || val.endsWith('.jpg') || val.endsWith('.png')) {
            typeSelect.value = 'image';
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
