<?php
// db_config.php (Railway-safe, production-ready)

$mysqlUrl = getenv('MYSQL_URL');

if (!$mysqlUrl) {
    die('Database environment variables not set.');
}

$db = parse_url($mysqlUrl);

$DB_HOST = $db['host'];
$DB_USER = $db['user'];
$DB_PASS = $db['pass'];
$DB_NAME = ltrim($db['path'], '/');
$DB_PORT = $db['port'];

$mysqli = new mysqli(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    $DB_PORT
);

if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
