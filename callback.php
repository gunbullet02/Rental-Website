<?php
require 'vendor/autoload.php'; // Load Composer dependencies

use Auth0\SDK\Auth0;

$config = require 'auth0_config.php'; // Load the Auth0 config

$auth0 = new Auth0([
    'domain' => $config['domain'],
    'client_id' => $config['client_id'],
    'client_secret' => $config['client_secret'],
    'redirect_uri' => $config['redirect_uri'],
    'scope' => $config['scope'],
]);

// Get the user info after successful login
$userInfo = $auth0->getUser();

if (!$userInfo) {
    // If no user info, authentication failed
    echo "Login failed.";
    exit();
}

// Start session and store user info
session_start();
$_SESSION['user'] = $userInfo;
header('Location: /dashboard2.php'); // Redirect to a dashboard or secure page
exit();
