<?php
// db_config.php
// Update these values according to your XAMPP/MySQL setup.

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // default for XAMPP
$DB_NAME = 'smart_chemical_inventory';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Force UTF-8
$mysqli->set_charset('utf8mb4');

