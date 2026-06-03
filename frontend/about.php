<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SIMS</title>
    <meta name="description" content="Learn more about the SIMS Student Information Management System.">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About</a></li>
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

    <div class="page-header">
        <h1>About SIMS</h1>
        <p>Your centralized hub for academic excellence.</p>
    </div>

    <section style="padding: 5rem 5%; max-width: 1000px; margin: auto;">
        <div style="display: flex; gap: 3rem; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80" alt="University Campus" style="width: 100%; border-radius: var(--radius); box-shadow: var(--shadow);">
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h2 style="font-size: 2rem; margin-bottom: 1rem;">Our Mission</h2>
                <p style="margin-bottom: 1rem; color: #555;">At SIMS, our mission is to streamline administrative workflows and empower students by providing a transparent, easy-to-use, and highly secure web portal.</p>
                <p style="color: #555;">We believe in harnessing technology to bring institutions and learners closer together, focusing on modern web standards and accessibility for all.</p>
            </div>
        </div>

        <div style="margin-top: 5rem; text-align: center;">
            <h2>Gallery</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
                <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=400&q=80" alt="Study group" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=400&q=80" alt="Writing" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=400&q=80" alt="Graduation" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=400&q=80" alt="Campus life" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="margin-top:0;">
        <div class="footer-bottom">
            <p>&copy; 2026 SIMS Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
