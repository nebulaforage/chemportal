<?php
// delete_chemical.php - Admin-only delete chemical
require_once 'auth_check.php';
require_role('admin');
require_once 'db_config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Delete safety record first due to FK
    $stmt1 = $mysqli->prepare('DELETE FROM chemical_safety WHERE chemical_id = ?');
    if ($stmt1) {
        $stmt1->bind_param('i', $id);
        $stmt1->execute();
    }

    // Delete chemical
    $stmt2 = $mysqli->prepare('DELETE FROM chemicals WHERE chemical_id = ?');
    if ($stmt2) {
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
    }
}

header('Location: view_chemicals.php');
exit;

