<?php
require_once __DIR__ . '/../config/db.php';
start_secure_session();
header("Location: frontend/index.php");
exit();
?>
