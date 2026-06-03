<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch some basic stats
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE status='Paid'")->fetch_assoc()['total'];
$total_feedback = $conn->query("SELECT COUNT(*) as count FROM feedback")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIMS</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-graduation-cap"></i> SIMS Admin
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="students.php"><i class="fa-solid fa-users"></i> Students</a></li>
            <li><a href="courses.php"><i class="fa-solid fa-book-open"></i> Courses</a></li>
            <li><a href="transactions.php"><i class="fa-solid fa-money-bill-wave"></i> Transactions</a></li>
            <li><a href="../frontend/logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <h2>Dashboard Overview</h2>
            <div class="user-info">
                <i class="fa-solid fa-user-circle fa-2x"></i>
                <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <div class="dashboard-cards">
                <div class="stat-card">
                    <div>
                        <h3>Total Students</h3>
                        <h2><?php echo $total_students; ?></h2>
                    </div>
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-card">
                    <div>
                        <h3>Total Courses</h3>
                        <h2><?php echo $total_courses; ?></h2>
                    </div>
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="stat-card">
                    <div>
                        <h3>Total Revenue</h3>
                        <h2>$<?php echo number_format($total_revenue ?: 0, 2); ?></h2>
                    </div>
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="stat-card">
                    <div>
                        <h3>Feedback Messages</h3>
                        <h2><?php echo $total_feedback; ?></h2>
                    </div>
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>

            <!-- Recent Students Table -->
            <div class="table-container">
                <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Recently Added Students</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent = $conn->query("SELECT * FROM students ORDER BY id DESC LIMIT 5");
                        if($recent && $recent->num_rows > 0):
                            while($row = $recent->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr><td colspan="4" style="text-align:center;">No students found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
