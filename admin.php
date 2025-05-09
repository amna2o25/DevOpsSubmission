<?php

ob_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once 'DB.php';
require_once 'sessionManager.php';

$db = new DB();
$conn = $db->connect(); // Properly initialize the database connection

$password = '0987654321';  // Your plain text password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

// Assume $conn is your PDO connection object
$username = 'Amy';
$email = 'amy9000@gmail.com';
$role = 'Admin';

// SQL to insert a new user
$sql = "INSERT INTO Users (Username, Email, Password, Role, Registration) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";

$stmt = $conn->prepare($sql);  // Prepare the SQL statement

// Bind parameters to the prepared statement
$stmt->bindParam(1, $username);
$stmt->bindParam(2, $email);
$stmt->bindParam(3, $hashed_password);
$stmt->bindParam(4, $role);

// Execute the statement
$stmt->execute();





if (!$conn) {
    die("Connection failed: " . $conn->errorInfo());
}

// Initialize CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errorMessage = '';

// Handling the login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errorMessage = "CSRF token validation failed";
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $errorMessage = "Please fill in all fields.";
        } else {
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
                    header("Location: userDashboard.php");
                    exit;
                }
            } else {
                $errorMessage = "Invalid email or password.";
            }
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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
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
    <br>
    <br>
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p>
</footer>
</body>
</html>















 


















































