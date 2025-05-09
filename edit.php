<?php

ob_start();
require_once 'DB.php';
require_once 'sessionManager.php';


// Check user authorization, assuming a role-based access
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: admin.php'); // Redirect to login if not authorized
    exit;
}

// Establish database connection
$db = new DB();
$conn = $db->connect();

if (!$conn) {
    die("Connection failed: " . $conn->errorInfo());
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Collect and sanitize input data
    $productId = filter_input(INPUT_POST, 'productId', FILTER_SANITIZE_NUMBER_INT);
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_VALIDATE_FLOAT);
    $productDescription = filter_input(INPUT_POST, 'productDescription', FILTER_SANITIZE_STRING);
    $productImage = filter_input(INPUT_POST, 'productImage', FILTER_SANITIZE_URL);

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE tbl_products SET ProductName = ?, Price = ?, Description = ?, Image = ? WHERE ProductID = ?");
    $stmt->bindParam(1, $productName);
    $stmt->bindParam(2, $productPrice);
    $stmt->bindParam(3, $productDescription);
    $stmt->bindParam(4, $productImage);
    $stmt->bindParam(5, $productId);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product.";
    }

    header("Location: edit.php?id=$productId"); // Redirect to avoid form resubmission
    exit;
}

// Retrieve product data for editing
if (isset($_GET['id'])) {
    $productId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("SELECT * FROM tbl_products WHERE ProductID = ?");
    $stmt->bindParam(1, $productId);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit;
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<main class="container">
    <h1>Edit Product</h1>
    <?php
    if (!empty($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    if (!empty($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <form action="edit.php" method="post">
        <input type="hidden" name="productId" value="<?= $product['ProductID']; ?>">
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="text" class="form-control" id="productName" name="productName" required value="<?= htmlspecialchars($product['ProductName']); ?>">
        </div>
        <div class="form-group">
            <label for="productPrice">Price:</label>
            <input type="text" class="form-control" id="productPrice" name="productPrice" required value="<?= htmlspecialchars($product['Price']); ?>">
        </div>
        <div class="form-group">
            <label for="productDescription">Description:</label>
            <textarea class="form-control" id="productDescription" name="productDescription" required><?= htmlspecialchars($product['Description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="productImage">Image URL:</label>
            <input type="text" class="form-control" id="productImage" name="productImage" value="<?= htmlspecialchars($product['Image']); ?>">
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Product</button>
    </form>
</main>

<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Your Company Name</p>
</footer>
</body>
</html>
