<?php
// view_chemicals.php - Shared (Admin & Guest) view of chemical inventory
require_once 'auth_check.php';
require_once 'db_config.php';

// Fetch chemicals with optional safety info
$chemicals = [];
$res = $mysqli->query("
    SELECT c.chemical_id, c.name, c.quantity, c.location, c.created_at, c.updated_at,
           cs.expiry_date, cs.safety_level
    FROM chemicals c
    LEFT JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    ORDER BY c.created_at DESC
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $chemicals[] = $row;
    }
}

require_once 'partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 fade-in-up">
    <div>
        <p class="page-title mb-1">Inventory</p>
        <h2 class="h4 mb-0">Chemical Stock</h2>
    </div>
    <?php if (is_role('admin')): ?>
        <a href="add_chemical.php" class="btn btn-neon">+ Add Chemical</a>
    <?php endif; ?>
</div>

<div class="glass-card p-3 table-glass fade-in-up">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Expiry</th>
                <th>Safety</th>
                <?php if (is_role('admin')): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($chemicals)): ?>
                <tr>
                    <td colspan="<?php echo is_role('admin') ? '7' : '6'; ?>" class="text-center text-secondary py-3">
                        No chemicals logged yet.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($chemicals as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['chemical_id']); ?></td>
                        <td><?php echo htmlspecialchars($c['name']); ?></td>
                        <td><?php echo htmlspecialchars($c['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($c['location']); ?></td>
                        <td>
                            <?php
                            echo $c['expiry_date']
                                ? htmlspecialchars($c['expiry_date'])
                                : '<span class="text-secondary">N/A</span>';
                            ?>
                        </td>
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
                        <?php if (is_role('admin')): ?>
                            <td>
                                <a href="edit_chemical.php?id=<?php echo urlencode($c['chemical_id']); ?>"
                                   class="btn btn-sm btn-outline-neon me-1">Edit</a>
                                <a href="delete_chemical.php?id=<?php echo urlencode($c['chemical_id']); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to delete this chemical?');">
                                    Delete
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

