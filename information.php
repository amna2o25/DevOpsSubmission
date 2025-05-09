<?php
ob_start();
ini_set("display_errors", 0); // Disable in production
error_reporting(0); // Disable error reporting in production

require 'DB.php';
require 'Product.php';

$product = null;

if (isset($_GET['ProductID'])) {
    $id = intval($_GET['ProductID']);

    $db = new DB();
    $conn = $db->connect();
    $query = $conn->prepare("SELECT * FROM tbl_products WHERE ProductID = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $product = new Product($row['ProductID'], $row['ProductName'], $row['Image'], $row['Price']);
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
    <title>Product Information</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>

    <title>Product Information</title>
</head>
<body>
    <h1>Product Information</h1>
    <?php if ($product): ?>
        <h2><?= htmlspecialchars($product->name()) ?></h2>
        <p><strong>Product ID:</strong> <?= htmlspecialchars($product->id()) ?></p>
        <p><strong>Product Name:</strong> <?= htmlspecialchars($product->name()) ?></p>
        <p><strong>Price:</strong> £<?= htmlspecialchars($product->price()) ?></p>
        <img src="<?= htmlspecialchars($product->image()) ?>" alt="<?= htmlspecialchars($product->name()) ?>" style="width:300px; height:300px;">
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</body>
</html>


