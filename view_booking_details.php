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

// Close the connection
$conn->close();

if (!$booking) {
    // Redirect back to the bookings page if booking not found
    header("Location: view_booking.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking Details - MoveMobility</title>
    <style>
        /* Your existing styles */
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
        .vehicle-info {
            font-size: 18px;
        }
        .details p {
            font-size: 16px;
            margin: 8px 0;
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
        .back-button {
            background-color: #005f99;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #0077b6;
        }
        .payment-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
        .payment-button:hover {
            background-color: #218838;
        }

        .contract-button {
    background-color: #ffc107;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin-top: 20px;
}
.contract-button:hover {
    background-color: #e0a800;
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

    <h1>Booking Details</h1>

    <div class="booking-container">
        <div class="vehicle-info">
            <h3><?= htmlspecialchars($booking['make'] . ' ' . $booking['model'] . ' (' . $booking['year'] . ')') ?></h3>
            <div class="details">
                <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>
                <p><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date']) ?></p>
                <p><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date']) ?></p>
                <p><strong>Total Cost:</strong> $<?= htmlspecialchars($booking['total_cost']) ?></p>
                <p><strong>Pick-up Location:</strong> <?= htmlspecialchars($booking['pickup_location']) ?></p>
                <p><strong>Drop-off Location:</strong> <?= htmlspecialchars($booking['dropoff_location']) ?></p>
                <p><strong>Additional Notes:</strong> <?= htmlspecialchars($booking['notes']) ?></p>
            </div>

            <a class="back-button" href="view_booking.php">Back to Bookings</a>

            <!-- Add Payment Button if Booking is Approved -->
            <?php if ($booking['status'] == 'Approved'): ?>
    <a class="payment-button" href="payment_page.php?booking_id=<?= $booking_id ?>">Proceed to Payment</a>
    <a class="contract-button" href="contract_page.php?booking_id=<?= $booking_id ?>">Sign Contract</a>
<?php else: ?>
    <p>Booking is not yet approved. You will be able to make the payment and sign the contract once it is approved.</p>
<?php endif; ?>
        </div>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
