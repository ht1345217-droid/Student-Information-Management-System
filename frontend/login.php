<?php
require_once '../config/db.php';
start_secure_session();

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. CSRF Verification
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $error = "Security validation failed. Invalid CSRF token.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        
        // 2. Brute Force Check
        $block_time = 900; // 15 minutes in seconds
        $max_attempts = 5;
        $is_blocked = false;
        
        $attempt_stmt = $conn->prepare("SELECT attempts, UNIX_TIMESTAMP(last_attempt) as last_attempt_time FROM login_attempts WHERE ip_address = ?");
        $attempt_stmt->bind_param("s", $ip);
        $attempt_stmt->execute();
        $attempt_res = $attempt_stmt->get_result();
        
        if($attempt_res && $attempt_res->num_rows > 0) {
            $attempt_data = $attempt_res->fetch_assoc();
            $attempts = $attempt_data['attempts'];
            $last_attempt_time = $attempt_data['last_attempt_time'];
            $time_passed = time() - $last_attempt_time;
            
            if($attempts >= $max_attempts && $time_passed < $block_time) {
                $is_blocked = true;
                $remaining_time = ceil(($block_time - $time_passed) / 60);
                $error = "Too many login attempts. Your IP has been temporarily blocked. Please try again after {$remaining_time} minute(s).";
            }
        }
        $attempt_stmt->close();
        
        if(!$is_blocked) {
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address format.';
            } else {
                // Prepared statement to avoid SQL injection
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if(password_verify($password, $user['password'])) {
                        // Prevent Session Fixation by regenerating ID
                        session_regenerate_id(true);
                        
                        // Clear login attempts on success
                        $clear_attempts = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
                        $clear_attempts->bind_param("s", $ip);
                        $clear_attempts->execute();
                        $clear_attempts->close();
                        
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['email'] = $user['email'];
        
                        if($user['role'] == 'admin') {
                            header("Location: ../admin/dashboard.php");
                        } else {
                            header("Location: students.php");
                        }
                        exit();
                    } else {
                        $error = 'Invalid email or password.';
                    }
                } else {
                    $error = 'Invalid email or password.';
                }
                $stmt->close();
                
                // Track failed attempt
                if(!empty($ip)) {
                    $log_attempt = $conn->prepare("INSERT INTO login_attempts (ip_address, attempts) VALUES (?, 1) ON DUPLICATE KEY UPDATE attempts = IF(UNIX_TIMESTAMP(last_attempt) < UNIX_TIMESTAMP() - ?, 1, attempts + 1)");
                    $log_attempt->bind_param("si", $ip, $block_time);
                    $log_attempt->execute();
                    $log_attempt->close();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <div class="nav-actions">
            <a href="index.php" class="btn btn-outline">Back to Home</a>
        </div>
    </nav>

    <div class="form-container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2>Welcome Back</h2>
            <p style="color: var(--light-text);">Please sign in to continue.</p>
        </div>

        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
                <?php echo xss_clean($error); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="admin@sims.com">
                <div id="emailError" class="error-text"></div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="password">
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign In</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem;">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
