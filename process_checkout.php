<?php
session_start(); // Ensuring the session starts at the beginning of the script
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); // Report all PHP errors for debugging

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

// If the method is not POST, or no data was submitted
echo "Invalid access method.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Process Checkout</title>
</head>
<body>
    <h1>Payment Processing</h1>
    <p>Please enter your payment details.</p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="text" name="card_name" placeholder="Cardholder Name" required><br>
        <input type="text" name="card_number" placeholder="Card Number" required><br>
        <input type="text" name="card_expiry" placeholder="MM/YY" required><br>
        <input type="text" name="card_cvv" placeholder="CVV" required><br>
        <button type="submit">Submit Payment</button>
    </form>
</body>
</html>




