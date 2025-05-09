<?php
session_start();
require_once 'DB.php';
require_once 'sessionManager.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['order_id'];
$db = new DB();
$conn = $db->connect();
$order_details = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id")->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Order Details</h1>";
echo "<table border='1'>";
echo "<tr><th>Product ID</th><th>Quantity</th><th>Price</th></tr>";
foreach ($order_details as $detail) {
    echo "<tr>";
    echo "<td>{$detail['product_id']}</td>";
    echo "<td>{$detail['quantity']}</td>";
    echo "<td>\${$detail['price']}</td>";
    echo "</tr>";
}
echo "</table>";
?>




