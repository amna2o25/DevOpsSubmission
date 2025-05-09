<?php
require_once 'DB.php';
require_once 'sessionManager.php';


ob_start();

// Check if the user is logged in and authorized
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: admin.php');
    exit;
}

// Check if a product ID was submitted and the request is a POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'])) {
    $db = new DB();
    $conn = $db->connect();

    // Check connection
    if (!$conn) {
        echo "<p>Connection failed: " . $conn->errorInfo() . "</p>";
    } else {
        $productId = $_POST['productId'];

        // Prepare the SQL statement to delete the product
        $stmt = $conn->prepare("DELETE FROM tbl_products WHERE ProductID = ?");
        $stmt->bindParam(1, $productId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<p>Product deleted successfully!</p>";
        } else {
            echo "<p>Failed to delete product. It may not exist.</p>";
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<main class="container">
    <h1>Delete Product</h1>
    <form action="delete_product.php" method="post">
        <div class="form-group">
            <label for="productId">Product ID:</label>
            <input type="text" class="form-control" id="productId" name="productId" required>
        </div>
        <button type="submit" class="btn btn-danger">Delete Product</button>
    </form>
</main>

<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p>
</footer>
</body>
</html>

