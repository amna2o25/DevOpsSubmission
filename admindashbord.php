<?php

ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'DB.php';
require_once 'sessionManager.php';

$db = new DB();

// Redirect non-admins back to login or another appropriate page
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: Login.php");
    exit;
}

// Connect to database
$conn = $db->connect();

// Handle POST request to update or delete orders
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $orderId = $_POST['orderId'];
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bindParam(1, $orderId);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $orderId = $_POST['orderId'];
        $newStatus = $_POST['status'];
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bindParam(1, $newStatus);
        $stmt->bindParam(2, $orderId);
        $stmt->execute();
    }
}

// Fetch all orders to display
$stmt = $conn->prepare("SELECT * FROM orders");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_end_flush();
?>
<!-- HTML and Bootstrap to display the dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<header>
<?php include 'navbar.php'; ?>
</header>
<div class="container">
<h1>Admin Dashboard</h1>
<!-- Table to display orders -->
<table class="table">
<thead>
<tr>
<th>Order ID</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($orders as $order): ?>
<tr>
<td><?= $order['order_id'] ?></td>
<td><?= $order['status'] ?></td>
<td>
<form action="adminDashboard.php" method="post">
<input type="hidden" name="orderId" value="<?= $order['order_id'] ?>">
<button type="submit" name="delete" class="btn btn-danger">Delete</button>
<button type="submit" name="update" class="btn btn-primary">Update Status</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
<p>&copy; 2024 Tyne Brew Coffee</p>
</footer>
</body>
</html>


