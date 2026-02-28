<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

// Fetch notices from database
$stmt = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 10");
$notices = $stmt->fetchAll();
?>

<!-- Page Header -->
<style>
    :root {
        --notice-glass: rgba(255, 255, 255, 0.8);
    }
    .notice-card {
        background: var(--notice-glass);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .notice-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    .notice-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: var(--primary);
    }
    .notice-date {
        font-size: 0.85rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.4rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sidebar-widget {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .event-item {
        display: flex;
        gap: 1.25rem;
        padding: 1.25rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: padding-left 0.2s ease;
    }
    .event-item:hover {
        padding-left: 0.5rem;
    }
    .event-date-box {
        background: #eff6ff;
        color: var(--primary);
        padding: 0.75rem;
        border-radius: 12px;
        text-align: center;
        min-width: 65px;
        font-weight: 800;
        line-height: 1.1;
    }
</style>

<div class="container" style="padding: 4rem 1.5rem; display: grid; grid-template-columns: 2.5fr 1fr; gap: 4rem;">
    <!-- Main Notices Area -->
    <div>
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
            <div style="width: 50px; height: 50px; background: #fff7ed; color: #f59e0b; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-bullhorn rotate-12"></i>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 2.25rem; font-weight: 800; color: #1e293b;">Latest Announcements</h2>
                <p style="margin: 0; color: #64748b;">Important updates from the school administration.</p>
            </div>
        </div>

        <?php if(count($notices) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <?php foreach($notices as $notice): ?>
                <div class="notice-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                        <h3 style="color: #0f172a; font-size: 1.5rem; font-weight: 700; margin: 0;"><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <div class="notice-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <?php echo date('M d, Y', strtotime($notice['created_at'])); ?>
                        </div>
                    </div>
                    <p style="color: #475569; margin-bottom: 2rem; line-height: 1.8; font-size: 1.05rem;">
                        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <?php if($notice['file_url']): ?>
                        <a href="<?php echo htmlspecialchars($notice['file_url']); ?>" target="_blank" class="btn btn-secondary" style="border-radius: 10px; font-weight: 600;">
                            <i class="fa-solid fa-file-pdf" style="margin-right: 0.5rem; color: #ef4444;"></i> Download Attachment
                        </a>
                        <?php endif; ?>
                        
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; font-size: 0.9rem;">
                            <i class="fa-solid fa-check-double"></i> Verified by Admin
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="background: white; padding: 5rem; text-align: center; border-radius: 20px; box-shadow: var(--shadow); border: 2px dashed #e2e8f0;">
                <div style="width: 80px; height: 80px; background: #f8fafc; color: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
                    <i class="fa-solid fa-bell-slash"></i>
                </div>
                <h3 style="color: #1e293b; font-weight: 700;">Broadcast Quiet</h3>
                <p style="color: #64748b; max-width: 400px; margin: 0 auto;">There are no new notices published at the moment. Please check back later for updates.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Events -->
    <div>
        <div class="sidebar-widget">
            <h3 style="color: #0f172a; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                <i class="fa-solid fa-calendar-star" style="color: var(--primary);"></i> Upcoming Events
            </h3>
            
            <div style="display: flex; flex-direction: column;">
                <div class="event-item">
                    <div class="event-date-box">
                        <span style="font-size: 1.5rem; display: block;">25</span>
                        <span style="font-size: 0.75rem; text-transform: uppercase;">Nov</span>
                    </div>
                    <div>
                        <h4 style="color: #1e293b; font-weight: 700; margin-bottom: 0.25rem;">Annual Sports Meet</h4>
                        <p style="color: #64748b; font-size: 0.85rem; margin: 0;"><i class="fa-solid fa-location-dot" style="width: 15px;"></i> School Stadium</p>
                    </div>
                </div>
                
                <div class="event-item">
                    <div class="event-date-box" style="background: #f0fdf4; color: #22c55e;">
                        <span style="font-size: 1.5rem; display: block;">10</span>
                        <span style="font-size: 0.75rem; text-transform: uppercase;">Dec</span>
                    </div>
                    <div>
                        <h4 style="color: #1e293b; font-weight: 700; margin-bottom: 0.25rem;">Science Carnival</h4>
                        <p style="color: #64748b; font-size: 0.85rem; margin: 0;"><i class="fa-solid fa-location-dot" style="width: 15px;"></i> Block B Labs</p>
                    </div>
                </div>
            </div>

            <a href="contact.php" class="btn" style="width: 100%; margin-top: 2rem; background: #f1f5f9; color: #475569; border: none; font-weight: 600; border-radius: 12px; padding: 1rem;">
                <i class="fa-solid fa-info-circle"></i> View Full Calendar
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
