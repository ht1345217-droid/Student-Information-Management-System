<?php
require_once '../config/db.php';
start_secure_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SIMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="students.php">Students & Courses</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-greeting"><i class="fa-solid fa-user-circle"></i> Hello, <?php echo xss_clean($_SESSION['name']); ?></span>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="../admin/dashboard.php" class="btn btn-outline">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="page-header">
        <h1>Contact Us</h1>
        <p>Get in touch with our support team.</p>
    </div>

    <section style="padding: 4rem 5%; max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; gap: 3rem;">
        
        <div style="flex: 1; min-width: 300px;">
            <h2>Send us a message</h2>
            <form action="feedback.php" method="POST" style="margin-top: 1.5rem;" class="form-container" style="margin: 0; padding: 0; box-shadow: none;">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <p style="margin-bottom: 1rem; color: var(--light-text);">Want to leave official feedback? <a href="feedback.php">Use our feedback form</a>.</p>
                <div class="form-group">
                    <label for="c_name">Full Name</label>
                    <input type="text" id="c_name" name="c_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="c_email">Email Address</label>
                    <input type="email" id="c_email" name="c_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="c_msg">Message</label>
                    <textarea id="c_msg" name="c_msg" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h2>Our Location</h2>
            <p style="margin-top: 1.5rem; color: #555;"><i class="fa-solid fa-location-dot" style="color:var(--primary-color);"></i> 123 University Ave, Tech City, TX 75001</p>
            <p style="margin-top: 0.5rem; color: #555;"><i class="fa-solid fa-phone" style="color:var(--primary-color);"></i> +1 (800) 123-4567</p>
            <p style="margin-top: 0.5rem; color: #555;"><i class="fa-solid fa-envelope" style="color:var(--primary-color);"></i> support@sims.edu</p>
            
            <div style="margin-top: 2rem; border-radius: var(--radius); overflow: hidden; height: 300px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3345.961025531981!2d-96.75389658481223!3d32.99026418090729!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c21ff895e4d29%3A0xe54d80d28b17b6a4!2sThe%20University%20of%20Texas%20at%20Dallas!5e0!3m2!1sen!2sus!4v1689620000000!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </section>

    <footer style="margin-top:auto;">
        <div class="footer-bottom">
            <p>&copy; 2026 SIMS Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
