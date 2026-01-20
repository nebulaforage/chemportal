<?php
// auth_check.php
session_start();

// If user is not logged in, redirect to login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

/**
 * Require a specific role for the current page.
 * Usage: require_role('admin');
 *
 * @param string $role
 */
function require_role(string $role): void
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        // Forbidden for this role
        header('HTTP/1.1 403 Forbidden');
        echo '<h2>403 - Forbidden</h2>';
        echo '<p>You do not have permission to access this page.</p>';
        echo '<p><a href="dashboard.php">Back to Dashboard</a></p>';
        exit;
    }
}

/**
 * Helper to check if current user has role.
 */
function is_role(string $role): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}


