<?php





echo "This is the admin functions file. It is being included successfully.";

// Function to add a product to the database
function addProduct($conn, $name, $image, $price, $description) {
    try {
        $stmt = $conn->prepare("INSERT INTO tbl_products (ProductName, Image, Price, Description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $image, $price, $description]);
        return "Product added successfully!";
    } catch (PDOException $e) {
        return "Error adding product: " . $e->getMessage();
    }
}

// Function to update a product in the database
function updateProduct($conn, $productID, $name, $image, $price, $description) {
    try {
        $stmt = $conn->prepare("UPDATE tbl_products SET ProductName = ?, Image = ?, Price = ?, Description = ? WHERE ProductID = ?");
        $stmt->execute([$name, $image, $price, $description, $productID]);
        return "Product updated successfully!";
    } catch (PDOException $e) {
        return "Error updating product: " . $e->getMessage();
    }
}

// Function to delete a product from the database
function deleteProduct($conn, $productID) {
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_products WHERE ProductID = ?");
        $stmt->execute([$productID]);
        return "Product deleted successfully!";
    } catch (PDOException $e) {
        return "Error deleting product: " . $e->getMessage();
    }
}

// Function to fetch all products from the database (useful for listing in admin panel)
function getAllProducts($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error fetching products: " . $e->getMessage();
    }
}
?>




