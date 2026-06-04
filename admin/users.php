<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Handle Delete User
if (isset($_GET['delete'])) {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $delete_id = intval($_GET['delete']);
        
        // Prevent deleting currently logged-in admin
        if ($delete_id === $_SESSION['user_id']) {
            $message = "You cannot delete your own admin account.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                $message = "User deleted successfully.";
                $messageType = "success";
            } else {
                $message = "Error deleting user.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

// Handle Change Role
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_role'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $user_id = intval($_POST['user_id'] ?? 0);
        $role = trim($_POST['role'] ?? '');
        
        if ($user_id > 0 && in_array($role, ['admin', 'student'])) {
            if ($user_id === $_SESSION['user_id'] && $role !== 'admin') {
                $message = "You cannot demote yourself from the admin role.";
                $messageType = "error";
            } else {
                $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
                $stmt->bind_param("si", $role, $user_id);
                if ($stmt->execute()) {
                    $message = "User role updated successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error updating role.";
                    $messageType = "error";
                }
                $stmt->close();
            }
        }
    }
}

$active_page = 'users';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>User Management</h2>
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

            <div class="table-container" style="background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                <h3>System Users</h3>
                <div style="overflow-x: auto; margin-top: 1rem;">
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users_res = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                            while($row = $users_res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo xss_clean($row['name']); ?></td>
                                <td><?php echo xss_clean($row['username']); ?></td>
                                <td><?php echo xss_clean($row['email']); ?></td>
                                <td><?php echo xss_clean($row['country'] ?: 'N/A'); ?></td>
                                <td><?php echo xss_clean($row['gender'] ?: 'N/A'); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['role'] == 'admin' ? 'badge-danger' : 'badge-success'; ?>">
                                        <?php echo xss_clean($row['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <!-- Form to change role -->
                                        <form method="POST" action="" style="margin:0; padding:0; display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="change_role" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <select name="role" onchange="this.form.submit()" style="padding: 0.25rem; font-size: 0.85rem; border-radius:4px; border:1px solid #ddd;">
                                                <option value="student" <?php echo $row['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                                                <option value="admin" <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </form>
                                        
                                        <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                                            <a href="?delete=<?php echo $row['id']; ?>&csrf_token=<?php echo generate_csrf_token(); ?>" class="action-btn btn-delete" style="padding:0.25rem 0.5rem;" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
