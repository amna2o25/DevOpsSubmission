<?php

    session_start();

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Reflected</title>
</head>
<body>
    <h2>XSS Reflected Attack Example</h2>
 
 
    <form method="GET">
        <input type="text" placeholder="Enter Search Term..." name="search"><br>
        <input type="submit" name="submit" value="search">
    </form>
 
    <p>
        <?php if(isset($_GET['submit']))
        {
            echo "you have search for" . htmlspecialchars($_GET['search']);
        }
        ?>
    </p>
 
</body>
</html>