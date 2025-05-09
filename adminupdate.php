<?php
session_start();
ob_start();

require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Check if form is submitted and the request is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data safely
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

    try {
        // Create database connection
        $conn = $db->connect();

        // SQL to update product in the database
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$name, $description, $price, $category, $product_id]);

            if ($stmt->rowCount()) {
                // Update successful, redirect to product details page
                header("Location: admin.php?product_id=$product_id&success=update");
                exit();
            } else {
                // No data changed
                echo "No changes were made to the product.";
            }
        } else {
            // Display error message
            throw new Exception("Failed to prepare the SQL statement.");
        }
    } catch (Exception $e) {
        // Connection or SQL error
        echo "Error: " . $e->getMessage();
    } finally {
        // Ensure the connection is closed
        $conn = null;
    }
} else {
    // Not a POST request, redirect to homepage or display an error message
    header("Location: home.php");
    exit();
}

ob_end_flush();
?>


