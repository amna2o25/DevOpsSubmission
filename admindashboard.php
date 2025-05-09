<?php
ob_start();
ini_set("display_errors", 1);



require_once 'sessionManager.php';



if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

require_once 'DB.php';
$db = new DB();

// Fetch example data (number of users, orders, etc.)
$conn = $db->connect();
$userCount = 0;
$orderCount = 0;

if ($conn) {
    $userResult = $conn->query("SELECT COUNT(*) AS userCount FROM users");
    $orderResult = $conn->query("SELECT COUNT(*) AS orderCount FROM orders");

    if ($userResult && $orderResult) {
        $userCount = $userResult->fetch_assoc()['userCount'];
        $orderCount = $orderResult->fetch_assoc()['orderCount'];
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .card-title {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="ml-auto">
            <span class="navbar-text text-white">Welcome, <?= htmlspecialchars($_SESSION['admin_name']); ?></span>
            <a href="logout.php" class="btn btn-outline-light ml-3">Logout</a>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- User Stats -->
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header">
                        Total Users
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $userCount ?></h5>
                        <p class="card-text">Number of registered users.</p>
                    </div>
                </div>
            </div>

            <!-- Orders Stats -->
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header">
                        Total Orders
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $orderCount ?></h5>
                        <p class="card-text">Number of orders placed.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="manageUsers.php" class="btn btn-primary btn-block">Manage Users</a>
            </div>
            <div class="col-md-4">
                <a href="manageOrders.php" class="btn btn-primary btn-block">Manage Orders</a>
            </div>
            <div class="col-md-4">
                <a href="settings.php" class="btn btn-primary btn-block">Settings</a>
            </div>
        </div>
    </div>
</body>
</html>

   


  




