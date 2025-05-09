<?php
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

// Handling product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProduct'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo 'CSRF token validation failed for adding product.';
    } else {
        $name = $_POST['productName'];
        $description = $_POST['productDescription'];
        $price = $_POST['productPrice'];
        $stock = $_POST['productStock'];

        $query = $conn->prepare("INSERT INTO Products (ProductName, Description, Price, Stock) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssdi", $name, $description, $price, $stock);
        $query->execute();
        echo "Product added successfully.";
    }
}

// Include other product management functionalities here if needed

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1.1">
    <title>Admin Dashboard</title>
</head>
<body>
    <header>
        <nav>
            <ul class="menu">
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="basket.php">Basket</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Forms for product management -->
    <!-- Add Product Form -->
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <h3>Add a New Product:</h3>
        Product Name: <input type="text" name="productName" required><br>
        Description: <input type="text" name="productDescription" required><br>
        Price: <input type="text" name="productPrice" required><br>
        Stock: <input type="text" name="productStock" required><br>
        <input type="submit" value="Add Product" name="addProduct">
    </form>
    <?php endif; ?>
</body>
</html>

