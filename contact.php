<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div style="background: var(--primary-dark); padding: 4rem 0; text-align: center; color: white;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.2rem; color: #cbd5e1;">We'd love to hear from you. Get in touch with our team.</p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem; display: grid; grid-template-columns: 1fr 2fr; gap: 4rem;">

    <!-- Contact Information -->
    <div>
        <h2 style="font-size: 2rem; color: var(--primary-dark); margin-bottom: 2rem;">Get In Touch</h2>
        
        <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: var(--bg-color); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary);">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">Our Location</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">123 Education Lane<br>Kathmandu, Nepal 44600</p>
            </div>
        </div>

        <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: var(--bg-color); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--secondary);">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">Phone Numbers</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">Office: +977 1-2345678<br>Principal: +977 9801234567</p>
            </div>
        </div>

        <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: var(--bg-color); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--accent);">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
                <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">Email Address</h3>
                <p style="color: var(--text-muted); line-height: 1.6;">info@everestschool.edu.np<br>admissions@everestschool.edu.np</p>
            </div>
        </div>
        
        <!-- Social Media Links -->
        <div style="margin-top: 3rem;">
            <h3 style="color: var(--text-main); margin-bottom: 1rem;">Follow Us</h3>
            <div style="display: flex; gap: 1rem;">
                <a href="#" style="width: 40px; height: 40px; background: #3b5998; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" style="width: 40px; height: 40px; background: #1da1f2; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" style="width: 40px; height: 40px; background: #e1306c; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none;"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- Contact Form & Map -->
    <div>
        <div style="background: white; padding: 3rem; border-radius: var(--radius); box-shadow: var(--shadow-lg); margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; color: var(--primary-dark); margin-bottom: 1.5rem;">Send Us a Message</h2>
            <form action="api/submit_contact.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name</label>
                        <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
                        <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Subject</label>
                    <input type="text" name="subject" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Your Message</label>
                    <textarea name="message" rows="5" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; resize: vertical;"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">Send Message</button>
            </form>
        </div>

        <!-- Google Map embed -->
        <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); height: 350px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113032.64621396825!2d85.2504897120689!3d27.70895425227181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb198a307baabf%3A0xb5137c1bf18db1ea!2sKathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2sus!4v1709400000000!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
