
<?php 

//Display errors
ini_set('display_errors', 1);

ob_start();
//require_once 'sessionManager.php';
//require_once 'basketFunctions.php';
require_once 'DB.php';
require_once 'Product.php';

session_start();

// Create database connection
$db = new DB();
$Products = [];

//Clear basket
//$_SESSION['basket'] = array();

//Check if basket exists
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = array();
}

// Attempt to query products from database
$conn = $db->connect();
$searchQuery = "SELECT * FROM tbl_products";
$params = [];

if(!empty($_POST['Increment']))
{
    if(!isset($_SESSION['counter']))
    {
        $_SESSION['counter'] = 0;
    }

    $_SESSION['counter']++;
}

if (!empty($_POST['search'])) {
    // Add search condition if search parameter is provided
    $search = '%' . $_POST['search'] . '%';
    $searchQuery .= " WHERE ProductName LIKE :search";
    $params[':search'] = $search;
}

$stmt = $conn->prepare($searchQuery);

// Bind parameters and execute SQL query
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}

$stmt->execute();

// Fetch products and store them in array
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $Products[] = new Product($row['ProductID'], $row['ProductName'], $row['Image'], $row['Price']);
}
    
//  catch (Exception $e) {
//     // Handle potential exceptions
//     error_log('Error: ' . $e->getMessage());
//     die('Error processing your request.');


//Handle adding products to basket
if (isset($_POST['update'])) 
{
    $id = $_POST['id'];
    $quantity = $_POST['quantity'] ?? 1; // Default ,quantity to 1 if not specified
    //add($db, $id, $quantity);

    $productFound = false;

    for($i = 0; $i < count($_SESSION['basket']); $i++)
    {
        if($_SESSION['basket'][$i]['ProductID'] == $id)
        {
            $productFound = true;
            $_SESSION['basket'][$i]['Quantity'] += $quantity;
            break;
        }
    }

    if(!$productFound)
    {
        $query = $db->connect()->prepare("SELECT * FROM tbl_products WHERE ProductID = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $_SESSION['basket'][] = [
            'ProductID' => $row['ProductID'],
            'ProductName' => $row['ProductName'],
            'Image' => $row['Image'],
            'Price' => $row['Price'],
            'Quantity' => $quantity
        ];
    }

    header('Location: home.php'); // Redirect to prevent form resubmission
}

echo "Basket Items: " . count($_SESSION['basket']);

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
    <title>Tyne Brew Coffee</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>   
    </header>
    <main>
    <br> <section class="info-box"><br>
        <br> <h1>Welcome to Tyne Brew Coffee</h1><br>

        <br><h3> At Tyne Brew Coffee, we believe coffee is an experience. We offer expertly crafted brews made with premium beans and techniques for exceptional flavors. Our irresistible treats, like croissants and cookies, perfectly pair with your favourite coffee. Bring the café home with our top-quality machines, reusable cups, and freshly roasted beans. Skip the line and order online for quick pick up or delivery. Join the Tyne Brew community and discover your ultimate coffee destination. <h3><br>
        </section>
        <br><h3>Our Products</h3><br>
        <section id="products" class="row text-center">
            <?php if (!empty($Products)): ?>
                <?php foreach ($Products as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="<?= htmlspecialchars($product->image()) ?>" alt="<?= htmlspecialchars($product->name()) ?>" class="product-image img-fluid">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product->name()) ?></h5>
                                <p class="card-text">£<?= htmlspecialchars($product->price()) ?></p>

                                <form method="POST" action="home.php" class="d-flex align-items-center">
                    
                                    <button type="button" class="btn btn-secondary quantity-minus">-</button>
                                    <input type="number" name="quantity" value="1" min="1" class="quantity-input form-control mx-2" style="width: 50px; text-align: center;">
                                    <button type="button" class="btn btn-secondary quantity-plus">+</button>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($product->id()) ?>">
                                    <button type="submit" name="update" value="Add" class="btn btn-primary add-to-basket-btn ml-2">Add to Basket</button>
                                    
                    
                                </form>
                                <br><a href="information.php?ProductID=<?= htmlspecialchars($product->id()) ?>" class="btn btn-info">Product Info</a><br>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found matching your search.</p>
            <?php endif; ?>
        </section>
    </main><br>
<br>
<br>
<footer>
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <h2>Contact Us</h2>
    <p>Email: tynebrew@gmail.com</p>
    <p>Phone: 07123456789</p>
    <p>Address: 1000 Kenton Road, NE3 4NT</p>
   <p>&copy; 2024 Tyne Brew Coffee</p>
    <div class="social-links">
        <a href="https://instagram.com" class="social-link"><i class="fab fa-instagram"></i></a>
        <a href="https://api.whatsapp.com/send?phone=yourphonenumber" class="social-link"><i class="fab fa-whatsapp"></i></a>
        <a href="https://facebook.com" class="social-link"><i the="fab fa-facebook-f"></i></a>
    </div>
</footer>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const minusButtons = document.querySelectorAll('.quantity-minus');
    const plusButtons = document.querySelectorAll('.quantity-plus');

    minusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.nextElementSibling; 
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    plusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling; 
            input.value = parseInt(input.value) + 1;
        });
    });
});


</script>
</body>
</html>




































 
































