<?php
ini_set('display_errors', 1);
require 'DB.php';

$db = new DB;

session_start();

if(isset($_POST['submit']))
{
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username FROM MBC WHERE username = :username AND password = :password";
        echo $sql;
        $query = $db->connect()->prepare($sql);
        $query->bindParam(':username',$username, PDO::PARAM_STR);
        $query->bindParam(':password',  $password, PDO::PARAM_STR);

        $query->execute();
        
        if($query->rowCount() > 0)
        {
            $_SESSION['username'] = $username;
            header('Location: sqlinjectionSuccess.php');
        }
        else
        {
            echo "Incorrect details.";
        }
    }
    
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


<h1>Login Page Secured</h1>
<form method="post">
<label for="username">Username:</label><br>
<input type="text" placeholder="please enter your username.."name="username"id="username">
<br><label>Password: </label><br>
<input type="password"placeholder="please enter your Password..." name="password" id="password"><br>
<input type="submit" name="submit" value="Login">
</form>

</body>      
</html>