<?php
// view_reports.php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);

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
    $email = $user['email'];
    $is_admin = $user['is_admin'];
} else {
    die("User not found.");
}

if ($is_admin != 1) {
    header("Location: dashboard.php");
    exit;
}

// Fetch reports from the database
$reports_sql = "SELECT * FROM reports"; // Assuming 'reports' table exists
$reports_result = $conn->query($reports_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Reports - MoveMobility</title>
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
        .report-table {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin: 20px auto;
        }
        .report-table h2 {
            font-size: 24px;
            color: #005f99;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #0077b6;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-reports {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            color: #888;
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
    <h1>Reports Overview</h1>
    
    <div class="report-table">
        <h2>All Reports</h2>
        <?php if ($reports_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>User</th>
                        <th>Report Type</th>
                        <th>Description</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $reports_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $report['report_id']; ?></td>
                            <td><?php echo $report['user_id']; ?></td>
                            <td><?php echo $report['report_type']; ?></td>
                            <td><?php echo $report['description']; ?></td>
                            <td><?php echo $report['date_submitted']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-reports">No reports available.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>MoveMobility © 2024. All rights reserved.</p>
</footer>

</body>
</html>
