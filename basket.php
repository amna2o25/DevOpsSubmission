<?php
ob_start();

ini_set('display_errors', 1);
require_once 'sessionManager.php';
require_once 'basketFunctions.php';
require_once 'DB.php';
require_once 'Product.php';




$db = new DB();

// Initialize basket and saved items if not set
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}
if (!isset($_SESSION['saved'])) {
    $_SESSION['saved'] = [];
}

// Fetch product details and handle actions
if (isset($_GET['action'], $_GET['id']) && is_numeric($_GET['id']))
{
    $productId = intval($_GET['id']);
    // $productDetails = $db->getProductDetails($productId);

    // if ($productDetails) {
        // if (!isset($_SESSION['basket'][$productId])) {
        //     $_SESSION['basket'][$productId] = [
        //         'Quantity' => 0,
        //         'Price' => $productDetails['Price'],
        //         'ProductName' => $productDetails['Name'],
        //         'Image' => $productDetails['Image'] ?? 'default_image.jpg'
        //     ];
        // }


        switch ($_GET['action']) {
            case 'add':
                $_SESSION['basket'][$productId]['Quantity']++;
                break;
            case 'subtract':
                if ($_SESSION['basket'][$productId]['Quantity'] > 1) {
                    $_SESSION['basket'][$productId]['Quantity']--;
                } else {
                    unset($_SESSION['basket'][$productId]);
                }
                break;
            case 'delete':
                unset($_SESSION['basket'][$productId]);
                break;
            case 'saveForLater':
                $_SESSION['saved'][$productId] = $_SESSION['basket'][$productId];
                unset($_SESSION['basket'][$productId]);
                break;
            case 'moveToBasket':
                if (!isset($_SESSION['basket'][$productId])) {
                    $_SESSION['basket'][$productId] = $_SESSION['saved'][$productId];
                } else {
                    $_SESSION['basket'][$productId]['Quantity'] += $_SESSION['saved'][$productId]['Quantity'];
                }
                unset($_SESSION['saved'][$productId]);
                break;
        
        header('Location: basket.php');
        exit;
    // } else {
    //     header('Location: basket.php?error=invalid_product');
    //     exit;
    // }
        }
    }

$totalPrice = array_sum(array_map(function ($item) {
    return $item['Price'] * $item['Quantity'];
}, $_SESSION['basket']));

if(isset($_POST['clear'])) {
    $_SESSION['basket'] = [];
    header('Location: basket.php');
    exit;
}

var_dump(isset($_SESSION['basket']));
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
    <link rel="stylesheet" href="style.CSS">
    <title>Your Basket</title>
    <style>
        .basket-product-image {
            width: 80px; /* Adjust the width as needed */
            height: auto; /* Maintain the aspect ratio */
        }
    </style>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>

<main class="container mt-8">
    <h2>Your Basket</h2>
    <?php if (!empty($_SESSION['basket'])): ?>
        <table class="table basket-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 0; $i < count($_SESSION['basket']); $i++): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($_SESSION['basket'][$i]['Image']) ?>" alt="<?= htmlspecialchars($product['ProductName']) ?>" class="basket-product-image"></td>
                        <td><?= htmlspecialchars($_SESSION['basket'][$i]['ProductName']) ?></td>
                        <td>
                            <?= $_SESSION['basket'][$i]['Quantity'] ?>
                            <a href="basket.php?action=add&id=<?= $i ?>" class="btn btn-primary">+</a>
                            <a href="basket.php?action=subtract&id=<?= $i ?>" class="btn btn-secondary">-</a>
                        </td>
                        <td>£<?= number_format($_SESSION['basket'][$i]['Price'] * $_SESSION['basket'][$i]['Quantity'], 2) ?></td>
                        <td>
                            <a href="basket.php?action=delete&id=<?= $i ?>" class="btn btn-danger">Remove</a>
                            <a href="basket.php?action=saveForLater&id=<?= $i ?>" class="btn btn-warning">Save for Later</a>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
</main><br>  
   </table>
        <div class="total-price">Total Price: £<?= number_format($totalPrice, 2) ?></div>
        <!-- Checkout Button -->
        <div class="checkout">
            <a href="checkout.php" class="btn btn-success">Checkout</a>
        </div>
    <?php else: ?>
        <p>Your basket is empty. <a href="home.php">Continue shopping</a></p>
    <?php endif; ?>
</main>
</footer>
</body>
</html>
</div>
    </body> 
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
    <br>
    <br>
    </html><footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p><br>

          









      
    







































