<?php
require_once '../config/db.php';
start_secure_session();

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. CSRF Verification
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security validation failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $country = trim($_POST['country'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        
        // 2. Reserved Username check (case-insensitive)
        $reserved_usernames = ['admin', 'administrator', 'root', 'superadmin', 'system'];
        
        // 3. Server-side validations
        if(strlen($name) < 3 || strlen($name) > 50 || !preg_match("/^[A-Za-z\s]+$/", $name)) {
            $message = "Invalid name format. Only alphabets and spaces allowed, 3 to 50 characters.";
            $messageType = "error";
        } elseif(in_array(strtolower($username), $reserved_usernames)) {
            $message = "This username is reserved. Please choose another username.";
            $messageType = "error";
        } elseif(!preg_match("/^[A-Za-z][A-Za-z0-9]*$/", $username)) {
            $message = "Username must start with an alphabet, contain no special characters, and can only include letters and numbers.";
            $messageType = "error";
        } elseif(empty($country)) {
            $message = "Please select your country. Country selection is mandatory.";
            $messageType = "error";
        } elseif(!in_array($gender, ['Male', 'Female', 'Other'])) {
            $message = "Please select a valid gender. Gender selection is mandatory.";
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
            // Check username uniqueness
            $user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $user_stmt->bind_param("s", $username);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            
            // Check email uniqueness
            $email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $email_stmt->bind_param("s", $email);
            $email_stmt->execute();
            $email_result = $email_stmt->get_result();
            
            if($user_result && $user_result->num_rows > 0) {
                $message = "Username is already taken. Please choose another one.";
                $messageType = "error";
            } elseif($email_result && $email_result->num_rows > 0) {
                $message = "Email already registered. Duplicate email accounts are not allowed.";
                $messageType = "error";
            } else {
                $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into users using prepared statement
                $insert_stmt = $conn->prepare("INSERT INTO users (name, username, email, password, country, gender, role) VALUES (?, ?, ?, ?, ?, ?, 'student')");
                $insert_stmt->bind_param("ssssss", $name, $username, $email, $hashed_pwd, $country, $gender);
                
                if($insert_stmt->execute()) {
                    // Automatically add the user to students directory table as well
                    $student_id = "STU-" . rand(1000, 9999);
                    
                    // Check if student_id is unique
                    $stu_check = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
                    $stu_check->bind_param("s", $student_id);
                    $stu_check->execute();
                    if($stu_check->get_result()->num_rows > 0) {
                        $student_id = "STU-" . rand(1000, 9999);
                    }
                    $stu_check->close();
                    
                    $insert_student = $conn->prepare("INSERT INTO students (student_id, name, email, image) VALUES (?, ?, ?, 'default.png')");
                    $insert_student->bind_param("sss", $student_id, $name, $email);
                    $insert_student->execute();
                    $insert_student->close();
                    
                    $message = "Registration successful! You can now login.";
                    $messageType = "success";
                } else {
                    $message = "An error occurred during registration. Please try again.";
                    $messageType = "error";
                }
                $insert_stmt->close();
            }
            $user_stmt->close();
            $email_stmt->close();
        }
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <div class="nav-actions">
            <a href="index.php" class="btn btn-outline">Back to Home</a>
        </div>
    </nav>

    <div class="form-container" style="max-width: 600px; margin: 2rem auto;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <h2>Create an Account</h2>
            <p style="color: var(--light-text);">Join the SIMS platform today.</p>
        </div>

        <?php if($message): ?>
            <div style="padding: 10px; border-radius: 4px; margin-bottom: 1rem; <?php echo $messageType == 'error' ? 'background:#f8d7da; color:#721c24;' : 'background:#d4edda; color:#155724;'; ?>">
                <?php echo xss_clean($message); ?>
            </div>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="John Doe">
                <div id="nameError" class="error-text"></div>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="JohnDoe123">
                <div id="usernameError" class="error-text"></div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="student@example.com">
                <div id="emailError" class="error-text"></div>
            </div>
            
            <div class="form-group" style="display: flex; gap: 1rem;">
                <div style="flex: 1;">
                    <label for="country">Country</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="">Select Country</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Canada">Canada</option>
                        <option value="Australia">Australia</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="India">India</option>
                        <option value="Germany">Germany</option>
                        <option value="France">France</option>
                        <option value="China">China</option>
                        <option value="Japan">Japan</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="Other">Other</option>
                    </select>
                    <div id="countryError" class="error-text"></div>
                </div>
                <div style="flex: 1;">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div id="genderError" class="error-text"></div>
                </div>
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
