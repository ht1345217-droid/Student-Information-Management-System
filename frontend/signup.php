<?php
session_start();
require_once '../config/db.php';

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Server side validation
    if(strlen($name) < 3 || strlen($name) > 50 || !preg_match("/^[A-Za-z\s]+$/", $name)) {
        $message = "Invalid name format. Name must be 3-50 characters containing only letters and spaces.";
        $messageType = "error";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $messageType = "error";
    } elseif(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/", $password)) {
        $message = "Password must be 8-20 characters, include 1 uppercase and 1 number.";
        $messageType = "error";
    } elseif($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $messageType = "error";
    } else {
        $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepared statements for security
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if($check_result && $check_result->num_rows > 0) {
            $message = "Email already registered.";
            $messageType = "error";
        } else {
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $insert_stmt->bind_param("sss", $name, $email, $hashed_pwd);
            if($insert_stmt->execute()) {
                $message = "Registration successful! You can now login.";
                $messageType = "success";
            } else {
                $message = "Error: Something went wrong. Please try again.";
                $messageType = "error";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - SIMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google reCAPTCHA mock inclusion -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            <h2>Create an Account</h2>
            <p style="color: var(--light-text);">Join the SIMS platform today.</p>
        </div>

        <?php if($message): ?>
            <div style="padding: 10px; border-radius: 4px; margin-bottom: 1rem; <?php echo $messageType == 'error' ? 'background:#f8d7da; color:#721c24;' : 'background:#d4edda; color:#155724;'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="John Doe">
                <div id="nameError" class="error-text"></div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="student@example.com">
                <div id="emailError" class="error-text"></div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Create a strong password">
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div id="passwordError" class="error-text"></div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-input-container">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirm your password">
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_password', this)" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div id="confirmPasswordError" class="error-text"></div>
            </div>
            
            <!-- Mock Google reCAPTCHA -->
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign Up</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem;">Already have an account? <a href="login.php">Log In</a></p>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
