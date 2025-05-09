<?php

ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'DB.php';
require_once 'sessionManager.php';


if (!isset($_SESSION['customer_id'])) {
    header("Location: admin.php");  // Ensure the user is logged in
    exit;
}

$db = new DB();
$conn = $db->connect();  // Connect to the database

// Fetch all orders belonging to the logged-in customer
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="style.css">  <!-- Link to CSS for styling -->
</head>
<body>
<h1>Your Order History</h1>

<?php if ($orders): ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><a href="view_order_details.php?order_id=<?= $order['id'] ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>You have no orders yet.</p>
<?php endif; ?>

</body>
</html>
