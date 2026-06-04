<?php
// admin/auth_check.php
require_once __DIR__ . '/../config/db.php';
start_secure_session();

// Enforce authentication and admin role check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Destroy session and redirect to login page
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: ../frontend/login.php");
    exit();
}
?>
