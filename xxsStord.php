<?php
ob_start();

// Include your DB connection (adjust path if needed)
require_once 'DB.php';
ini_set('display_errors', 1);

$db = new DB;

// Handle form submission
if (isset($_POST['submit'])) {
    $conn  = $db->connect();
    $stmt  = $conn->prepare("INSERT INTO SM (comment) VALUES (:comment)");
    $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
    $stmt->execute();
}

// Fetch all comments
$comments = [];
$conn     = $db->connect();
$query    = $conn->query("SELECT * FROM SM");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
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

    <form method="POST" action="">
        <input type="text" name="comment" placeholder="Enter a Comment" required>
        <input type="submit" name="submit" value="Submit">
    </form>

    <h3>Comments:</h3>
    <?php foreach ($comments as $comment): ?>
        <!-- Unsafe: raw output -->
        <div><?php echo $comment; ?></div>
        <!-- Safe: escaped output -->
        <div><?php echo htmlspecialchars($comment, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></div>
        <hr>
    <?php endforeach; ?>

</body>
</html>
