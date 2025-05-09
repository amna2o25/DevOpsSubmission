<?php
require_once 'DB.php';
require_once 'sessionManager.php';
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new DB();
    $conn = $db->connect();

    $productName = $_POST['productName'];
    $productPrice = (float)$_POST['productPrice'];
    $productDescription = $_POST['productDescription'];
    $productImage = $_POST['productImage']; // You might want to handle file uploads here

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO tbl_products (ProductName, Price, Description, Image) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $productName);
    $stmt->bindParam(2, $productPrice);
    $stmt->bindParam(3, $productDescription);
    $stmt->bindParam(4, $productImage);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<p>Product added successfully!</p>";
    } else {
        echo "<p>Failed to add product.</p>";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<main class="container">
    <h1>Add New Product</h1>
    <form action="addproduct.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
        </div>
        <div class="form-group">
            <label for="productPrice">Price:</label>
            <input type="text" class="form-control" id="productPrice" name="productPrice" required>
        </div>
        <div class="form-group">
            <label for="productDescription">Description:</label>
            <textarea class="form-control" id="productDescription" name="productDescription" required></textarea>
        </div>
        <div class="form-group">
            <label for="productImage">Image URL:</label>
            <input type="text" class="form-control" id="productImage" name="productImage">
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</main>
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

