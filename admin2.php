<?php
ob_start();
ini_set("display_errors", 1);

require_once 'DB.php';
require_once 'sessionManager.php';

$db = new DB();
$errorMessage = '';
$loginSuccess = false;

// Display admin details
$adminData = [];

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        $conn = $db->connect();
        if ($conn) {
            $stmt = $conn->prepare("SELECT admin_id, admin_email, admin_password FROM admins WHERE admin_email = ?");
            $stmt->bindParam(1, $email);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $adminData['admin_password'])) {
                        $_SESSION['admin_id'] = $adminData['admin_id'];
                        $_SESSION['admin_email'] = $adminData['admin_email'];
                        $loginSuccess = true;
                    } else {
                        $errorMessage = "Invalid email or password.";
                    }
                } else {
                    $errorMessage = "No account found with this email.";
                }
            } else {
                $errorMessage = "An error occurred during the login process.";
            }
        } else {
            $errorMessage = "Database connection failed.";
        }
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style></style>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<div class="container">
    <?php if ($loginSuccess): ?>
        <!-- Redirect or display dashboard -->
        <script>window.location.href = 'adminDashboard.php';</script>
    <?php else: ?>
        <div class="login-container">
            <h1>Admin Only</h1>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errorMessage ?>
                </div>
            <?php endif; ?>
            <form action="admin.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
        <?php if (!$loginSuccess && !empty($errorMessage)): ?>
            <div class="alert alert-info" role="alert">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
</div>
    </body> 
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    </html><footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p><br>