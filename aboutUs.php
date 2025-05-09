<?php
ob_start();

require_once 'sessionManager.php';
require_once 'DB.php';
require 'Product.php';

$db = new DB();
$connection = $db->connect(); // Establish and reuse connection

// Initialize an array to hold products
$Products = array();

// Handle search query if provided
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = htmlspecialchars($_POST['search']);
    $query = $connection->prepare("SELECT * FROM tbl_products WHERE ProductName LIKE :search");
    $query->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
    // Fetch all products if no search query
    $query = $connection->prepare("SELECT * FROM tbl_products");
}

if ($query->execute()) {
    // Fetch all products
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $ProductID = $row['ProductID'];
        $ProductName = $row['ProductName'];
        $Image = $row['Image'];
        $Price = $row['Price'];

        $Products[] = new Product($ProductID, $ProductName, $Image, $Price);
    }
} else {
    error_log('Database query failed: ' . $query->errorInfo()[2]);
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
    <title>Tyne Brew Coffee</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>
    <main>
    <main>
    <!-- About Us Section -->
    <section id="about-us" class="my-5">
        <div class="container">
            <h1 class="text-center mb-4">About Us</h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="about-box">
                        <h1>Quality</h1>
                        <h3>At Tyne Brew, quality is our top priority. We meticulously source the finest, ethically-grown coffee beans and expertly roast and serve them with great care, ensuring an exceptional coffee experience in every cup.</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-box">
                        <h1>Community</h1>
                        <h3>Building lasting, meaningful relationships with our customers and local communities is at the heart of our mission.</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-box">
                        <h1>Innovation</h1>
                        <h3>Driven by a relentless pursuit of excellence, we constantly experiment with new and innovative flavor profiles and brewing techniques.</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer class="bg-light text-center text-lg-start">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h3 class="text-uppercase">Location</h3>
                <h5>1000 Kenton Road, NE3 4NT</h5>
                <h5><i class="fas fa-map-marker-alt"></i> Newcastle, UK</h5>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h3 class="text-uppercase">Contact</h3>
                <h5><i class="fas fa-phone"></i> 07123 456789</h5>
                <h5><i class="fas fa-envelope"></i> tynebrew@gmail.com</h5>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h3 class="text-uppercase">Opening Hours</h3>
                <h5>Monday to Saturday: 9:00 AM - 9:00 PM</h5>
                <h5>Sunday: Closed</h5>
            </div>
            </div>
              <br>
              <br>
              <br>
              <br>
            <div class="ask">
               <br> <h2>Become a Part of Tyne Brew Coffee</h2>
               Join the Tyne Brew Coffee community today and become part of our vibrant family.<br>
                We eagerly look forward to welcoming you and collaborating with you to create a truly delightful and memorable future. <br>
                Together, let's brew something truly special and extraordinary at Tyne Brew Coffee!<br>
                <br>
                <br>
                      <h3 class="text-uppercase">Follow Us</h3>
                      <a href="https://instagram.com" class="btn btn-outline-dark btn-floating m-1" role="button"><i class="fab fa-instagram"></i></a>
                <a href="https://api.whatsapp.com/send?phone=yourphonenumber" class="btn btn-outline-dark btn-floating m-1" role="button"><i class="fab fa-whatsapp"></i></a>
                <a href="https://facebook.com" class="btn btn-outline-dark btn-floating m-1" role="button"><i class="fab fa-facebook-f"></i></a>
                     
                    </div>
            </div>
</html>

<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p><br>
              
                
            </div>
        </div>
    </div>
</footer>


















