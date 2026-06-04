<?php
require_once 'auth_check.php';

// Handle CSV exports first
if (isset($_GET['export'])) {
    $type = $_GET['export'];
    
    // Prevent CSRF by checking token in GET request for exports
    $csrf_token = $_GET['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf_token)) {
        die("Security verification failed. Invalid CSRF token.");
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sims_' . $type . '_report_' . date('Ymd') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    if ($type === 'students') {
        fputcsv($output, ['Student ID', 'Name', 'Email', 'Course', 'Date Joined']);
        $query = $conn->query("SELECT s.student_id, s.name, s.email, c.name as course_name, s.created_at FROM students s LEFT JOIN courses c ON s.course_id = c.id ORDER BY s.name ASC");
        while ($row = $query->fetch_assoc()) {
            fputcsv($output, [
                $row['student_id'],
                $row['name'],
                $row['email'],
                $row['course_name'] ?? 'Unassigned',
                $row['created_at']
            ]);
        }
    } elseif ($type === 'transactions') {
        fputcsv($output, ['Date', 'Student ID', 'Student Name', 'Amount', 'Status']);
        $query = $conn->query("SELECT t.date, s.student_id, s.name as student_name, t.amount, t.status FROM transactions t LEFT JOIN students s ON t.student_id = s.id ORDER BY t.date DESC");
        while ($row = $query->fetch_assoc()) {
            fputcsv($output, [
                $row['date'],
                $row['student_id'] ?? 'N/A',
                $row['student_name'] ?? 'Unknown',
                $row['amount'],
                $row['status']
            ]);
        }
    } elseif ($type === 'inventory') {
        fputcsv($output, ['Asset Name', 'Type', 'Quantity', 'Status', 'Last Updated']);
        $query = $conn->query("SELECT item_name, type, quantity, status, last_updated FROM inventory ORDER BY item_name ASC");
        while ($row = $query->fetch_assoc()) {
            fputcsv($output, [
                $row['item_name'],
                $row['type'],
                $row['quantity'],
                $row['status'],
                $row['last_updated']
            ]);
        }
    }
    fclose($output);
    exit();
}

// Fetch report stats
$stats_paid = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE status='Paid'")->fetch_assoc()['total'] ?? 0;
$stats_pending = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE status='Pending'")->fetch_assoc()['total'] ?? 0;
$stats_overdue = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE status='Overdue'")->fetch_assoc()['total'] ?? 0;

$active_page = 'reports';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .report-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .export-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: var(--radius);
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .export-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }
        .export-card i {
            color: var(--primary-color);
            margin-bottom: 0.75rem;
        }
        .progress-bar-container {
            width: 100%;
            background: #e9ecef;
            border-radius: 50px;
            overflow: hidden;
            height: 12px;
            margin-top: 0.5rem;
        }
        .progress-bar {
            height: 100%;
            background: var(--primary-color);
            border-radius: 50px;
        }
        @media print {
            body { background: white; color: black; }
            .sidebar, .topbar, .btn, .export-card, form { display: none !important; }
            .main-content { padding: 0; margin: 0; }
            .content-wrapper { padding: 0; }
            .report-section { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Reports & Analytics</h2>
            <div style="display:flex; gap: 1rem; align-items: center;">
                <button onclick="window.print()" class="action-btn btn-edit" style="background:#28a745;"><i class="fa-solid fa-print"></i> Print Report</button>
                <div class="user-info">
                    <span><?php echo xss_clean($_SESSION['name']); ?></span>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            
            <!-- Statistics Breakdown -->
            <div class="report-section">
                <h3>Financial Summary</h3>
                <div class="report-grid">
                    <div style="padding: 1rem; border-left: 4px solid #28a745; background: #f4fdf6; border-radius: 4px;">
                        <span style="color: #666; font-size: 0.9rem;">Collected (Paid)</span>
                        <h2 style="color: #28a745; margin-top: 0.25rem;">$<?php echo number_format($stats_paid, 2); ?></h2>
                    </div>
                    <div style="padding: 1rem; border-left: 4px solid #ffc107; background: #fffdf3; border-radius: 4px;">
                        <span style="color: #666; font-size: 0.9rem;">Pending (Outstanding)</span>
                        <h2 style="color: #d39e00; margin-top: 0.25rem;">$<?php echo number_format($stats_pending, 2); ?></h2>
                    </div>
                    <div style="padding: 1rem; border-left: 4px solid #dc3545; background: #fff5f6; border-radius: 4px;">
                        <span style="color: #666; font-size: 0.9rem;">Unpaid (Overdue)</span>
                        <h2 style="color: #dc3545; margin-top: 0.25rem;">$<?php echo number_format($stats_overdue, 2); ?></h2>
                    </div>
                </div>
            </div>

            <!-- Enrollment By Course Section -->
            <div class="report-section">
                <h3>Course Enrollment Distribution</h3>
                <div style="margin-top: 1.5rem;">
                    <?php
                    $courses_q = $conn->query("SELECT c.name, COUNT(s.id) as student_count FROM courses c LEFT JOIN students s ON s.course_id = c.id GROUP BY c.id");
                    $total_registered_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'] ?: 1;
                    
                    while ($c_row = $courses_q->fetch_assoc()):
                        $percentage = round(($c_row['student_count'] / $total_registered_students) * 100);
                    ?>
                    <div style="margin-bottom: 1.25rem;">
                        <div style="display:flex; justify-content:space-between; font-weight:500; font-size:0.95rem;">
                            <span><?php echo xss_clean($c_row['name']); ?></span>
                            <span><?php echo $c_row['student_count']; ?> student(s) (<?php echo $percentage; ?>%)</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo $percentage; ?>%; background-color: var(--primary-color);"></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Data Exports section -->
            <div class="report-section">
                <h3>Data Export Center</h3>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem;">Download raw system records in standardized CSV format for spreadsheet importing.</p>
                <div class="report-grid">
                    <div class="export-card">
                        <i class="fa-solid fa-users fa-3x"></i>
                        <h4 style="margin: 0.5rem 0;">Student Roster</h4>
                        <p style="color: #777; font-size: 0.85rem; margin-bottom: 1rem;">Complete student personal and enrollment records.</p>
                        <a href="?export=students&csrf_token=<?php echo generate_csrf_token(); ?>" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;">Export CSV</a>
                    </div>
                    <div class="export-card">
                        <i class="fa-solid fa-file-invoice-dollar fa-3x"></i>
                        <h4 style="margin: 0.5rem 0;">Financial Ledger</h4>
                        <p style="color: #777; font-size: 0.85rem; margin-bottom: 1rem;">Fee statements, collections, and outstanding bills.</p>
                        <a href="?export=transactions&csrf_token=<?php echo generate_csrf_token(); ?>" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;">Export CSV</a>
                    </div>
                    <div class="export-card">
                        <i class="fa-solid fa-warehouse fa-3x"></i>
                        <h4 style="margin: 0.5rem 0;">Asset Catalog</h4>
                        <p style="color: #777; font-size: 0.85rem; margin-bottom: 1rem;">Campus equipment lists, quantities, and status records.</p>
                        <a href="?export=inventory&csrf_token=<?php echo generate_csrf_token(); ?>" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;">Export CSV</a>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

</body>
</html>
