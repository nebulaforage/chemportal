<?php
// db_config.php — Railway FINAL FIX

$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQL_DATABASE');   // ✅ underscore FIX
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

// Debug safety check
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
