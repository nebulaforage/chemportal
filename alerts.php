<?php
// alerts.php - Expiry & Safety Alerts
require_once 'auth_check.php';
require_once 'db_config.php';

// Expired chemicals
$expired = [];
$res = $mysqli->query("
    SELECT c.chemical_id, c.name, c.quantity, c.location,
           cs.expiry_date, cs.safety_level
    FROM chemicals c
    JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    WHERE cs.expiry_date IS NOT NULL
      AND cs.expiry_date < CURDATE()
    ORDER BY cs.expiry_date ASC
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $expired[] = $row;
    }
}

// Expiring soon (next 30 days)
$soon = [];
$res = $mysqli->query("
    SELECT c.chemical_id, c.name, c.quantity, c.location,
           cs.expiry_date, cs.safety_level
    FROM chemicals c
    JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    WHERE cs.expiry_date IS NOT NULL
      AND cs.expiry_date >= CURDATE()
      AND cs.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    ORDER BY cs.expiry_date ASC
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $soon[] = $row;
    }
}

// High safety risk (any date)
$high = [];
$res = $mysqli->query("
    SELECT c.chemical_id, c.name, c.quantity, c.location,
           cs.expiry_date, cs.safety_level
    FROM chemicals c
    JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    WHERE cs.safety_level = 'High'
    ORDER BY cs.expiry_date ASC
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $high[] = $row;
    }
}

require_once 'partials/header.php';
?>

<div class="mb-3 fade-in-up">
    <p class="page-title mb-1">Safety Center</p>
    <h2 class="h4 mb-0">Expiry &amp; Safety Alerts</h2>
    
</div>

<div class="row g-4 fade-in-up">
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <h5 class="mb-2 text-danger">Expired</h5>
            <p class="small text-secondary mb-3">
                Chemicals past their expiry date. Must be disposed or handled according to safety protocol.
            </p>
            <div class="table-responsive table-glass">
                <table class="table table-dark table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Expiry</th>
                        <th>Safety</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($expired)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-2">
                                No expired chemicals.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($expired as $c): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['name']); ?></td>
                                <td><?php echo htmlspecialchars($c['expiry_date']); ?></td>
                                <td>
                                    <span class="badge badge-safety-high">
                                        <?php echo htmlspecialchars($c['safety_level']); ?>
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
            <h5 class="mb-2 text-warning">Expiring Soon (≤ 30 days)</h5>
            <p class="small text-secondary mb-3">
                Plan ahead for restocking or safe disposal before these chemicals expire.
            </p>
            <div class="table-responsive table-glass">
                <table class="table table-dark table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Expiry</th>
                        <th>Safety</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($soon)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-2">
                                No chemicals expiring soon.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($soon as $c): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['name']); ?></td>
                                <td><?php echo htmlspecialchars($c['expiry_date']); ?></td>
                                <td>
                                    <?php
                                    $level = $c['safety_level'] ?? 'Low';
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
            <h5 class="mb-2 text-info">High Safety Risk</h5>
            <p class="small text-secondary mb-3">
                Chemicals marked with <strong>High</strong> safety level due to toxicity, flammability, or reactivity.
            </p>
            <div class="table-responsive table-glass">
                <table class="table table-dark table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Expiry</th>
                        <th>Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($high)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-2">
                                No high risk chemicals flagged.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($high as $c): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['name']); ?></td>
                                <td>
                                    <?php
                                    echo $c['expiry_date']
                                        ? htmlspecialchars($c['expiry_date'])
                                        : '<span class="text-secondary">N/A</span>';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($c['location']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

