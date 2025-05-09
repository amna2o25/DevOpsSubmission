<?php 
ob_start();
ini_set('display_errors', 1);

require_once 'sessionManager.php'; // Ensures session management
require_once 'DB.php'; // Handles database interactions




function processPayment($cardDetails) {
    // Check for any empty required fields
    if (empty($cardDetails['card_number']) || empty($cardDetails['card_name']) ||
        empty($cardDetails['card_expiry']) || empty($cardDetails['card_cvv'])) {
        return false;
    }

    $currentYear = date('Y');
    $currentMonth = date('m');
    list($expMonth, $expYear) = explode('/', $cardDetails['card_expiry']);
    $expYear = 2000 + (int)$expYear; // Convert YY to YYYY
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        return false;
    }
    // Assuming card validation passes
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardDetails = [
        'card_name' => $_POST['card_name'] ?? '',
        'card_number' => $_POST['card_number'] ?? '',
        'card_expiry' => $_POST['card_expiry'] ?? '',
        'card_cvv' => $_POST['card_cvv'] ?? ''
    ];

    
        if (processPayment($cardDetails)) {
            unset($_SESSION['basket']); // Clear the basket after successful payment
            $_SESSION['success'] = true;
            header('Location: success.php'); // Ensure this is the intended target
            exit;
        } else {
            $_SESSION['payment_error'] = "Failed to process payment. Please try again.";
            header('Location: checkout.php?error=payment_failed');
            exit;
        }
    }
    

ob_end_flush();

$db = new DB;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Payment Processing - Tyne Brew Coffee</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <div class="container">
        <h2 class="mb-3">Enter Payment Details</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="card_name">Cardholder Name:</label>
                <input type="text" class="form-control" id="card_name" name="card_name" required placeholder="Full Name as on Card">
            </div>
            <div class="form-group">
                <label for="card_number">Card Number:</label>
                <input type="text" class="form-control" id="card_number" name="card_number" required placeholder="Card Number" pattern="\d{16}" title="Card number should have 16 digits">
            </div>
            <div class="form-group">
                <label for="card_expiry">Expiry Date (MM/YY):</label>
                <input type="text" class="form-control" id="card_expiry" name="card_expiry" required placeholder="MM/YY" pattern="\d{2}/\d{2}" title="Enter date in MM/YY format">
            </div>
            <div class="form-group">
                <label for="card_cvv">CVV:</label>
                <input type="text" class="form-control" id="card_cvv" name="card_cvv" required placeholder="CVV" pattern="\d{3}" title="CVV should have 3 digits">
            </div>
            <button type="submit" class="btn btn-primary">Submit Payment</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>















