<?php
ob_start();

// Disable error reporting in production environment
ini_set('display_errors', 0);
error_reporting(0);

require_once 'sessionManager.php';  // This will handle session starting and security settings
require_once 'DB.php';



$db = new DB();
$conn = $db->connect();

if (!isset($_SESSION['customer_id'])) {
    // Save the target URL in the session or pass it as a query parameter
    $_SESSION['redirect_url'] = 'customer_orders.php';
    header("Location: Login.php");
    exit;
}


// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("
    SELECT o.orderID, o.orderDate, o.status, o.details, c.name as CustomerName
    FROM orders_tbl o
    JOIN customers_tbl c ON o.customerID = c.id
    WHERE o.customerID = ?
    ORDER BY o.orderDate DESC
");
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Orders</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h1>Customer Orders</h1>
<table>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Details</th>
        <th>View Details</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= htmlspecialchars($order['orderID']) ?></td>
        <td><?= htmlspecialchars($order['CustomerName']) ?></td>
        <td><?= htmlspecialchars($order['orderDate']) ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td><?= htmlspecialchars($order['details']) ?></td>
        <td><a href="view_order_details.php?order_id=<?= $order['orderID'] ?>">View Details</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

<?php
$conn = null;
ob_end_flush();
?>




