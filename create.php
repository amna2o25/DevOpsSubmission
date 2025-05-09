<?php
ob_start();

ini_set ('display_errors', 1);

require 'DB.php';

if(isset($_POST['upload']))
{
    $name =$_POST['name'];
    $targetDir ="img/";
    $targetFile = $targetDir.basename($_FILES['image']['name']);
    $type = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $supported = ['image/jpg','image/jpeg', 'image/png'];
    $canUpload = false;
    
    if(!in_array(mime_content_type($_FILES['image']['tmp_name']), $supported))
    {
        echo"File is Not The Correct Supported Type, only JPEG, JPG AND PNG Are Allowed";
        exit();   
    }
    
    if(file_exists($targetFile))
    {
        echo "File already exists on the server. Will not re-upload.";
        exit();
    }

    if($_FILES['image']['size'] > 3000000 || $_FILES['image']['size'] === 0 )
    {
        echo"File Size is Inccorect, Must be Greater than 0MB and lower then 3MB.";
        exit();
    }    

    if(!getimagesize($_FILES['image']['tmp_name']))
    {
        echo"File is not an Image, Must be a JPEG,JPG OR PNG File";
        exit();

    }

    if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile))
    {
        $db = new DB;

        $query = $db->connect()->prepare("INSERT into tbl_products (ProductName, Image) VALUES ('$name', '$targetFile')");
        $query->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
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
    <title>create</title>
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
    
    
</header>


<h1>Create ANew Products</h1>

<form method="post"action="create.php" enctype="multipart/form-data">
    <label for="name"> New Products Name</lable><br>
    <input type="text" placeholder="Enter a products name.." id="name" name="name"><br>
    <label for="image">Select An Image File</label><br>
    <input type="file"id="image"name="image"accept="image/*"><br>
    <input type="submit"name="upload"value="Create">
</form>

</head>
<body></body>