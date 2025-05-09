<?php


ob_start();
ini_set('display_errors', 1); // Enable error reporting for development
error_reporting(E_ALL);

require_once 'DB.php';
require_once 'sessionManager.php'; // This script should handle session security

$db = new DB();
$conn = $db->connect(); // Connect to the database

$errorMessage = '';


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Session Token: " . $_SESSION['csrf_token'] . "<br>";
    echo "Posted Token: " . $_POST['csrf_token'] . "<br>";

    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errorMessage = "CSRF token validation failed.";
    } else {
        // handle login
    }
}

   else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $errorMessage = "Please fill in all fields.";
        } else {
            if (!$conn) {
                die("Database connection failed.");
            }
            $stmt = $conn->prepare("SELECT UserID, Username, Password, Role FROM users WHERE Email = ?");
            $stmt->bindParam(1, $email);

            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData && password_verify($password, $userData['Password'])) {
                $_SESSION['UserID'] = $userData['UserID'];
                $_SESSION['Username'] = $userData['Username'];
                $_SESSION['Role'] = $userData['Role'];

                if ($userData['Role'] === 'Admin') {
                    header("Location: adminDashboard.php");
                    exit;
                } else {
                    $errorMessage = "Access Denied: You are not an admin.";
                }
            } else {
                $errorMessage = "Invalid email or password.";
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
</head>
<body>
<header>
<?php include 'navbar.php'; ?>
</header>
<div class="container">
    <h1>Admin Only</h1>
    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($errorMessage) ?>
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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p>
</footer>
</body>
</html>






 


















































