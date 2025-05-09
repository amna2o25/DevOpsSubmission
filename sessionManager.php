<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

$lifetime = 1800; // 30 minutes

session_set_cookie_params([
    'lifetime' => $lifetime,
    'domain' => 'sencdigitech.co.uk', // Adjust if necessary
    'path' => '/',
    'secure' => true, // Uses only HTTPS
    'samesite' => 'Strict', // Limits the cookie to same-site requests
    'httponly' => true // Restricts script access for clients
]);

session_start();

// CSRF token initialization
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['createdAt'])) {    
    $_SESSION['createdAt'] = time();
}
else
{
    if(time() - $_SESSION['createdAt'] > $lifetime)
    {
        session_regenerate_id(true);
        $_SESSION['createdAt'] = time();
    }
}










































 
?>

