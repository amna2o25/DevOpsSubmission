<?php

ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'DB.php';
require_once 'sessionManager.php';


if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$db = new DB();
$conn = $db->connect(); // Establish a connection to the database

// Fetch orders specific to the logged-in customer
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Customer Dashboard</h1>
<h2>Your Recent Orders</h2>
<table>
    <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Status</th>
        <th>Details</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= htmlspecialchars($order['id']) ?></td>
        <td><?= htmlspecialchars($order['order_date']) ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td><a href="view_order_details.php?order_id=<?= $order['id'] ?>">View Details</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="customer_profile.php">Edit Profile</a> | <a href="customer_support.php">Support</a>

<?php
// Always ensure to handle the closing of any database connections
$conn = null;
ob_end_flush();
?>
