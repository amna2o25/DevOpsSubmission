<?php
session_start();
require_once 'DB.php';
require_once 'sessionManager.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $db = new DB();
    $conn = $db->connect();
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

    header("Location: manage_orders.php");
    exit;
} else {
    $order_id = $_GET['order_id'];

    echo "<h1>Update Order Status</h1>";
    echo "<form action='update_order_status.php' method='post'>";
    echo "<input type='hidden' name='order_id' value='{$order_id}'>";
    echo "<select name='status'>";
    echo "<option value='Pending'>Pending</option>";
    echo "<option value='Shipped'>Shipped</option>";
    echo "<option value='Completed'>Completed</option>";
    echo "</select>";
    echo "<button type='submit'>Update Status</button>";
    echo "</form>";
}
?>
