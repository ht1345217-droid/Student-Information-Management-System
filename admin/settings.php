<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Fetch current settings
$settings_res = $conn->query("SELECT * FROM settings");
$settings = [];
if ($settings_res) {
    while ($row = $settings_res->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

// Handle Update Settings
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $system_name = trim($_POST['system_name'] ?? 'SIMS Portal');
        $allow_registration = isset($_POST['allow_registration']) ? '1' : '0';
        $max_login_attempts = intval($_POST['max_login_attempts'] ?? 5);
        
        if (empty($system_name) || $max_login_attempts <= 0) {
            $message = "System Name is required and Max Login Attempts must be positive.";
            $messageType = "error";
        } else {
            // Prepared statement to update setting keys
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            
            // 1. System name
            $key = 'system_name';
            $stmt->bind_param("ss", $system_name, $key);
            $stmt->execute();
            
            // 2. Allow registration
            $key = 'allow_registration';
            $stmt->bind_param("ss", $allow_registration, $key);
            $stmt->execute();
            
            // 3. Max login attempts
            $key = 'max_login_attempts';
            $max_str = (string)$max_login_attempts;
            $stmt->bind_param("ss", $max_str, $key);
            $stmt->execute();
            
            $stmt->close();
            
            // Update local variable list
            $settings['system_name'] = $system_name;
            $settings['allow_registration'] = $allow_registration;
            $settings['max_login_attempts'] = $max_str;
            
            $message = "System settings updated successfully.";
            $messageType = "success";
        }
    }
}

$active_page = 'settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>System Settings</h2>
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

            <div style="max-width: 600px; background: var(--white); padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow); margin: 0 auto;">
                <h3>Configuration Options</h3>
                <form method="POST" action="" style="margin-top: 1.5rem;">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="update_settings" value="1">
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">System/Portal Name</label>
                        <input type="text" name="system_name" required value="<?php echo xss_clean($settings['system_name'] ?? 'SIMS Portal'); ?>" style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Max Failed Login Attempts</label>
                        <input type="number" min="1" name="max_login_attempts" required value="<?php echo xss_clean($settings['max_login_attempts'] ?? '5'); ?>" style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px;">
                        <span style="font-size:0.75rem; color:#888;">Number of retries before IP address is temporarily blocked for 15 minutes.</span>
                    </div>

                    <div style="margin-bottom: 2rem; display:flex; align-items:center; gap: 0.5rem;">
                        <input type="checkbox" name="allow_registration" id="allow_registration" value="1" <?php echo ($settings['allow_registration'] ?? '1') === '1' ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="allow_registration" style="font-weight:500; cursor: pointer; user-select:none;">Allow Student Self-Registration</label>
                    </div>

                    <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 600;">Save Configuration</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
