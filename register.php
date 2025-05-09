<?php
ob_start();
ini_set("display_errors", 1);
require_once 'DB.php';
require_once 'sessionManager.php';
$db = new DB;


if (isset($_POST['register'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confPass = $_POST['confPass'];

    if (empty($email) || empty($username) || empty($password) || empty($confPass)) {
        echo 'You must fill in all fields of the form!';
        die();
    }

    if ($password != $confPass) {
        echo 'Your passwords must match!';
        die();
    }

    if (strlen($email) < 5 || strlen($email) > 50) {
        echo 'Your email does not meet the length requirements!';
        die();
    }

    if (strlen($username) < 3 || strlen($username) > 12) {
        echo 'Your username does not meet the length requirements!';
        die();
    }

    if (strlen($password) < 3 || strlen($password) > 100) {
        echo 'Your password does not meet the length requirements!';
        die();
    }

    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        echo 'Your username contains invalid characters!';
        die();
    }

    $conn = $db->connect();
    if (!$conn) {
        echo 'Database failed to connect!';
        die();
    }

    $emailQuery = $conn->prepare("SELECT * FROM Users WHERE Email = :email");
    $emailQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $emailQuery->execute();

    if ($emailQuery->rowCount() > 0) {
        echo 'Email is already in use!';
        die();
    } else {
        $encryptedpassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = $conn->prepare("INSERT INTO Users (Email, Username, Password) VALUES (:email, :username, :password)");
        $insertQuery->bindParam(":email", $email, PDO::PARAM_STR);
        $insertQuery->bindParam(":username", $username, PDO::PARAM_STR);
        $insertQuery->bindParam(":password", $encryptedpassword, PDO::PARAM_STR);
        $insertQuery->execute();

        if ($insertQuery) {
            echo 'Account successfully created!';
        }
    }
}
ob_end_flush();
?>
<!DOCTYPE html>

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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method='post'>
                    <h2 class="mb-3">Sign Up</h2>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type='email' class="form-control" placeholder='Email...' name='email' required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type='text' class="form-control" placeholder='Username...' name='username' required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type='password' class="form-control" placeholder='Password...' name='password' required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="confPass">Confirm Password:</label>
                        <input type='password' class="form-control" placeholder='Confirm...' name='confPass' required autocomplete="new-password">
                    </div>
                    <button type='submit' class="btn btn-primary" name='register'>Sign-up</button>
                </form>
            </div>
        </div>
    </div>
</body>
<br>
<br>
<br>
<br>
<br>
</html><footer class="footer mt-auto py-3 bg-dark text-white text-center">
<p>&copy; 2024 Tyne Brew Coffee</p><br>
