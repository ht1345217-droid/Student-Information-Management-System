<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

$message = '';
// Handle Add Course
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $department = $conn->real_escape_string($_POST['department']);
    
    $sql = "INSERT INTO courses (name, department) VALUES ('$name', '$department')";
    if($conn->query($sql)) {
        $message = "Course added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Handle Delete Course
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM courses WHERE id=$id");
    $message = "Course deleted successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-graduation-cap"></i> SIMS Admin</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="students.php"><i class="fa-solid fa-users"></i> Students</a></li>
            <li><a href="courses.php" class="active"><i class="fa-solid fa-book-open"></i> Courses</a></li>
            <li><a href="transactions.php"><i class="fa-solid fa-money-bill-wave"></i> Transactions</a></li>
            <li><a href="../frontend/logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="topbar">
            <h2>Manage Courses</h2>
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($message): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div style="display:flex; gap:2rem;">
                <div style="flex: 1; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Add New Course</h3>
                    <form method="POST" action="" style="margin-top: 1rem;">
                        <input type="hidden" name="add_course" value="1">
                        <div style="margin-bottom: 1rem;">
                            <label>Course Name</label>
                            <input type="text" name="name" required style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Department</label>
                            <input type="text" name="department" required style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Add Course</button>
                    </form>
                </div>

                <div style="flex: 2; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Course List</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">ID</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Name</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Department</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c_res = $conn->query("SELECT * FROM courses");
                                while($row = $c_res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td style="padding:10px; border-bottom:1px solid #eee;"><?php echo $row['id']; ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;">
                                        <a href="?delete=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
