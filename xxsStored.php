<?php
ob_start();
ini_set('display_errors', 1);

require 'DB.php';

$db = new DB();

// Handle new comment submission
if (isset($_POST['submit'])) {
    $stmt = $db->connect()->prepare(
        "INSERT INTO SM (comment) VALUES (:comment)"
    );
    $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
    $stmt->execute();
}

// Fetch all comments
$comments = [];
$query = $db->connect()->query("SELECT comment FROM SM");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $comments[] = $row['comment'];
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>XSS Stored Demo</title>
</head>
<body>
  <h2>XSS Stored Demo</h2>

  <form method="POST" action="">
    <input type="text" name="comment" placeholder="Enter a Comment" required>
    <input type="submit" name="submit" value="Submit">
  </form>

  <div class="comments">
    <?php foreach ($comments as $comment): ?>
      <p><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?>
  </div>
</body>
</html>
