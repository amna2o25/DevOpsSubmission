<?php
ob_start();
ini_set("display_errors", 1);
require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo 'CSRF token validation failed.';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo 'Please fill in all fields!';
        } else {
            $query = $conn->prepare("SELECT * FROM Users WHERE Email = :email");
            $query->bindParam(':email', $email);
            $query->execute();
            $user = $query->fetch();

            if ($user && password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['Role'];

              
                if ($user['Role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    header("Location: user_dashboard.php");
                    exit;
                }
            } else {
                echo "Invalid email or password.";
            }
        }
     
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteProduct'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo 'CSRF token validation failed for product deletion.';
    } else {
        $productID = $_POST['productID'];
        $query = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
        $query->bind_param("i", $productID);
        $query->execute();
        echo "Product deleted successfully.";
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Tyne Brew Coffee</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
   
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        Product ID to delete: <input type="text" name="productID" required><br>
        <input type="submit" value="Delete Product" name="deleteProduct">
    </form>
    <?php endif; ?>
</body>
</html>


