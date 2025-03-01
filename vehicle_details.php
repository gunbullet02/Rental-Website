<?php
// Start the session
session_start();

// Check if the user is logged in (optional)
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the vehicle ID from the URL
$vehicle_id = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;

// Fetch vehicle details from the database
$sql = "SELECT * FROM vehicles WHERE vehicle_id = $vehicle_id";
$result = $conn->query($sql);

$vehicle = null;
if ($result->num_rows > 0) {
    // Fetch the vehicle details
    $vehicle = $result->fetch_assoc();
} else {
    // No vehicle found
    echo "Vehicle not found.";
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
    <title>Vehicle Details - MoveMobility</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .vehicle-item {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .vehicle-item h3 {
            color: #005f99;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .vehicle-item p {
            color: #555;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .vehicle-item strong {
            color: #333;
        }

        .inquiry-button, .booking-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #005f99;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .inquiry-button:hover, .booking-button:hover {
            background-color: #0077b6;
        }

        footer {
            text-align: center;
            padding: 10px 0;
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
                <a href="signup.html">Sign Up</a>
                <a href="signin.php">Sign In</a>
            </div>
        </nav>
    </header>

    <h1>Vehicle Details</h1>

    <?php if ($vehicle): ?>
        <div class="vehicle-item">
            <h3><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' (' . $vehicle['year'] . ')') ?></h3>
            <img src="<?= htmlspecialchars($vehicle['photos']) ?>" alt="Image of <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?>" style="width: 350px; height: 350px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            
            <p><strong>Fuel Type:</strong> <?= htmlspecialchars($vehicle['fuel_type']) ?></p>
            <p><strong>Transmission:</strong> <?= htmlspecialchars($vehicle['transmission']) ?></p>
            <p><strong>Ramp Type:</strong> <?= htmlspecialchars($vehicle['ramp_type']) ?></p>
            <p><strong>Seating Configuration:</strong> <?= htmlspecialchars($vehicle['seating_config']) ?></p>
            <p><strong>Number of Wheelchair Spaces:</strong> <?= htmlspecialchars($vehicle['num_wheelchair']) ?></p>
            <p><strong>Height Clearance:</strong> <?= htmlspecialchars($vehicle['height_clearance']) ?> inches</p>
            <p><strong>Drive From Wheelchair:</strong> <?= $vehicle['drive_from_wc'] ? 'Yes' : 'No' ?></p>

            <!-- New Additional Details -->
            <p><strong>VIN:</strong> <?= htmlspecialchars($vehicle['vin']) ?></p>
            <p><strong>Accessibility Type:</strong> <?= htmlspecialchars($vehicle['accessibility_type']) ?></p>
            <p><strong>Interior Height:</strong> <?= htmlspecialchars($vehicle['interior_height']) ?> inches</p>
            <p><strong>Door Clearance:</strong> <?= htmlspecialchars($vehicle['door_clearance']) ?> inches</p>
            <p><strong>Ramp/Lift Width:</strong> <?= htmlspecialchars($vehicle['ramp_lift_width']) ?> inches</p>

            <!-- Inquiry and Booking Buttons -->
            <a href="inquire.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="inquiry-button">Inquire</a>
            <a href="book.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="booking-button">Book Now</a>

            <!-- View Details Button -->
            <a href="vehicle_details.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="inquiry-button">View Details</a>
        </div>
    <?php else: ?>
        <p>Vehicle details could not be retrieved.</p>
    <?php endif; ?>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
