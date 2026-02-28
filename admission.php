<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div style="background: var(--primary-dark); padding: 4rem 0; text-align: center; color: white;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Admissions</h1>
        <p style="font-size: 1.2rem; color: #cbd5e1;">Join the Everest family for the 2026 academic year</p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem; display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
    
    <!-- Left Column: Info & Form -->
    <div>
        <h2 style="font-size: 2rem; color: var(--primary-dark); margin-bottom: 1.5rem;">Admission Criteria</h2>
        <p style="color: var(--text-muted); margin-bottom: 1rem;">Admission is granted on the basis of a written entrance examination followed by an interview. For +2 programs, the SEE scores are strictly evaluated.</p>
        
        <h3 style="font-size: 1.25rem; margin: 2rem 0 1rem; color: var(--text-main);">Required Documents:</h3>
        <ul style="list-style: none; color: var(--text-muted); margin-bottom: 2rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-file-circle-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Completed Admission Form</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-file-circle-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Copy of Birth Certificate</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-file-circle-check" style="color: var(--accent); margin-right: 0.5rem;"></i> Previous Year's Marksheet (for Class 2 and above)</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-file-circle-check" style="color: var(--accent); margin-right: 0.5rem;"></i> 2 Passport size photographs</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-file-circle-check" style="color: var(--accent); margin-right: 0.5rem;"></i> SEE Grade Sheet & Character Certificate (for +2 Admissions)</li>
        </ul>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 3rem 0;">

        <!-- Online Admission Form -->
        <h2 style="font-size: 2rem; color: var(--primary-dark); margin-bottom: 1.5rem;" id="apply">Online Application Inquiry</h2>
        <div style="background: white; padding: 2.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
            <form action="api/submit_inquiry.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Student's Name</label>
                        <input type="text" name="student_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Parent/Guardian's Name</label>
                        <input type="text" name="parent_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
                        <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Phone Number</label>
                        <input type="tel" name="phone" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Applying For Class/Program</label>
                    <select name="class_applied" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; background: white;">
                        <option value="">Select Class</option>
                        <option value="Class 1">Class 1</option>
                        <option value="Class 2">Class 2</option>
                        <option value="Class 5">Class 5</option>
                        <option value="Class 8">Class 8</option>
                        <option value="Class 10">Class 10</option>
                        <option value="+2 Science">+2 Computer Science</option>
                        <option value="+2 Mgmt">+2 Hotel Management</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Submit Inquiry</button>
            </form>
        </div>
    </div>

    <!-- Right Column: Sidebar -->
    <div>
        <!-- Fee Structure Box -->
        <div style="background: var(--bg-color); border: 1px solid #e2e8f0; border-radius: var(--radius); padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: var(--primary-dark); margin-bottom: 1rem;"><i class="fa-solid fa-money-bill-wave" style="margin-right: 0.5rem;"></i> Fee Structure</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;">Fees vary based on the class and stream chosen. Please download our detailed fee structure PDF for exact figures.</p>
            <a href="#" class="btn btn-secondary" style="display: block;"><i class="fa-solid fa-download"></i> Download Fee Info</a>
        </div>
        
        <!-- Scholarship Box -->
        <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: var(--radius); padding: 2rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fa-solid fa-award" style="margin-right: 0.5rem;"></i> Scholarships</h3>
            <p style="font-size: 0.95rem; margin-bottom: 1rem; color: #e2e8f0;">We offer merit-based and need-based scholarships for deserving students. Special scholarships available for SEE rank holders entering our +2 programs.</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem; font-size: 0.9rem; color: #e2e8f0;">
                <li>Academic Excellence Award</li>
                <li>Sports Quota</li>
                <li>Underprivileged Student Grant</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
