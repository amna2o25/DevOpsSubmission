<?php
ob_start();
require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle adding a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

    if (!$name || !$description || !$price) {
        die("All fields are required.");
    }

    $db->query(
        "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)",
        [
            'name' => $name,
            'description' => $description,
            'price' => $price,
        ]
    );
    header("Location: manage_products.php");
    exit;
}


$products = $db->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>

   
    <form action="manage_products.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <button type="submit">Add Product</button>
    </form>

 
    <h2>Existing Products</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>£<?= number_format($product['price'], 2) ?></td>
                    <td>
                        <form action="delete_product.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>


