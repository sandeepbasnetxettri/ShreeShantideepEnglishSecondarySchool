<?php include 'includes/header.php'; ?>

<!-- Hero Slider Section -->
<section class="hero" style="padding: 0;">
    <div class="hero-slider">
        <!-- Slide 1 -->
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Students in campus" loading="lazy">
            <div class="slide-content">
                <h2>Welcome to Everest School</h2>
                <p>Nurturing minds, shaping futures with excellence.</p>
                <a href="about.php" class="btn btn-primary">Discover More</a>
            </div>
        </div>
        <!-- Slide 2: Events -->
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Graduation Event" loading="lazy">
            <div class="slide-content">
                <h2>Annual Sports Meet 2026</h2>
                <p>Join us in celebrating athleticism and spirit.</p>
                <a href="notices.php" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
        <!-- Slide 3: Achievements -->
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1588667355001-eb2c1e29c077?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Laboratory" loading="lazy">
            <div class="slide-content">
                <h2>State-of-the-art Labs</h2>
                <p>Computer Science and Hotel Management facilities.</p>
                <a href="academics.php" class="btn btn-primary">View Programs</a>
            </div>
        </div>
    </div>
    
    <div class="slider-controls">
        <div class="slider-dot active"></div>
        <div class="slider-dot"></div>
        <div class="slider-dot"></div>
    </div>
</section>

<!-- Quick Links Grid -->
<div class="container relative z-10">
    <div class="quick-links">
        <div class="quick-link-card">
            <a href="admission.php">
                <i class="fa-solid fa-user-plus"></i>
                <h3>Admissions Open</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Enroll for 2026 session</p>
            </a>
        </div>
        <div class="quick-link-card">
            <a href="results.php">
                <i class="fa-solid fa-square-poll-vertical"></i>
                <h3>Check Results</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Term Examinations</p>
            </a>
        </div>
        <div class="quick-link-card">
            <a href="notices.php">
                <i class="fa-solid fa-bullhorn"></i>
                <h3>Notice Board</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Latest updates</p>
            </a>
        </div>
        <div class="quick-link-card">
            <a href="contact.php">
                <i class="fa-solid fa-address-book"></i>
                <h3>Contact Us</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Get in touch</p>
            </a>
        </div>
    </div>
</div>

<!-- Welcome Section -->
<section>
    <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
        <div>
            <h2 style="font-size: 2.5rem; color: var(--primary-dark); margin-bottom: 1.5rem;">Principal's Message</h2>
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem; color: #475569;">
                "Education is the passport to the future, for tomorrow belongs to those who prepare for it today. 
                At Everest School, we provide a vibrant learning environment that encourages curiosity and innovation."
            </p>
            <p style="font-weight: 600;">- Dr. Sharma, Principal</p>
            <a href="about.php" class="btn btn-secondary" style="margin-top: 2rem;">Read Full Message</a>
        </div>
        <div style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-lg);">
            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Students studying" style="width:100%; height:100%; object-fit:cover;">
        </div>
    </div>
</section>

<!-- Stats Section -->
<section style="background: var(--primary-dark); color: white; text-align: center; padding: 5rem 0;">
    <div class="container" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;">
        <div>
            <i class="fa-solid fa-users fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 2.5rem;">1500+</h3>
            <p style="color: #cbd5e1;">Students</p>
        </div>
        <div>
            <i class="fa-solid fa-chalkboard-user fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 2.5rem;">85+</h3>
            <p style="color: #cbd5e1;">Expert Teachers</p>
        </div>
        <div>
            <i class="fa-solid fa-book-open fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 2.5rem;">25+</h3>
            <p style="color: #cbd5e1;">Courses</p>
        </div>
        <div>
            <i class="fa-solid fa-award fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 2.5rem;">20+</h3>
            <p style="color: #cbd5e1;">Years of Excellence</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
