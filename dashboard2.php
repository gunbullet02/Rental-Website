<?php
session_start(); // Start the session to access user data

if (!isset($_SESSION['user'])) {
    // Redirect to login if user is not authenticated
    header('Location: /login.php');
    exit();
}

$user = $_SESSION['user']; // Get user info from session
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <a href="logout.php">Log Out</a>
</body>
</html>
