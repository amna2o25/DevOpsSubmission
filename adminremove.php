<?php
ob_start();


// Security checks
require_once 'sessionManager.php';
checkAdmin();  // Ensure only logged-in admins can access

require_once 'DB.php';

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$db = new DB();
$conn = $db->connect();

// Initialize message
$errorMessage = '';
$successMessage = '';

// Handle the deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    $user_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $user_token)) {
        die('CSRF token validation failed');
    }

    if (isset($_POST['admin_id']) && is_numeric($_POST['admin_id'])) {
        $admin_id = $_POST['admin_id'];

        try {
            $conn->beginTransaction();
            $stmt = $conn->prepare("DELETE FROM admins WHERE admin_id = ?");
            $stmt->bindParam(1, $admin_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $conn->commit();
                    $successMessage = "Admin successfully removed.";
                } else {
                    $conn->rollBack();
                    $errorMessage = "Error: No admin found with ID $admin_id.";
                }
            } else {
                $conn->rollBack();
                $errorMessage = "Error removing admin.";
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            $errorMessage = "Database error: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid Admin ID.";
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<div class="container">
    <h1>Remove Admin</h1>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>
    <?php if ($successMessage): ?>
        <div class="alert alert-success" role="alert">
            <?= $successMessage ?>
        </div>
    <?php endif; ?>
    <form action="adminremove.php" method="post">
        <div class="form-group">
            <label for="admin_id">Admin ID:</label>
            <input type="number" id="admin_id" name="admin_id" class="form-control" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Remove Admin</button>
    </form>
</div>

<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p>
</footer>
</body>
</html>









