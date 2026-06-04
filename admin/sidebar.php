<?php
// admin/sidebar.php
// Expected variable: $active_page (e.g. 'dashboard', 'users', 'students', 'courses', 'categories', 'inventory', 'transactions', 'reports', 'profile', 'settings')
if (!isset($active_page)) {
    $active_page = '';
}
?>
<nav class="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-graduation-cap"></i> SIMS Admin
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="<?php echo $active_page === 'dashboard' ? 'active' : ''; ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
        <li><a href="users.php" class="<?php echo $active_page === 'users' ? 'active' : ''; ?>"><i class="fa-solid fa-user-gear"></i> User Management</a></li>
        <li><a href="students.php" class="<?php echo $active_page === 'students' ? 'active' : ''; ?>"><i class="fa-solid fa-users"></i> Students</a></li>
        <li><a href="courses.php" class="<?php echo $active_page === 'courses' ? 'active' : ''; ?>"><i class="fa-solid fa-book-open"></i> Courses</a></li>
        <li><a href="categories.php" class="<?php echo $active_page === 'categories' ? 'active' : ''; ?>"><i class="fa-solid fa-tags"></i> Categories</a></li>
        <li><a href="inventory.php" class="<?php echo $active_page === 'inventory' ? 'active' : ''; ?>"><i class="fa-solid fa-boxes-stacked"></i> Inventory</a></li>
        <li><a href="transactions.php" class="<?php echo $active_page === 'transactions' ? 'active' : ''; ?>"><i class="fa-solid fa-money-bill-wave"></i> Transactions</a></li>
        <li><a href="reports.php" class="<?php echo $active_page === 'reports' ? 'active' : ''; ?>"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
        <li><a href="profile.php" class="<?php echo $active_page === 'profile' ? 'active' : ''; ?>"><i class="fa-solid fa-user-circle"></i> Profile</a></li>
        <li><a href="settings.php" class="<?php echo $active_page === 'settings' ? 'active' : ''; ?>"><i class="fa-solid fa-gears"></i> Settings</a></li>
        <li><a href="../frontend/logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
