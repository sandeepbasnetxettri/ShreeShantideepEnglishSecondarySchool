<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div style="background: var(--primary-dark); padding: 4rem 0; text-align: center; color: white;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">About Everest School</h1>
        <p style="font-size: 1.2rem; color: #cbd5e1;">A Legacy of Academic Excellence</p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">
    <!-- History & Vision -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 4rem;">
        <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-clock-rotate-left fa-2x" style="color: var(--primary);"></i>
                <h2 style="color: var(--primary-dark);">Our History</h2>
            </div>
            <p style="color: var(--text-muted); line-height: 1.8;">
                Founded in 2005, Everest International School began with a simple mission: to provide world-class education accessible to everyone. Over the past two decades, we have grown from a small facility to a sprawling campus accommodating over 1500 students. Our alumni are spread across the globe, excelling in various professional fields.
            </p>
        </div>
        
        <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-eye fa-2x" style="color: var(--primary);"></i>
                <h2 style="color: var(--primary-dark);">Vision & Mission</h2>
            </div>
            <p style="color: var(--text-muted); line-height: 1.8;">
                <strong>Vision:</strong> Empowering students to become global leaders through innovative and holistic education.<br><br>
                <strong>Mission:</strong> To foster a stimulating learning environment that encourages critical thinking, creativity, and moral integrity. We strive to develop lifelong learners capable of navigating a complex world.
            </p>
        </div>
    </div>

    <!-- Management Info -->
    <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; color: var(--primary-dark); margin-bottom: 2rem;">School Management</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <!-- Member 1 -->
            <div style="background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Chairman" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="color: var(--text-main);">Prof. R.K. Sharma</h3>
                    <p style="color: var(--primary); font-weight: 500;">Chairman</p>
                </div>
            </div>
            <!-- Member 2 -->
            <div style="background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Principal" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="color: var(--text-main);">Dr. Anita Thapa</h3>
                    <p style="color: var(--primary); font-weight: 500;">Principal</p>
                </div>
            </div>
            <!-- Member 3 -->
            <div style="background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);">
                <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Vice Principal" style="width: 100%; height: 250px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3 style="color: var(--text-main);">Mr. Sanjay Joshi</h3>
                    <p style="color: var(--primary); font-weight: 500;">Vice Principal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Segment -->
    <div style="background: var(--bg-color); padding: 3rem; border-radius: var(--radius); text-align: center;">
        <h2 style="font-size: 2rem; color: var(--primary-dark); margin-bottom: 1rem;">Our Dedicated Faculty</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 2rem;">Our teachers are highly qualified professionals committed to nurturing student potential across all disciplines.</p>
        <a href="#" class="btn btn-primary">View Full Faculty Directory</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
