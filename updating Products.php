<?php
ob_start();
ini_set("display_errors", 1);
require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();
$conn = $db->connect();

// Always regenerate CSRF token on every POST request to enhance security
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CSRF token validation for login
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

                // Redirect based on role
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
        // Regenerate CSRF token after successful form submission
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Product update handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateProduct'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo 'CSRF token validation failed for product update.';
    } else {
        $productID = $_POST['productID'];
        $name = $_POST['productName'];
        $description = $_POST['productDescription'];
        $price = $_POST['productPrice'];
        $stock = $_POST['productStock'];

        $query = $conn->prepare("UPDATE Products SET ProductName = ?, Description = ?, Price = ?, Stock = ? WHERE ProductID = ?");
        $query->bind_param("ssdii", $name, $description, $price, $stock, $productID);
        $query->execute();
        echo "Product updated successfully.";
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
    <title>Tyne Brew Coffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <main>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Form to update a product -->
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        Product ID: <input type="text" name="productID" required><br>
        Product Name: <input type="text" name="productName" required><br>
        Description: <input type="text" name="productDescription" required><br>
        Price: <input type="text" name="productPrice" required><br>
        Stock: <input type="text" name="productStock" required><br>
        <input type="submit" value="Update Product" name="updateProduct">
    </form>
    <?php endif; ?>
</body>
</html>



