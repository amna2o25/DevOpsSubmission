<?php
ob_start();
require_once 'DB.php';
require_once 'sessionManager.php';

// Redirect if the user is not logged in or not an admin
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: Login.php');
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_VALIDATE_FLOAT);
    $productDescription = filter_input(INPUT_POST, 'productDescription', FILTER_SANITIZE_STRING);
    $productImage = filter_input(INPUT_POST, 'productImage', FILTER_SANITIZE_URL);

    // Check required fields
    if (!$productName || !$productPrice || !$productDescription) {
        $_SESSION['error_message'] = 'Please fill in all required fields correctly.';
        header('Location: addproduct.php');
        exit;
    }

    // Create database connection
    $db = new DB();
    $conn = $db->connect();

    if ($conn) {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO tbl_products (ProductName, Price, Description, Image) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $productName);
        $stmt->bindParam(2, $productPrice);
        $stmt->bindParam(3, $productDescription);
        $stmt->bindParam(4, $productImage);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Product added successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to add product.';
        }
    } else {
        $_SESSION['error_message'] = 'Database connection failed.';
    }

    // Redirect back to the product addition page
    header('Location: addproduct.php');
    exit;
}

// Redirect if accessed directly without posting the form
else {
    header('Location: addproduct.php');
    exit;
}
ob_end_flush();
?>
