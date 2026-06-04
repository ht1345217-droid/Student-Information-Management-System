<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Handle Add Transaction
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_trans'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $student_id = intval($_POST['student_id'] ?? 0);
        $amount = floatval($_POST['amount'] ?? 0.0);
        $status = trim($_POST['status'] ?? '');
        
        if ($student_id <= 0 || $amount <= 0 || empty($status)) {
            $message = "All fields are required and amount must be positive.";
            $messageType = "error";
        } else {
            $sql = "INSERT INTO transactions (student_id, amount, status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ids", $student_id, $amount, $status);
            if($stmt->execute()) {
                $message = "Transaction added successfully.";
                $messageType = "success";
            } else {
                $message = "Error recording transaction. Please try again.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

$active_page = 'transactions';
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

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Transactions</h2>
            <div class="user-info">
                <span><?php echo xss_clean($_SESSION['name']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($message): ?>
                <div style="background: <?php echo $messageType == 'error' ? '#f8d7da; color: #721c24;' : '#d4edda; color: #155724;'; ?> padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
                    <?php echo xss_clean($message); ?>
                </div>
            <?php endif; ?>

            <div style="display:flex; gap:2rem; flex-wrap: wrap;">
                <!-- Add Transaction Form -->
                <div style="flex: 1; min-width: 300px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Add Record</h3>
                    <form method="POST" action="" style="margin-top: 1rem;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="add_trans" value="1">
                        
                        <div style="margin-bottom: 1rem;">
                            <label>Student</label>
                            <select name="student_id" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                                <option value="">Select Student</option>
                                <?php
                                $s_res = $conn->query("SELECT id, name, student_id FROM students ORDER BY name ASC");
                                while($s = $s_res->fetch_assoc()) {
                                    echo "<option value='".$s['id']."'>".xss_clean($s['name'])." (".xss_clean($s['student_id']).")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Amount ($)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Status</label>
                            <select name="status" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Overdue">Overdue</option>
                            </select>
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Add Transaction</button>
                    </form>
                </div>

                <!-- Transaction List -->
                <div style="flex: 2; min-width: 500px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Transaction History</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Status</th>
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
                                    <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                    <td><?php echo xss_clean($row['student_name'] ?? 'Unknown'); ?></td>
                                    <td>$<?php echo number_format($row['amount'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo xss_clean($row['status']); ?></span>
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
