<?php
// index.php - Landing page with role selection and 3D hero
require_once 'partials/header.php';
?>

<div class="position-relative">
    <div class="hero-3d-orbit">
        <div class="hero-orb" style="top:-40px; left:-40px;"></div>
        <div class="hero-orb secondary" style="bottom:-60px; right:-40px;"></div>
    </div>

    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 mb-4 fade-in-up">
            <div class="card-3d glass-card p-4 p-md-5">
                <div class="card-3d-inner">
                    <div class="card-3d-content">
                        <p class="page-title mb-1">Smart Chemical Inventory</p>
                        <h1 class="display-6 fw-semibold mb-3">Chemical Inventory Portal</h1>
                        <p class="mb-4 text-secondary">
                            Securely track laboratory and industrial chemicals with role-based access,
                            expiry &amp; safety alerts, and a modern dashboard experience.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge rounded-pill bg-info-subtle text-info">Role-based Access</span>
                            <span class="badge rounded-pill bg-success-subtle text-success">Expiry Alerts</span>
                            <span class="badge rounded-pill bg-warning-subtle text-warning">Safety Levels</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="login.php?role=admin" class="btn btn-neon px-4 py-2">
                                Login as Admin
                            </a>
                            <a href="login.php?role=guest" class="btn btn-outline-neon px-4 py-2">
                                Login as Guest
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4 fade-in-up">
            <div class="glass-card dashboard-panel p-4">
                <div class="dashboard-panel-inner">
                    <h5 class="mb-3">Live Glimpse</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Total Chemicals</span>
                            <span class="fs-4 fw-semibold text-info">∞</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Expiring Soon</span>
                            <span class="fs-5 fw-semibold text-warning">Smart Alerts</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">High Risk</span>
                            <span class="fs-5 fw-semibold text-danger">Highlighted</span>
                        </div>
                        <small class="text-secondary">
                            Sign in as <strong>Admin</strong> for full control,
                            or <strong>Guest</strong> for safe, read-only exploration.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>
