<?php
// admin_dashboard.php

// Start the session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in page if not logged in
    header("Location: signin.php");
    exit;
}

// Fetch the user's details from the database
$servername = "localhost"; // Adjust with your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rentalwebsite"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $email = $user['email'];
    $is_admin = $user['is_admin']; // Assuming 'is_admin' column is in the users table
} else {
    die("User not found.");
}

// Check if the user is an admin
if ($is_admin != 1) { // Assuming '1' means the user is an admin
    // Redirect non-admins to the regular dashboard or another page
    header("Location: dashboard.php");
    exit;
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - MoveMobility</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #005f99;
            padding: 10px 20px;
            color: white;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            font-weight: bold;
        }
        nav a:hover {
            background-color: #0077b6;
            transition: background-color 0.3s ease;
        }
        .container {
            padding: 40px;
            text-align: center;
        }
        .admin-info {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 50%;
            text-align: left;
        }
        .admin-info h2 {
            font-size: 24px;
            color: #005f99;
            margin-bottom: 20px;
        }
        .admin-info p {
            font-size: 16px;
            margin: 8px 0;
        }
        .admin-actions {
            margin-top: 30px;
            text-align: center;
        }
        .admin-actions a {
            text-decoration: none;
            background-color: #0077b6;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin: 10px;
        }
        .admin-actions a:hover {
            background-color: #0096c7;
        }
        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #005f99;
            color: white;
            font-size: 14px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

    <header>
        <nav>
            <div class="logo">
                <h1>MoveMobility</h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="ourVehicles.php">Our Vehicles</a>
                <!--

                <a href="signup.php">Sign Up</a>
                <a href="signin.php">Sign In</a>
    -->
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Welcome to the Admin Dashboard, <?php echo $name; ?>!</h1>
        <p>As an admin, you have full control over the platform's management.</p>
        
        <div class="admin-info">
            <h2>Your Profile</h2>
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Role:</strong> Admin</p>
        </div>

        <div class="admin-actions">
            <h2>Admin Actions</h2>
            <a href="manage_users.php">Manage Users</a>
            <a href="admin_verify_vehicles.php">Manage Vehicles</a>
            <a href="view_reports.php">View Reports</a>
        </div>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>

</body>
</html>
