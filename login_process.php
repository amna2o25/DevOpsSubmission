<?php
ob_start();  // Turn on output buffering
ini_set("display_errors", 1);  // Enable display of errors for debugging


require_once 'sessionManager.php';  // Include your session management logic
require_once 'DB.php';  // Database connection and operations

$db = new DB();
$conn = $db->connect();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize_checkout'])) {
    // Redirect to process payment page without checking login
    header('Location: process_payment.php');
    exit();
}

ob_end_flush();  // Send output buffer and turn off output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Tyne Brew Coffee - Shipping Details</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <div class="container">
        <h2 class="mb-4">Shipping Details</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="finalize_checkout" value="1">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="address">Street Address *</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="country">Country *</label>
                <select class="form-control" id="country" name="country">
                    <option>United Kingdom</option>
                </select>
            </div>
            <div class="form-group">
                <label for="city">Town / City *</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="postcode">Post Code *</label>
                <input type="text" class="form-control" id="postcode" name="postcode" required>
            </div>
            <div class="form-group">
                <label for="delivery_note">Delivery Note *</label>
                <input type="text" class="form-control" id="delivery_note" name="delivery_note" required>
            </div>
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script><br>
    <br><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <footer class="footer mt-auto py-3 bg-dark text-white text-center">
        <p>&copy; 2024 Tyne Brew Coffee</p>
    </footer>
</body>
</html>





