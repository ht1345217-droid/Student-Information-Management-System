<?php
// config/db.php
$host = getenv('MYSQLHOST');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$dbname = getenv('MYSQLDATABASE');
$port = (int)getenv('MYSQLPORT');

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

function start_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
// Check connection
if ($conn->connect_error) {
    // Return a generic friendly error without exposing database connection details
    die("Database Connection Error. Please try again later.");
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// Start a secure session if not already started
function start_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        // Enforce cookie security
        $secure_cookie = isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        
        session_set_cookie_params([
            'lifetime' => 3600, // 1 hour expiration
            'path' => '/',
            'domain' => '',
            'secure' => $secure_cookie,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        session_start();
    }
}

// Set Security Headers
function set_security_headers() {
    // Clickjacking protection
    header("X-Frame-Options: DENY");
    // X-XSS Protection (for older browsers)
    header("X-XSS-Protection: 1; mode=block");
    // Content Type Sniffing protection
    header("X-Content-Type-Options: nosniff");
    // Referrer Policy
    header("Referrer-Policy: strict-origin-when-cross-origin");
    // Simple Content Security Policy
    header("Content-Security-Policy: default-src 'self' https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com; img-src 'self' data: https://images.unsplash.com https://ui-avatars.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; frame-src https://www.google.com https://www.google.com/maps/;");
}

// CSRF Token Generation
function generate_csrf_token() {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token Verification
function verify_csrf_token($token) {
    start_secure_session();
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// XSS Sanitization helper for output
function xss_clean($data) {
    if (is_array($data)) {
        return array_map('xss_clean', $data);
    }
    return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}

// Call headers automatically
set_security_headers();
?>
