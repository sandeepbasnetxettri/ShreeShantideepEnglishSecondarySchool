<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

// Fetch gallery items from database
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT 12");
$media_items = $stmt->fetchAll();
?>

<!-- Page Header -->
<div style="background: var(--primary-dark); padding: 4rem 0; text-align: center; color: white;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Photo & Video Gallery</h1>
        <p style="font-size: 1.2rem; color: #cbd5e1;">Glimpses of life, learning, and celebrations at Everest School.</p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">

    <!-- Filter Buttons -->
    <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap;">
        <button class="btn btn-primary">All</button>
        <button class="btn" style="background: white; border: 1px solid var(--primary); color: var(--primary);">School Activities</button>
        <button class="btn" style="background: white; border: 1px solid var(--primary); color: var(--primary);">Sports</button>
        <button class="btn" style="background: white; border: 1px solid var(--primary); color: var(--primary);">Events</button>
        <button class="btn" style="background: white; border: 1px solid var(--primary); color: var(--primary);">Practical Labs</button>
    </div>

    <!-- Masonry Gallery Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        
        <?php if(count($media_items) > 0): ?>
            <?php foreach($media_items as $item): ?>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <?php if($item['type'] == 'image'): ?>
                    <img src="<?php echo htmlspecialchars($item['media_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <?php else: ?>
                    <video src="<?php echo htmlspecialchars($item['media_url']); ?>" controls style="width: 100%; height: 100%; object-fit: cover;"></video>
                <?php endif; ?>
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0; font-size: 1.1rem; text-shadow: 1px 1px 2px black;"><?php echo htmlspecialchars($item['title']); ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Placeholder Images if no database entries -->
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Students in Classroom" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Interactive Learning</h4>
                </div>
            </div>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Graduation" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Annual Function</h4>
                </div>
            </div>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Computer Lab" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Computer Science Lab</h4>
                </div>
            </div>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Kitchen Lab" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Hotel Management Training</h4>
                </div>
            </div>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Sports" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Sports Week</h4>
                </div>
            </div>
            <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); position: relative; aspect-ratio: 4/3;">
                <img src="https://images.unsplash.com/photo-1588667355001-eb2c1e29c077?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Science Fair" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 1rem;">
                    <h4 style="margin: 0;">Science Fair Exhibition</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
