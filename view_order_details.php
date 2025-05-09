<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'sessionManager.php';  // This will handle session starting and security settings
require_once 'DB.php';  // Ensures secure database connection

// Start output buffering
ob_start();

if (!isset($_SESSION['customer_id'])) {
    // Ensure to include query parameters if needed
    $_SESSION['redirect_url'] = 'view_order_details.php?order_id=' . $_GET['order_id'];
    header("Location: Login.php");
    exit;
}

// Ensure an order ID is provided
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "<p>Order ID is required.</p>";
    exit;
}

$order_id = $_GET['order_id'];
$db = new DB();
$conn = $db->connect();

// Assuming you have already included your database connection and session management
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
if (!$order_id) {
    echo "Order ID is required.";
    exit;  // Consider redirecting to an error page or another appropriate page
}

$stmt = $conn->prepare("SELECT * FROM Orders WHERE orderID = ? AND customerID = ?");
$stmt->execute([$order_id, $_SESSION['customer_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "No order found.";
    exit;  // Or redirect to an appropriate page
}


// Fetch the specific order from the database using prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM Orders WHERE orderID = ? AND customerID = ?");
$stmt->execute([$order_id, $_SESSION['customer_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<p>No order found for this ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Order Details</h1>
    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Order ID</td>
            <td><?= htmlspecialchars($order['orderID']) ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?= htmlspecialchars($order['orderDate']) ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?= htmlspecialchars($order['status']) ?></td>
        </tr>
        <tr>
            <td>Details</td>
            <td><?= htmlspecialchars($order['details']) ?></td>
        </tr>
    </table>
    <a href="customer_orders.php">Back to Orders</a>
</body>
</html>

<?php
$conn = null;  // Close the database connection
ob_end_flush();  // Send output buffer and turn off output buffering
?>


