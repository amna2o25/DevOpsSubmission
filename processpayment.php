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
    $currentYear = date('Y'); // Full four digit year
    $currentMonth = date('m');
    list($expMonth, $expYear) = explode('/', $cardDetails['card_expiry']);
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        echo "Error: The card's expiration date has passed.";
        return false;
    }

    // Simulate a successful payment process
    return true; // This is a stub; integrate with an actual payment gateway in production
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <title>Payment Processing - Tyne Brew Coffee</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <div class="container">
        <h1>Make a Payment</h1>
        <!-- Payment form should go here, if needed -->
    </div>
</body>
</html>










