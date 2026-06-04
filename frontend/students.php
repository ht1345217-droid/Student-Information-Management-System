<?php
require_once '../config/db.php';
start_secure_session();


// Fetch courses
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students & Courses - SIMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .search-bar {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 3rem;
            display: flex;
        }
        .search-bar input {
            flex: 1;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: var(--radius) 0 0 var(--radius);
            font-size: 1rem;
            outline: none;
        }
        .search-bar button {
            padding: 1rem 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 var(--radius) var(--radius) 0;
            cursor: pointer;
        }
        .tab-btn {
            background: none; border: none; font-size: 1.1rem; font-weight: 600; color: var(--light-text);
            padding: 0.5rem 1rem; margin-right: 1rem; cursor: pointer; border-bottom: 3px solid transparent;
        }
        .tab-btn.active {
            color: var(--primary-color); border-bottom-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="students.php" class="active">Students & Courses</a></li>
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
        <h1>Directory</h1>
        <p>Explore our courses and outstanding student community.</p>
    </div>

    <section style="padding: 4rem 5%; min-height: 60vh;">
        
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search by name, department, or course...">
            <button><i class="fa-solid fa-search"></i></button>
        </div>

        <div style="text-align: center; margin-bottom: 2rem;">
            <button class="tab-btn active" onclick="showTab('coursesTab', this)">Courses</button>
            <button class="tab-btn" onclick="showTab('studentsTab', this)">Registered Students</button>
        </div>

        <div id="coursesTab" class="tab-content">
            <div class="grid-container" style="padding-top: 0;">
                <?php if($courses_result && $courses_result->num_rows > 0): ?>
                    <?php while($row = $courses_result->fetch_assoc()): ?>
                    <div class="card">
                        <i class="fa-solid fa-laptop-code fa-3x" style="color:var(--primary-color); margin-bottom:1rem;"></i>
                        <h3 style="font-size:1.2rem; margin-bottom:0.5rem;"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p style="color:var(--light-text); font-weight:500;">Dept: <?php echo htmlspecialchars($row['department']); ?></p>
                        <a href="signup.php" class="btn btn-outline" style="margin-top: 1.5rem;">Enroll Now</a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; width:100%;">No courses available.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="studentsTab" class="tab-content" style="display:none;">
            <div class="grid-container" style="padding-top: 0;">
                <?php 
                $stu_sql = "SELECT s.*, c.name as course_name FROM students s LEFT JOIN courses c ON s.course_id = c.id";
                $stu_result = $conn->query($stu_sql);
                if($stu_result && $stu_result->num_rows > 0):
                    while($row = $stu_result->fetch_assoc()):
                ?>
                <div class="card student-card">
                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=random'" alt="Student">
                    <h3 style="font-size:1.2rem; margin-bottom:0.25rem;"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p style="color:var(--primary-color); font-weight:bold; font-size:0.9rem;"><?php echo htmlspecialchars($row['student_id']); ?></p>
                    <p style="color:var(--light-text); font-size:0.9rem; margin-top:0.5rem;"><i class="fa-solid fa-book"></i> <?php echo htmlspecialchars($row['course_name'] ?? 'N/A'); ?></p>
                </div>
                <?php endwhile; else: ?>
                    <p style="text-align: center; width:100%;">No students registered yet.</p>
                <?php endif; ?>
            </div>
        </div>

    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <p>&copy; 2026 SIMS Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/script.js"></script>
    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            btn.classList.add('active');
        }
    </script>
</body>
</html>
