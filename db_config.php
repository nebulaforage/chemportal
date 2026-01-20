<?php
// db_config.php - Railway compatible MySQL config

$DB_HOST = getenv("MYSQLHOST");
$DB_USER = getenv("MYSQLUSER");
$DB_PASS = getenv("MYSQLPASSWORD");
$DB_NAME = getenv("MYSQLDATABASE");
$DB_PORT = getenv("MYSQLPORT");

// Safety check: avoid crashing during container boot
if (!$DB_HOST || !$DB_USER || !$DB_NAME) {
    die("Database environment variables not set.");
}

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Force UTF-8
$mysqli->set_charset("utf8mb4");
?>
