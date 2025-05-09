<?php
ob_start();
// Start the session at the very beginning
ini_set("display_errors", 1); // For debugging, remove or turn off when in production

require_once 'DB.php';
require_once 'sessionManager.php';
$db = new DB();

// Initialize the error message variable to avoid undefined notices

if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Consider using filter_input() here as well

    if ($email && $password) { // Check if variables are not empty
        $conn = $db->connect();
        if ($conn) {
            $query = $conn->prepare("SELECT * FROM Users WHERE Email = :email");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                $user = $query->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['Password'])) {
                    $_SESSION['user_id'] = $user['UserID'];
                    $_SESSION['username'] = $user['Username'];

                    $redirect = filter_input(INPUT_GET, 'redirect', FILTER_SANITIZE_URL) ?? 'home.php'; // Validate and sanitize input
                    header("Location: $redirect");
                    exit;
                } else {
                    $errorMessage = 'Incorrect password!';
                }
            } else {
                $errorMessage = 'No account found with this email!';
            }
        } else {
            $errorMessage = 'Database connection failed!';
        }
    } else {
        $errorMessage = 'Please fill all fields!';
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
    <title>Tyne Brew Coffee</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>
    <main>
        <div class="container mt-4">
            <h2>Login to Your Account</h2><br>
            <form method="POST">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? 'home.php') ?>">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </div>
            </form>

            <?php if (!empty($errorMessage)): ?>
                <p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </main>
    <footer>
        <!-- Footer content -->
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
    </html><footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <p>&copy; 2024 Tyne Brew Coffee</p><br>





