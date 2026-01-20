<?php
// login.php - Login logic for Admin & Guest
require_once 'db_config.php';
session_start();

$error = '';
$prefilledRole = isset($_GET['role']) ? $_GET['role'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $mysqli->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // For simplicity, this demo uses plain comparison.
                // You can replace with password_verify() if you store hashed passwords.
                if ($password === $row['password']) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    header('Location: dashboard.php');
                    exit;
                }
            }
            $error = 'Invalid username or password.';
        } else {
            $error = 'Failed to prepare login statement.';
        }
    }
}

require_once 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5 fade-in-up">
        <div class="card-3d glass-card p-4 p-md-5">
            <div class="card-3d-inner">
                <div class="card-3d-content">
                    <p class="page-title mb-1">Secure Access</p>
                    <h2 class="h3 mb-3">Login to Chemical Inventory</h2>
                    <?php if ($prefilledRole): ?>
                        <p class="mb-3 text-secondary">
                            You are about to login as
                            <span class="badge rounded-pill <?php echo $prefilledRole === 'admin' ? 'role-pill-admin' : 'role-pill-guest'; ?>">
                                <?php echo htmlspecialchars(strtoupper($prefilledRole)); ?>
                            </span>
                        </p>
                    <?php else: ?>
                        <p class="mb-3 text-secondary">
                            Use an Admin account for full CRUD control, or a Guest account for read-only access.
                        </p>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                   required>
                        </div>
                        <div class="mb-3">
                           
                            <div class="form-text">
                                Admin has full Add/Edit/Delete access. Guest has read-only access.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-neon w-100 mt-2">Login</button>
                    </form>

                    <hr class="my-4 border-secondary">
                    <p class="text-secondary small mb-1">
                        Sample users (from SQL file):
                    </p>
                    <ul class="small text-secondary mb-0">
                        <li><strong>Admin</strong>: admin / admin123</li>
                        <li><strong>Guest</strong>: guest / guest123</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

