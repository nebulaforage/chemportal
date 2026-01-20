<?php
// dashboard.php - Role-based dashboard
require_once 'auth_check.php';
require_once 'db_config.php';

// Fetch simple stats and alerts
$totalChemicals = 0;
$expiringSoon = 0;
$highRiskCount = 0;

// Total chemicals
$res = $mysqli->query('SELECT COUNT(*) AS c FROM chemicals');
if ($res && $row = $res->fetch_assoc()) {
    $totalChemicals = (int)$row['c'];
}

// Expiring within 30 days
$res = $mysqli->query("
    SELECT COUNT(*) AS c
    FROM chemical_safety cs
    WHERE cs.expiry_date IS NOT NULL
      AND cs.expiry_date >= CURDATE()
      AND cs.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
");
if ($res && $row = $res->fetch_assoc()) {
    $expiringSoon = (int)$row['c'];
}

// High safety level
$res = $mysqli->query("
    SELECT COUNT(*) AS c
    FROM chemical_safety cs
    WHERE cs.safety_level = 'High'
");
if ($res && $row = $res->fetch_assoc()) {
    $highRiskCount = (int)$row['c'];
}

// Latest few alerts (expired or high)
$alerts = [];
$res = $mysqli->query("
    SELECT c.chemical_id, c.name, cs.expiry_date, cs.safety_level
    FROM chemicals c
    LEFT JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    WHERE (cs.expiry_date IS NOT NULL AND cs.expiry_date < CURDATE())
       OR cs.safety_level = 'High'
    ORDER BY cs.expiry_date ASC
    LIMIT 5
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $alerts[] = $row;
    }
}

require_once 'partials/header.php';
?>

<div class="row g-4 fade-in-up">
    <div class="col-12 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="page-title mb-1">Welcome back</p>
                <h2 class="h3 mb-0">
                    Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    (<?php echo htmlspecialchars(strtoupper($_SESSION['role'])); ?>)
                </h2>
            </div>
            <?php if (is_role('admin')): ?>
                <a href="add_chemical.php" class="btn btn-neon">+ Add Chemical</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card dashboard-panel p-3 h-100">
            <div class="dashboard-panel-inner">
                <p class="page-title mb-1">Inventory</p>
                <h3 class="h2 mb-0 text-info"><?php echo $totalChemicals; ?></h3>
                <p class="text-secondary mb-0">Total chemicals logged</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card dashboard-panel p-3 h-100">
            <div class="dashboard-panel-inner">
                <p class="page-title mb-1">Expiring Soon (&le; 30 days)</p>
                <h3 class="h2 mb-0 text-warning"><?php echo $expiringSoon; ?></h3>
                <p class="text-secondary mb-0">Plan ahead to restock safely</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card dashboard-panel p-3 h-100">
            <div class="dashboard-panel-inner">
                <p class="page-title mb-1">High Risk</p>
                <h3 class="h2 mb-0 text-danger"><?php echo $highRiskCount; ?></h3>
                <p class="text-secondary mb-0">Chemicals with critical safety level</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4 fade-in-up">
    <div class="col-lg-8">
        <div class="glass-card p-3 table-glass">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Recent Alerts</h5>
                <a href="alerts.php" class="small text-info text-decoration-none">View all alerts</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>Chemical</th>
                        <th>Expiry Date</th>
                        <th>Safety Level</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($alerts)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-3">
                                No urgent alerts at the moment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alerts as $a): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($a['name']); ?></td>
                                <td>
                                    <?php
                                    echo $a['expiry_date']
                                        ? htmlspecialchars($a['expiry_date'])
                                        : '<span class="text-secondary">N/A</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $level = $a['safety_level'] ?? 'Low';
                                    $badgeClass = 'badge-safety-low';
                                    if ($level === 'Medium') {
                                        $badgeClass = 'badge-safety-medium';
                                    } elseif ($level === 'High') {
                                        $badgeClass = 'badge-safety-high';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($level); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <h5>Quick Links</h5>
            <ul class="list-unstyled small mb-0">
                <li class="mb-2">
                    <a href="view_chemicals.php" class="text-decoration-none">
                        View full chemical inventory
                    </a>
                </li>
                <li class="mb-2">
                    <a href="alerts.php" class="text-decoration-none">
                        Review expiry &amp; safety alerts
                    </a>
                </li>
                <?php if (is_role('admin')): ?>
                    <li class="mb-2">
                        <a href="add_chemical.php" class="text-decoration-none">
                            Add new chemical record
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

