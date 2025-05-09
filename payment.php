<?php

ob_start();
ini_set('display_errors', 1);


require_once 'sessionManager.php'; // Handles session-related tasks
require_once 'DB.php'; // Database interaction
//require_once 'checkout.php'; // Ensure this is actually needed or it may cause unexpected behavior

$db = new DB();

function processPayment($cardDetails) {
    if (empty($cardDetails['card_number']) || empty($cardDetails['card_name']) || empty($cardDetails['card_expiry']) || empty($cardDetails['card_cvv'])) {
        return false;
    }

    // Date validation
    $currentYear = date('y');
    $currentMonth = date('m');
    list($expMonth, $expYear) = explode('/', $cardDetails['card_expiry']);
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        return false;
    }

    // Here, integrate with your payment gateway
    return true;
}

$paymentSuccessful = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardDetails = [
        'card_name' => $_POST['card_name'] ?? '',
        'card_number' => $_POST['card_number'] ?? '',
        'card_expiry' => $_POST['card_expiry'] ?? '',
        'card_cvv' => $_POST['card_cvv'] ?? ''
    ];

    $paymentSuccessful = processPayment($cardDetails);

    if ($paymentSuccessful) {
        unset($_SESSION['basket']);
        $_SESSION['payment_success'] = true;
        header('Location: success_page.php'); // Redirect to a success page
        exit;
    } else {
        $_SESSION['payment_success'] = false;
        header('Location: checkout.php?error=payment_failed'); // Redirect back with error
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Tyne Brew Coffee</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3>Payment Information</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="card_name">Cardholder Name:</label>
                        <input type="text" class="form-control" id="card_name" name="card_name" required placeholder="Name as it appears on card">
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number:</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" required placeholder="Valid card number">
                    </div>
                    <div class="form-group">
                        <label for="card_expiry">Expiry Date:</label>
                        <input type="text" class="form-control" id="card_expiry" name="card_expiry" required placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                        <label for="card_cvv">CVV:</label>
                        <input type="text" class="form-control" id="card_cvv" name="card_cvv" required placeholder="CVV">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>
                </form>
            </div>

            <div class="col-md-4">
                <h3>Order Summary</h3>
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <?php
                        if (!empty($_SESSION['basket'])) {
                            foreach ($_SESSION['basket'] as $item): ?>
                                <li class="list-group-item">
                                    <?= htmlspecialchars($item['ProductName']) ?> - £<?= number_format($item['Price'] * $item['Quantity'], 2) ?>
                                    <br>Quantity: <?= $item['Quantity'] ?>
                                </li>
                            <?php endforeach;
                        } else {
                            echo "<li class='list-group-item'>Your basket is empty.</li>";
                        }
                        ?>
                    </ul>
                </div>
                <a href="finalize_checkout.php" class="btn btn-primary btn-block mt-3">Complete Checkout</a>
            </div>
        </div>
    </div>

    <br><footer><br>
        <p>&copy; 2024 Tyne Brew Coffee</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

