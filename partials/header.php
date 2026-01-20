<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Chemical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark nav-glass fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-semibold" href="index.php">
            <span class="text-info">Chem</span><span class="text-light">Portal</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_chemicals.php">Chemicals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alerts.php">Alerts</a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_chemical.php">Add Chemical</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="badge rounded-pill <?php echo $_SESSION['role'] === 'admin' ? 'role-pill-admin' : 'role-pill-guest'; ?>">
                        <?php echo htmlspecialchars(strtoupper($_SESSION['role'])); ?>
                    </span>
                    <a href="logout.php" class="btn btn-sm btn-outline-neon">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-neon">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="app-main">
    <div class="container py-4">

