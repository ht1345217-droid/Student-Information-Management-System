<?php
require_once 'auth_check.php';

// Fetch some basic stats
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE status='Paid'")->fetch_assoc()['total'];
$total_feedback = $conn->query("SELECT COUNT(*) as count FROM feedback")->fetch_assoc()['count'];
$total_categories = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$total_inventory = $conn->query("SELECT SUM(quantity) as total FROM inventory")->fetch_assoc()['total'];

$active_page = 'dashboard';
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
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <h2>Dashboard Overview</h2>
            <div class="user-info">
                <i class="fa-solid fa-user-circle fa-2x" style="color: var(--primary-color);"></i>
                <span><?php echo xss_clean($_SESSION['name']); ?></span>
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
                <div class="stat-card">
                    <div>
                        <h3>Course Categories</h3>
                        <h2><?php echo $total_categories; ?></h2>
                    </div>
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div class="stat-card">
                    <div>
                        <h3>Inventory Stock</h3>
                        <h2><?php echo number_format($total_inventory ?: 0); ?></h2>
                    </div>
                    <i class="fa-solid fa-boxes-stacked"></i>
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
                            <td><?php echo xss_clean($row['student_id']); ?></td>
                            <td><?php echo xss_clean($row['name']); ?></td>
                            <td><?php echo xss_clean($row['email']); ?></td>
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
