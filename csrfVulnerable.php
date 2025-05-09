<?php
   
    require 'DB.php';
 
    //ini_set('display_errors', 1);
   
    session_start();
 
    $db = new DB;
 
    $_SESSION['account'] = 12345678;
    $account = $_SESSION['account'];
    if(empty(($_SESSION['token'])))
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));

    }
 
    $query = $db->connect()->prepare("SELECT balance FROM account WHERE account = :account");
    $query->bindparam('account', $account, PDO::PARAM_INT);
    $query->execute();
 
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $balance = $row['balance'];
    }
 
    if($_SERVER['REQUEST_METHOD'] ==='POST')
    {
        $token = htmlspecialchars(($_POST['token']));
        
        if(empty($_POST['token']) || $token !== $_SESSION['token'])
        {
            echo "invalid csrf token.";
            exit;
        }
        {
        $amount = $_POST['amount'];
        $target = $_POST['targetaccount'];
 
        $originNewBalance = $balance - $amount;
 
        $query = $db->connect()->prepare("SELECT balance FROM account WHERE account = :target");
        $query->bindParam(':target', $target, PDO::PARAM_INT);
        $query->execute();
 
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $targetBalance = $row['balance'];
        }
 
        $targetNewBalance = $targetBalance + $amount;
       
        $query = $db->connect()->prepare("UPDATE account SET balance = :newBalance WHERE account = :account;
                                        UPDATE account SET balance = :targetBalance WHERE account = :target");

$query->execute(array(':newBalance' =>$originNewBalance,
':account' =>$account,
':targetBalance' =>$targetNewBalance,
':target' =>$target));
$_SESSION['token'] = bin2hex(random_bytes(32));

header('Location: csrfVulnerable.php');
    }
    }
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Transfer</title>
</head>
<body>
    <h2>super secure bank</h2>
    <h2>current balance £<?= $balance ?></h2>
 
    <form method="POST">
        <label for="amount"> amount to transfer</label><br>
        <input type="text" id="amount" name="amount"><br>
        <label for="targetaccount">payee account</label><br>
        <input type="text" id="targetaccount" name="targetaccount"><br>
        <input type="hidden" name="token"value ="<php echo $_SESSION['token]?>"></php>
        <input type="submit" name="submit" value="Transfer">
    </form>
 
</form>
</body>
</html>