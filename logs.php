if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProduct'])) {
    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];

    $query = $conn->prepare("INSERT INTO Products (ProductName, Description, Price, Stock) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssdi", $name, $description, $price, $stock);
    $query->execute();
}

