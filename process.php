<?php

ob_start();
ini_set('display_errors', 1);


require_once 'sessionManager.php';
require_once 'DB.php';

function processPayment($cardDetails) {
    // Simulated payment process
    // In real-world scenario, integrate with a payment API here
    if (empty($cardDetails['card_number']) || empty($cardDetails['card_name']) || empty($cardDetails['card_expiry']) || empty($cardDetails['card_cvv'])) {
        return false;
    }

    // Validate expiration date
    $currentYear = date('y');
    $currentMonth = date('m');
    list($expMonth, $expYear) = explode('/', $cardDetails['card_expiry']);
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        return false;
    }

    return true; // Assume payment is processed
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardDetails = [
        'card_name' => $_POST['card_name'] ?? '',
        'card_number' => $_POST['card_number'] ?? '',
        'card_expiry' => $_POST['card_expiry'] ?? '',
        'card_cvv' => $_POST['card_cvv'] ?? ''
    ];

    if (processPayment($cardDetails)) {
        // Clear basket and set success indicator
        unset($_SESSION['basket']);
        header('Location: success_page.php');
        exit;
    } else {
        // Redirect back with error
        header('Location: checkout.php?error=payment_failed');
        exit;
    }
}
?>


