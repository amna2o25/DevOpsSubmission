<?php
require_once 'sessionManager.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <header>
        <h1>Settings</h1>
    </header>
    <section>
        <form method="post">
            <label for="site_theme">Site Theme:</label>
            <select name="site_theme" id="site_theme">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>
            <br>
            <input type="submit" value="Save Settings">
        </form>
    </section>
</body>
</html>
