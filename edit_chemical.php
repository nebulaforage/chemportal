<?php
// edit_chemical.php - Admin-only edit chemical
require_once 'auth_check.php';
require_role('admin');
require_once 'db_config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: view_chemicals.php');
    exit;
}

$error = '';
$success = '';

// Fetch existing record
$stmt = $mysqli->prepare("
    SELECT c.chemical_id, c.name, c.quantity, c.location,
           cs.expiry_date, cs.safety_level
    FROM chemicals c
    LEFT JOIN chemical_safety cs ON c.chemical_id = cs.chemical_id
    WHERE c.chemical_id = ?
    LIMIT 1
");
if (!$stmt) {
    die('Failed to prepare fetch statement.');
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$chemical = $result->fetch_assoc();

if (!$chemical) {
    header('Location: view_chemicals.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $expiry = trim($_POST['expiry_date'] ?? '');
    $safety = trim($_POST['safety_level'] ?? '');

    if ($name === '' || $quantity === '' || $location === '') {
        $error = 'Name, quantity, and location are required.';
    } else {
        // Update chemicals
        $stmtUp = $mysqli->prepare('UPDATE chemicals SET name = ?, quantity = ?, location = ?, updated_at = NOW() WHERE chemical_id = ?');
        if ($stmtUp) {
            $stmtUp->bind_param('sssi', $name, $quantity, $location, $id);
            if ($stmtUp->execute()) {
                // Upsert safety record
                if ($expiry !== '' || $safety !== '') {
                    if ($safety === '') {
                        $safety = 'Low';
                    }
                    // Check if record exists
                    $sCheck = $mysqli->prepare('SELECT chemical_id FROM chemical_safety WHERE chemical_id = ?');
                    $sCheck->bind_param('i', $id);
                    $sCheck->execute();
                    $sRes = $sCheck->get_result();
                    $expiryParam = $expiry !== '' ? $expiry : null;
                    if ($sRes->fetch_assoc()) {
                        $sUp = $mysqli->prepare('UPDATE chemical_safety SET expiry_date = ?, safety_level = ? WHERE chemical_id = ?');
                        if ($sUp) {
                            $sUp->bind_param('ssi', $expiryParam, $safety, $id);
                            $sUp->execute();
                        }
                    } else {
                        $sIns = $mysqli->prepare('INSERT INTO chemical_safety (chemical_id, expiry_date, safety_level) VALUES (?, ?, ?)');
                        if ($sIns) {
                            $sIns->bind_param('iss', $id, $expiryParam, $safety);
                            $sIns->execute();
                        }
                    }
                }

                $success = 'Chemical updated successfully.';
                // Refresh local copy
                $chemical['name'] = $name;
                $chemical['quantity'] = $quantity;
                $chemical['location'] = $location;
                $chemical['expiry_date'] = $expiry !== '' ? $expiry : null;
                $chemical['safety_level'] = $safety !== '' ? $safety : null;
            } else {
                $error = 'Failed to update chemical: ' . $stmtUp->error;
            }
        } else {
            $error = 'Failed to prepare update statement.';
        }
    }
}

require_once 'partials/header.php';
?>

<div class="row justify-content-center fade-in-up">
    <div class="col-md-8 col-lg-7">
        <div class="glass-card p-4">
            <p class="page-title mb-1">Admin</p>
            <h2 class="h4 mb-3">Edit Chemical #<?php echo htmlspecialchars($chemical['chemical_id']); ?></h2>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success py-2"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Chemical Name</label>
                        <input type="text" name="name" class="form-control"
                               value="<?php echo htmlspecialchars($chemical['name']); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" name="quantity" class="form-control"
                               value="<?php echo htmlspecialchars($chemical['quantity']); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control"
                               value="<?php echo htmlspecialchars($chemical['location']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control"
                               value="<?php echo htmlspecialchars($chemical['expiry_date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Safety Level</label>
                        <select name="safety_level" class="form-select">
                            <?php
                            $lvl = $chemical['safety_level'] ?? '';
                            ?>
                            <option value="">Choose...</option>
                            <option value="Low" <?php echo $lvl === 'Low' ? 'selected' : ''; ?>>Low</option>
                            <option value="Medium" <?php echo $lvl === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="High" <?php echo $lvl === 'High' ? 'selected' : ''; ?>>High</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="view_chemicals.php" class="btn btn-outline-neon">Back to List</a>
                    <button type="submit" class="btn btn-neon">Update Chemical</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

