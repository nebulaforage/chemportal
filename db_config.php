<?php
// db_config.php (Railway-safe)

$databaseUrl = getenv('MYSQL_PUBLIC_URL');

if (!$databaseUrl) {
    die('Database environment variables not set.');
}

$db = parse_url($databaseUrl);

$DB_HOST = $db['host'];
$DB_PORT = $db['port'];
$DB_USER = $db['user'];
$DB_PASS = $db['pass'];
$DB_NAME = ltrim($db['path'], '/');

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
