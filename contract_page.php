<?php
// Start the session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in page if not logged in
    header("Location: signin.php");
    exit;
}

// Check if the booking_id is set in the URL
if (!isset($_GET['booking_id'])) {
    // Redirect back to the bookings page if no booking ID is provided
    header("Location: view_booking.php");
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

// Fetch the booking details
$booking_id = $_GET['booking_id']; // Get the booking ID from URL
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$sql = "SELECT bookings.*, vehicles.make, vehicles.model, vehicles.year 
        FROM bookings 
        JOIN vehicles ON bookings.vehicle_id = vehicles.vehicle_id
        WHERE bookings.booking_id = ? AND bookings.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$booking = $result->fetch_assoc();

if (!$booking) {
    // Redirect back to the bookings page if booking not found
    header("Location: view_booking.php");
    exit;
}

// Handle contract signing submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update booking status to 'Contract Signed'
    $sql = "UPDATE bookings SET contract_signed = 1 WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    
    // Redirect to a success page or bookings page
    header("Location: view_booking.php?contract_signed=success");
    exit;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Contract - MoveMobility</title>
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
        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            font-weight: bold;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #005f99;
        }
        .contract-container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 40px;
        }
        .contract-details {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .contract-details p {
            margin: 10px 0;
        }
        .sign-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
        }
        .sign-button:hover {
            background-color: #218838;
        }
        .download-link {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
        .download-link:hover {
            background-color: #0056b3;
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
            <a href="index.html">Home</a>
            <a href="our-vehicles.php">Our Vehicles</a>
            <a href="view_booking.php">View Bookings</a>
            <a href="signup.html">Sign Up</a>
            <a href="signin.php">Sign In</a>
        </nav>
    </header>

    <h1>Sign Contract</h1>

    <div class="contract-container">
        <div class="contract-details">
            <h3>Contract for Vehicle: <?= htmlspecialchars($booking['make'] . ' ' . $booking['model'] . ' (' . $booking['year'] . ')') ?></h3>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date']) ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date']) ?></p>
            <p><strong>Total Cost:</strong> $<?= htmlspecialchars($booking['total_cost']) ?></p>
            <p><strong>Pick-up Location:</strong> <?= htmlspecialchars($booking['pickup_location']) ?></p>
            <p><strong>Drop-off Location:</strong> <?= htmlspecialchars($booking['dropoff_location']) ?></p>

            <p>By signing this contract, you agree to the terms and conditions of renting the above vehicle for the specified dates. Ensure that the vehicle is returned in good condition, and follow all policies as stated during the booking process.</p>

            <p>Download and review the sample contract before signing:</p>
            <a class="download-link" href="contracts/sample_contract.pdf" target="_blank">Download Contract (PDF)</a>
        </div>

        <form method="POST" action="">
            <button type="submit" class="sign-button">Sign Contract</button>
        </form>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
