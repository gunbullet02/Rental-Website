<?php
// Start the session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in page if not logged in
    header("Location: signin.php");
    exit;
}

// Database connection
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

// Fetch user's booking details
$user_id = $_SESSION['user_id']; // Get logged-in user's ID
$sql = "SELECT bookings.*, vehicles.make, vehicles.model, vehicles.year 
        FROM bookings 
        JOIN vehicles ON bookings.vehicle_id = vehicles.vehicle_id
        WHERE bookings.user_id = $user_id ORDER BY bookings.status DESC";

$result = $conn->query($sql);

$bookings = [];

if ($result->num_rows > 0) {
    // Store the bookings in an array for display
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Bookings - MoveMobility</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }
        header {
            background-color: #005f99;
            padding: 20px;
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
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #005f99;
        }
        .booking-container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 40px;
        }
        .booking-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .booking-item:last-child {
            border-bottom: none;
        }
        .vehicle-info {
            flex: 1;
            font-size: 16px;
        }
        .status {
            font-weight: bold;
        }
        .action-buttons a {
            background-color: #005f99;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        .action-buttons a:hover {
            background-color: #0077b6;
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #005f99;
            color: white;
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
                <a href="index.html">Home</a>
                <a href="our-vehicles.php">Our Vehicles</a>
                <a href="view_booking.php">View Bookings</a>
                <a href="signup.html">Sign Up</a>
                <a href="signin.php">Sign In</a>
            </div>
        </nav>
    </header>

    <h1>Your Bookings</h1>

    <div class="booking-container">
        <?php if (count($bookings) > 0): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-item">
                    <div class="vehicle-info">
                        <h3><?= htmlspecialchars($booking['make'] . ' ' . $booking['model'] . ' (' . $booking['year'] . ')') ?></h3>
                        <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>
                        <p><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date']) ?></p>
                        <p><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date']) ?></p>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="view_booking_details.php?booking_id=<?= $booking['booking_id'] ?>">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no bookings yet. Start booking a vehicle!</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
