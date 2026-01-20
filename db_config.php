<?php
$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQL_DATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

if (!$host || !$port || !$db || !$user || !$pass) {
    die('Database environment variables not set.');
}

$mysqli = new mysqli($host, $user, $pass, $db, (int)$port);

if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
