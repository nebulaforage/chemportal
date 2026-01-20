<?php
// add_chemical.php - Admin-only add chemical
require_once 'auth_check.php';
require_role('admin');
require_once 'db_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $expiry = trim($_POST['expiry_date'] ?? '');
    $safety = trim($_POST['safety_level'] ?? '');

    if ($name === '' || $quantity === '' || $location === '') {
        $error = 'Name, quantity, and location are required.';
    } else {
        // Insert into chemicals table
        $stmt = $mysqli->prepare('INSERT INTO chemicals (name, quantity, location, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
        if ($stmt) {
            $stmt->bind_param('sss', $name, $quantity, $location);
            if ($stmt->execute()) {
                $chemicalId = $stmt->insert_id;

                // Optionally insert safety record
                if ($expiry !== '' || $safety !== '') {
                    if ($safety === '') {
                        $safety = 'Low';
                    }
                    $stmt2 = $mysqli->prepare('INSERT INTO chemical_safety (chemical_id, expiry_date, safety_level) VALUES (?, ?, ?)');
                    if ($stmt2) {
                        $expiryParam = $expiry !== '' ? $expiry : null;
                        $stmt2->bind_param('iss', $chemicalId, $expiryParam, $safety);
                        $stmt2->execute();
                    }
                }

                $success = 'Chemical added successfully.';
            } else {
                $error = 'Failed to insert chemical: ' . $stmt->error;
            }
        } else {
            $error = 'Failed to prepare insert statement.';
        }
    }
}

require_once 'partials/header.php';
?>

<div class="row justify-content-center fade-in-up">
    <div class="col-md-8 col-lg-7">
        <div class="glass-card p-4">
            <p class="page-title mb-1">Admin</p>
            <h2 class="h4 mb-3">Add New Chemical</h2>

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
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" name="quantity" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Safety Level</label>
                        <select name="safety_level" class="form-select">
                            <option value="">Choose...</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="view_chemicals.php" class="btn btn-outline-neon">Back to List</a>
                    <button type="submit" class="btn btn-neon">Save Chemical</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

