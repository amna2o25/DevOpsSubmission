<?php

ini_set('display_errors', 1);

require_once 'DB.php';

function add($db, $id, $quantity)
{
    $query = $db->connect()->prepare("SELECT ProductID, ProductName, Image, Price FROM tbl_products WHERE ProductID = :id");
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();

    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        if(array_search($id, array_column($_SESSION['basket'], 'id')) !== FALSE)
        {
            $key = array_search($id, array_column($_SESSION['basket'], 'id'));
            $_SESSION['basket'][$key]['quantity'] += $quantity;

            echo "Increased quantity of an existing product.";
        }
        else
        {
            $toAdd = array(
                'ProductID' => $row['ProductID'],
                'ProductName' => $row['ProductName'],
                'Image' => $row['Image'],
                'Price' => $row['Price'],
                'Quantity' => $quantity
            );

            $_SESSION['basket'][] = $toAdd;

            echo "Added a new product.";
        }
    }    
}

function addProductToBasket($db, $ProductId, $quantity) {

    if(array_search($ProductId, array_column($_SESSION['basket'], 'ProductID')) !== FALSE)
    {
        $key = array_search($ProductId, array_column($_SESSION['basket'], 'ProductID'));
        $_SESSION['basket'][$key]['Quantity'] += $quantity;
    }
    else
    {
        $query = $db->connect()->prepare("SELECT ProductID, ProductName, Image, Price FROM tbl_products WHERE ProductID = :id");
        $query->bindParam(":id", $ProductId, PDO::PARAM_INT);
        $query->execute();
        
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $toAdd = array(
                'ProductID' => $row['ProductID'],
                'ProductName' => $row['ProductName'],
                'Image' => $row['Image'],
                'Price' => $row['Price'],
                'Quantity' => $quantity
            );
            
            $_SESSION['basket'][] = $toAdd;
        }
    }
}
