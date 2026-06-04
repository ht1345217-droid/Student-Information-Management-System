<?php
require_once __DIR__ . '/../config/db.php';
start_secure_session();

// Redirect to login if not already logged in, otherwise to dashboard
if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: dashboard.php");
} else {
    header("Location: ../frontend/login.php");
}
exit();
?>
