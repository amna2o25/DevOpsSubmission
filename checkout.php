<?php
ob_start();
ini_set("display_errors", 1);
require_once 'sessionManager.php';
require_once 'DB.php';

$db = new DB();
$conn = $db->connect();

// Handling adding to basket and redirecting to home.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_basket') {
    $itemId = $_POST['item_id'] ?? null;
    $itemPrice = $_POST['item_price'] ?? null;
    $itemName = $_POST['item_name'] ?? null;
    $itemQuantity = $_POST['item_quantity'] ?? 0;

    if (!isset($_SESSION['basket'][$itemId])) {
        $_SESSION['basket'][$itemId] = [
            'Quantity' => $itemQuantity,
            'Price' => $itemPrice,
            'ProductName' => $itemName,
            'Image' => 'default_image.jpg'  // Assuming a default image path
        ];
    } else {
        $_SESSION['basket'][$itemId]['Quantity'] += $itemQuantity;
    }
   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize_checkout'])) {
    var_dump($_POST); // See what data is received
    die('Redirecting to payment...'); // Check if this part is reached
    header('Location: process_payment.php');
    exit();
}


ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Tyne Brew Coffee - Checkout</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <div class="container">
        <h3>Enter your email address or Sign In</h3>
        <form action="login_process.php" method="post">
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Email Address">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                <small id="emailHelp" class="form-text text-muted">If you are a returning customer, please enter your password to continue.</small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Sign In</button>
                <a href="#" class="small">Forgot Your Password?</a>
            </div>
        </form>
    </div>

   

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
</div>
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
        <p>&copy; 2024 Tyne Brew Coffee</p>
    </footer>















































