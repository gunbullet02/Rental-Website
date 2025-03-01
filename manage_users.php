<?php
// manage_users.php

// Start session to track logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in page if not logged in
    header("Location: signin.php");
    exit;
}

// Fetch user details from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $is_admin = $user['is_admin'];
} else {
    die("User not found.");
}

// Check if the user is an admin
if ($is_admin != 1) {
    header("Location: dashboard.php");
    exit;
}

// Fetch all users for management
$users_sql = "SELECT * FROM users";
$users_result = $conn->query($users_sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Users - MoveMobility</title>
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
        h1 {
            color: #005f99;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 80%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #0077b6;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        .actions a {
            background-color: #0077b6;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .actions a:hover {
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
            <a href="signup.php">Sign Up</a>
            <a href="signin.php">Sign In</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
</header>

<div class="container">
    <h1>Manage Users</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($users_result->num_rows > 0) {
                    while ($row = $users_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . ($row['is_admin'] == 1 ? 'Admin' : 'User') . "</td>";
                        echo "<td class='actions'>
                            <a href='edit_user.php?id=" . $row['user_id'] . "'>Edit</a> |
                            <a href='delete_user.php?id=" . $row['user_id'] . "'>Delete</a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>MoveMobility © 2024. All rights reserved.</p>
</footer>

</body>
</html>
