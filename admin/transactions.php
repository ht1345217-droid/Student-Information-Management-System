<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Handle Add Transaction
$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_trans'])) {
    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $sql = "INSERT INTO transactions (student_id, amount, status) VALUES ($student_id, $amount, '$status')";
    if($conn->query($sql)) {
        $message = "Transaction added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-graduation-cap"></i> SIMS Admin</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="students.php"><i class="fa-solid fa-users"></i> Students</a></li>
            <li><a href="courses.php"><i class="fa-solid fa-book-open"></i> Courses</a></li>
            <li><a href="transactions.php" class="active"><i class="fa-solid fa-money-bill-wave"></i> Transactions</a></li>
            <li><a href="../frontend/logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="topbar">
            <h2>Transactions</h2>
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
                    <h3>Add Record</h3>
                    <form method="POST" action="" style="margin-top: 1rem;">
                        <input type="hidden" name="add_trans" value="1">
                        <div style="margin-bottom: 1rem;">
                            <label>Student</label>
                            <select name="student_id" required style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                                <?php
                                $s_res = $conn->query("SELECT id, name, student_id FROM students");
                                while($s = $s_res->fetch_assoc()) {
                                    echo "<option value='".$s['id']."'>".$s['name']." (".$s['student_id'].")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Amount ($)</label>
                            <input type="number" step="0.01" name="amount" required style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Status</label>
                            <select name="status" required style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Overdue">Overdue</option>
                            </select>
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Add Transaction</button>
                    </form>
                </div>

                <div style="flex: 2; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Transaction History</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Date</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Student</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Amount</th>
                                    <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $t_res = $conn->query("SELECT t.*, s.name as student_name FROM transactions t LEFT JOIN students s ON t.student_id = s.id ORDER BY t.date DESC");
                                while($row = $t_res->fetch_assoc()):
                                    $badge_class = 'badge-success';
                                    if($row['status'] == 'Pending') $badge_class = 'badge-warning';
                                    if($row['status'] == 'Overdue') $badge_class = 'badge-danger';
                                ?>
                                <tr>
                                    <td style="padding:10px; border-bottom:1px solid #eee;"><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;">$<?php echo number_format($row['amount'], 2); ?></td>
                                    <td style="padding:10px; border-bottom:1px solid #eee;">
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
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
