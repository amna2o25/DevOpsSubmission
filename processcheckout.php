<?php

ob_start();
ini_set('display_errors', 1);


require_once 'sessionManager.php'; // Manages session-related tasks
require_once 'DB.php'; // Database interactions

function processPayment($cardDetails) {
    // Check for any empty required fields
    if (empty($cardDetails['card_number']) || empty($cardDetails['card_name']) ||
        empty($cardDetails['card_expiry']) || empty($cardDetails['card_cvv'])) {
        echo "Error: All card details must be provided.";
        return false;
    }

    // Validate expiration date
    $currentYear = date('y');
    $currentMonth = date('m');
    list($expMonth, $expYear) = explode('/', $cardDetails['card_expiry']);
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        echo "Error: The card's expiration date has passed.";
        return false;
    }

    // Simulate a successful payment process
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardDetails = [
        'card_name' => $_POST['card_name'] ?? '',
        'card_number' => $_POST['card_number'] ?? '',
        'card_expiry' => $_POST['card_expiry'] ?? '',
        'card_cvv' => $_POST['card_cvv'] ?? ''
    ];

    if (processPayment($cardDetails)) {
        // Assuming payment processing was successful
        unset($_SESSION['basket']); // Clear the basket after successful payment
        $_SESSION['payment_success'] = true; // Flag payment as successful
        header('Location: success_page.php'); // Redirect to a success page
        exit;
    } else {
        $_SESSION['payment_error'] = "Failed to process payment. Please try again.";
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

<header>
        <?php include 'navbar.php'; ?>
    </header>
        
 

   

<body>
    <div class="form-container">
        <h1>Payment Processing</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
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
</body>
</html>





