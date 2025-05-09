<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Icons</title>
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: #fffa;
            display: flex;
            justify-content: space-between;
            align-items: left;
            padding: 10px 20px;
        }
        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .menu li {
            margin-right: 20px;
        }
        .menu li a {
            color: #fffa;
            text-decoration: none;
            display: flex;
            align-items: left;
        }
        .menu li a i {
            margin-right: 8px;
        }
        .menu li a:hover {
            color: #f0a500;
        }
        .search {
            display: flex;
            align-items: left;
        }
        .search input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        .search input[type="submit"] {
            padding: 5px 10px;
            background-color: #f0a500;
            border: none;
            color: #fffa;
            cursor: pointer;
            border-radius: 3px;
        }
        .search input[type="submit"]:hover {
            background-color: #d98b00;
        }
    </style>
</head>
<body>
    <nav>
        <ul class="menu">
            <li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="aboutUs.php"><i class="fas fa-info-circle"></i>About Us</a></li>
            <li><a href="basket.php"><i class="fas fa-shopping-basket"></i>Basket</a></li>
            <li><a href="register.php"><i class="fas fa-user-plus"></i>Register</a></li>
            <li><a href="Login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>
            <li><a href="admin.php"><i class="fas fa-user-shield"></i>Admin</a></li>
        </ul>
        <div class="search">
            <form method="POST" id="search-bar" class="search-form">
                <input type="text" name="search" placeholder="Search for products..." value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">
                <input type="submit" value="Search">
            </form>
        </div>
    </nav>
</body>
</html>




