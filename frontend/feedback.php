<?php
require_once '../config/db.php';
start_secure_session();

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF verification
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security validation failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        // Distinguish between contact page submission and feedback page submission
        if (isset($_POST['c_msg'])) {
            $name = trim($_POST['c_name'] ?? '');
            $email = trim($_POST['c_email'] ?? '');
            $msg = trim($_POST['c_msg'] ?? '');
        } else {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $msg = trim($_POST['message'] ?? '');
        }

        // Check word count
        $wordCount = str_word_count($msg);
        if($wordCount > 250) {
            $message = "Feedback cannot exceed 250 words.";
            $messageType = "error";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $msg);
            if($stmt->execute()) {
                $message = "Your message was sent successfully! Thank you for your feedback.";
                $messageType = "success";
            } else {
                $message = "Error submitting feedback. Please try again.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - SIMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><i class="fa-solid fa-graduation-cap"></i> SIMS Portal</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="students.php">Students & Courses</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-greeting"><i class="fa-solid fa-user-circle"></i> Hello, <?php echo xss_clean($_SESSION['name']); ?></span>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="../admin/dashboard.php" class="btn btn-outline">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="page-header">
        <h1>Submit Feedback</h1>
        <p>We value your thoughts and suggestions.</p>
    </div>

    <div class="form-container" style="margin-top: 4rem;">
        <?php if($message): ?>
            <div style="padding: 10px; border-radius: 4px; margin-bottom: 1rem; <?php echo $messageType == 'error' ? 'background:#f8d7da; color:#721c24;' : 'background:#d4edda; color:#155724;'; ?>">
                <?php echo xss_clean($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required value="">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required value="">
            </div>
            <div class="form-group">
                <label for="feedbackMsg">Message (Max 250 words)</label>
                <textarea id="feedbackMsg" name="message" class="form-control" rows="6" required></textarea>
                <div style="text-align: right; font-size: 0.85rem; color: var(--light-text); margin-top: 0.25rem;" id="wordCount">0/250 words</div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Feedback</button>
        </form>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
