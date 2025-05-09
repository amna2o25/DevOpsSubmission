<?php

ob_start();

require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Check if product ID is provided
if (!isset($_GET['product_id'])) {
    header("Location: home.php");
    exit();
}

$product_id = $_GET['product_id'];

try {
    // Create database connection
    $conn = $db->connect();

    // Prepare statement to delete associated comments or reviews for the product
    $stmt = $conn->prepare("DELETE FROM comments WHERE product_id = ?");
    $stmt->bindParam(1, $product_id, PDO::PARAM_INT);

    // Begin transaction
    $conn->beginTransaction();

    if ($stmt->execute()) {
        // Comments or reviews deleted successfully, proceed to delete product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bindParam(1, $product_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Commit transaction
            $conn->commit();

            // Product deleted successfully
            header("Location: admin.php?success=deletion");
            exit();
        } else {
            // Roll back transaction if product deletion fails
            $conn->rollBack();
            echo "Error deleting product.";
        }
    } else {
        // Roll back transaction if comment deletion fails
        $conn->rollBack();
        echo "Error deleting comments or reviews.";
    }
} catch (PDOException $e) {
    // Roll back transaction in case of any PDO error
    $conn->rollBack();
    die("Database error: " . $e->getMessage());
}

// Close connection
$conn = null;
ob_end_flush();
?>




