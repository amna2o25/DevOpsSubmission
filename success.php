<?php
ob_start();
ini_set('display_errors', 1);

require_once 'sessionManager.php'; // Ensures session management
require_once 'DB.php'; // Handles database interactions

$db = new DB;


$userID = $_SESSION['user_id'];


$query = $db->connect()->prepare("INSERT INTO Orders (userID) VALUES (:userID)");
$query->bindParam("userID", $userID, PDO::PARAM_STR);
$query->execute();

$_SESSION['basket'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['success'] = true; // Force success for debugging
    header('Location: success.php');
    exit();
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5 text-center">
    <h1 class="text-success">Payment Successful!</h1>
    <h2>Thank you for your order from Tyne Brew Coffee. Your payment has been processed successfully.</h2>
    <a href="home.php" class="btn btn-primary mt-3">Return to Home</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>









