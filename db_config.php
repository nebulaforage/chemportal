<?php
$DB_HOST = getenv('MYSQLHOST');
$DB_PORT = getenv('MYSQLPORT');
$DB_USER = getenv('MYSQLUSER');
$DB_PASS = getenv('MYSQLPASSWORD');
$DB_NAME = getenv('MYSQL_DATABASE');

if (!$DB_HOST || !$DB_PORT || !$DB_USER || !$DB_PASS || !$DB_NAME) {
    die("Database environment variables not set.");
}

$mysqli = new mysqli(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    (int)$DB_PORT
);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
