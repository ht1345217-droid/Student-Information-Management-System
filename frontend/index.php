<?php
require_once '../config/db.php';
start_secure_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS - Student Information Management System</title>
    <meta name="description" content="A complete portal for student and course management.">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="students.php">Students & Courses</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-greeting"><i class="fa-solid fa-user-circle"></i> Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Empowering Education Through <span>Smart Management</span></h1>
            <p>A seamless, intuitive portal for managing student data, course enrollment, and university resources all in one place. Built for speed and accessibility.</p>
            <a href="students.php" class="btn btn-primary" style="margin-right: 10px;">Browse Courses</a>
            <a href="about.php" class="btn btn-outline">Learn More</a>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80" alt="Students on campus">
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 5rem 5%; text-align: center; background: white;">
        <h2 style="font-size: 2.5rem; margin-bottom: 3rem;">Why Choose SIMS?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div class="card" style="box-shadow: none; border: 1px solid #eee;">
                <i class="fa-solid fa-users fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3>Student Profiles</h3>
                <p>Manage and track student progress, attendance, and details seamlessly.</p>
            </div>
            <div class="card" style="box-shadow: none; border: 1px solid #eee;">
                <i class="fa-solid fa-book-open fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3>Course Management</h3>
                <p>Easily enroll in courses and track available subjects across departments.</p>
            </div>
            <div class="card" style="box-shadow: none; border: 1px solid #eee;">
                <i class="fa-solid fa-chart-pie fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h3>Interactive Dashboard</h3>
                <p>Get real-time insights with a comprehensive administrator dashboard.</p>
            </div>
        </div>
    </section>

    <!-- Chatbot UI -->
    <button class="chatbot-toggler">
        <i class="fa-solid fa-comment-dots"></i>
    </button>
    <div class="chatbot">
        <header>
            <h2>SIMS Support</h2>
        </header>
        <ul class="chatbox">
            <li class="chat incoming">
                <i class="fa-solid fa-robot" style="align-self:flex-end; color:var(--primary-color); margin-right:5px;"></i>
                <p>Hi there! How can I help you today?</p>
            </li>
        </ul>
        <div class="chat-input">
            <input type="text" placeholder="Type a message..." required>
            <button id="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div>
                <h3 style="margin-bottom: 1rem;"><i class="fa-solid fa-graduation-cap"></i> SIMS</h3>
                <p style="color: var(--light-text);">Providing top-tier educational management solutions globally.</p>
            </div>
            <div>
                <h4 style="margin-bottom: 1rem;">Quick Links</h4>
                <ul style="list-style: none; line-height: 2;">
                    <li><a href="about.php" style="color: var(--light-text);">About Us</a></li>
                    <li><a href="students.php" style="color: var(--light-text);">Courses</a></li>
                    <li><a href="feedback.php" style="color: var(--light-text);">Feedback</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 1rem;">Contact</h4>
                <p style="color: var(--light-text);"><i class="fa-solid fa-envelope"></i> info@sims.edu</p>
                <p style="color: var(--light-text);"><i class="fa-solid fa-phone"></i> +1 234 567 890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SIMS Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
