<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div style="background: var(--primary-dark); padding: 4rem 0; text-align: center; color: white;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Academic Programs</h1>
        <p style="font-size: 1.2rem; color: #cbd5e1;">Discover our comprehensive curriculum from Class 1 to +2 Programs</p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">

    <!-- Foundational & Secondary Education -->
    <div style="margin-bottom: 5rem; text-align: center;">
        <h2 style="font-size: 2.5rem; color: var(--primary-dark); margin-bottom: 2rem;">Foundational & Secondary Education</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Primary -->
            <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow); border-top: 5px solid var(--accent);">
                <i class="fa-solid fa-child-reaching fa-3x" style="color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 1rem;">Class 1 to 5</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">Our primary section focuses on building strong foundational skills in literacy, numeracy, and environmental awareness through activity-based learning.</p>
            </div>
            <!-- Middle -->
            <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow); border-top: 5px solid var(--secondary);">
                <i class="fa-solid fa-users fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 1rem;">Class 6 to 8</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">The middle school curriculum expands students' horizons with specialized subjects, integrated projects, and comprehensive personality development programs.</p>
            </div>
            <!-- High School -->
            <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow); border-top: 5px solid var(--primary);">
                <i class="fa-solid fa-book-open-reader fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 1rem;">Class 9 to 10</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">Preparing for the SEE board exams. We offer rigorous academic training coupled with practical lab works in Science and Computer.</p>
            </div>
        </div>
    </div>

    <!-- +2 Programs -->
    <div style="margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; color: var(--primary-dark); text-align: center; margin-bottom: 3rem;">+2 Programs (Class 11 & 12)</h2>
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 3rem;">
            <!-- Computer Science -->
            <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-lg); overflow: hidden; display: flex; flex-wrap: wrap;">
                <div style="flex: 1 1 400px;">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Computer Lab" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="flex: 1 1 400px; padding: 3rem;">
                    <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;">Computer Science</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.8;">
                        Designed for tech-enthusiasts, this stream covers programming, database management, web technologies, and networking.
                    </p>
                    <ul style="list-style: none; color: var(--text-main); margin-bottom: 1.5rem;">
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Advanced Computer Labs</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Dedicated Project Works</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Industry-expert Faculty</li>
                    </ul>
                    <a href="#" class="btn btn-secondary">Download Syllabus (PDF)</a>
                </div>
            </div>

            <!-- Hotel Management -->
            <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-lg); overflow: hidden; display: flex; flex-wrap: wrap; flex-direction: row-reverse;">
                <div style="flex: 1 1 400px;">
                    <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Kitchen Lab" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="flex: 1 1 400px; padding: 3rem;">
                    <h3 style="font-size: 2rem; color: var(--secondary); margin-bottom: 1rem;">Hotel Management</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.8;">
                        Preparing future hospitality leaders. This course blends theoretical knowledge with robust practical training in modern kitchens.
                    </p>
                    <ul style="list-style: none; color: var(--text-main); margin-bottom: 1.5rem;">
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Professional Training Kitchens</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> F&B Service Training</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Internship Opportunities in 5-star Hotels</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Download Syllabus (PDF)</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Resources -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
        <div style="background: var(--bg-color); padding: 2rem; border-radius: var(--radius); text-align: center; border: 1px dashed var(--primary);">
            <i class="fa-solid fa-calendar-days fa-2x" style="color: var(--primary); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 1rem;">Academic Calendar</h3>
            <a href="#" class="btn btn-primary btn-sm">View Calendar</a>
        </div>
        <div style="background: var(--bg-color); padding: 2rem; border-radius: var(--radius); text-align: center; border: 1px dashed var(--secondary);">
            <i class="fa-solid fa-clock fa-2x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 1rem;">Class Timetables</h3>
            <a href="#" class="btn btn-secondary btn-sm">Download Routine</a>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
