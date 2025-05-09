<?php

ob_start();

require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

    try {
        // Create database connection
        $conn = $db->connect();

        // SQL to insert new product into the database
        $sql = "INSERT INTO products (name, description, price, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$name, $description, $price, $category]);

            // Check if insert was successful
            if ($stmt->rowCount()) {
                echo "<p>Product added successfully!</p>";
            } else {
                echo "<p>Failed to add the product.</p>";
            }
        } else {
            throw new Exception("Failed to prepare the SQL statement.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form action="admin_add_product.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <label for="price">Price ($):</label>
        <input type="number" id="price" name="price" step="0.01" required><br>
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>



