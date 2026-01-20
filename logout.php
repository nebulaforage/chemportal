<?php
// logout.php - Destroy session
session_start();
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;

