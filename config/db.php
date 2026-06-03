<?php
// config/db.php
$host = "localhost";
$username = "root"; // Default for XAMPP/WAMP, change for InfinityFree
$password = ""; // Default empty, change for InfinityFree
$dbname = "sims_db"; // Change if using free hosting DB name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
