<?php
 
 ob_start();

 
 require_once
    require 'DB.php';
    ini_set("display_errors", 1);

    $db = new DB;
 
    if(isset($_POST['submit']))
    {
        $query = $db->connect()->prepare("INSERT INTO SM (comment) VALUES (:comment)");
        $query->bindParam(":comment", $_POST['comment'], PDO::PARAM_STR);
        $query->execute();
    }

    $comments = array();
 
    $query = $db->connect()->query("SELECT * FROM SM");
 
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $comments[] = $row['comment'];
    }
    ob_end_flush();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Stored Demo</title>
</head>
<body>
    <h2>XSS Stored Demo</h2>
 
    <form method="POST">
        <input type="text" placeholder="Enter a Comment" name="comment">
        <input type="submit" name="submit" value="submit">
    </form>
 
    <?php foreach($comments as $comment): ?>
        <?= $comment ?>
        <?=htmlspecialchars($comment)?>
        <br>
    <? endforeach ?>
 
</body>
</html>