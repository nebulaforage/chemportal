<?php
// db_config.php — Railway-compatible

$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

// Validate environment variables
if (!$host || !$port || !$db || !$user || !$pass) {
    die('Database environment variables not set.');
}

// Create MySQL connection
$mysqli = new mysqli($host, $user, $pass, $db, (int)$port);

// Check connection
if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Force UTF-8
$mysqli->set_charset('utf8mb4');
