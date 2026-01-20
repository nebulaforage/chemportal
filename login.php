<?php
// login.php - Secure Login (Admin & Guest)

require_once 'db_config.php';
session_start();

$error = '';
$prefilledRole = $_GET['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {

        // ✅ PREPARE STATEMENT (NO get_result)
        $sql = "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            // HARD DEBUG (remove after testing)
            die("SQL Prepare Failed: " . $mysqli->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        // ✅ Use bind_result instead of get_result
        $stmt->bind_result($id, $dbUsername, $dbPassword, $role);

        if ($stmt->fetch()) {

            // ✅ SECURE PASSWORD CHECK
            if (password_verify($password, $dbPassword)) {

                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $dbUsername;
                $_SESSION['role'] = $role;

                // Redirect based on role (optional)
                header("Location: dashboard.php");
                exit;

            } else {
                $error = 'Invalid username or password.';
            }

        } else {
            $error = 'Invalid username or password.';
        }

        $stmt->close();
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
                                <?php echo strtoupper(htmlspecialchars($prefilledRole)); ?>
                            </span>
                        </p>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-neon w-100 mt-2">Login</button>
                    </form>

                    <hr class="my-4 border-secondary">
                    <p class="text-secondary small mb-1">Sample users:</p>
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
