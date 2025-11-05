<?php include 'header.php'; ?>
<?php

// Start the session to track the logged-in user (if required)
session_start();

// Check if the user is logged in (optional, depending on your use case)
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

// Fetch approved vehicles from the database
$sql = "SELECT * FROM vehicles WHERE approval_status = 'approved'"; // Only approved vehicles
$result = $conn->query($sql);

$vehicles = [];

if ($result->num_rows > 0) {
    // Store the vehicles in an array for display
    while($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}

// Get unique categories from the vehicles table
$categories_sql = "SELECT DISTINCT category FROM vehicles";
$categories_result = $conn->query($categories_sql);

$categories = [];
if ($categories_result->num_rows > 0) {
    while($row = $categories_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// Fetch bookings for the logged-in user to check their booking statuses
$user_id = $_SESSION['user_id'];
$booking_sql = "SELECT * FROM bookings WHERE user_id = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

$bookings = [];
while ($row = $booking_result->fetch_assoc()) {
    $bookings[$row['vehicle_id']] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Our Vehicles - RideUpz</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
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
        }
        .vehicle-category {
            margin: 20px auto;
            width: 90%;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .category-title {
            font-size: 24px;
            color: #005f99;
            margin-bottom: 20px;
            text-align: left;
        }
        .vehicle-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .vehicle-item {
            flex: 1 1 calc(30% - 20px);
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .vehicle-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        .vehicle-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .vehicle-item h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
        }
        .vehicle-item p {
            font-size: 14px;
            margin-bottom: 10px;
            color: #555;
        }
        .inquiry-button, .booking-button, .view-booking-button {
            display: block;
            margin: 15px auto;
            padding: 12px 24px;
            background-color: #005f99;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .inquiry-button:hover, .booking-button:hover, .view-booking-button:hover {
            background-color: #0077b6;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .tab-button {
            padding: 10px 20px;
            background-color: #fff;
            border: 1px solid #005f99;
            border-radius: 5px;
            color: #005f99;
            font-weight: bold;
            text-decoration: none;
            margin: 0 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .tab-button.active, .tab-button:hover {
            background-color: #005f99;
            color: #fff;
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
                <h1>RideUpz</h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="ourVehicles.php">Our Vehicles</a>
                <a href="uploadID.php">Id Verification</a>
                <a href="enlist-vehicle.php">Enlist Vehicle</a>
                <!--
                <a href="signup.php">Sign Up</a>
                <a href="signin.php">Sign In</a>

    -->
                <a href="logout.php">Logout</a>

            </div>
            <!-- No changes to navbar -->
        </nav>
    </header>

    <h1>Our Vehicles</h1>

    <div class="tabs">
        <?php foreach ($categories as $category): ?>
            <button class="tab-button" onclick="filterCategory('<?= htmlspecialchars($category) ?>')"><?= htmlspecialchars($category) ?></button>
        <?php endforeach; ?>
    </div>

    <?php if (count($vehicles) > 0): ?>
        <?php foreach ($categories as $category): ?>
            <div class="vehicle-category" data-category="<?= htmlspecialchars($category) ?>">
                <h2 class="category-title"><?= htmlspecialchars($category) ?></h2>
                <div class="vehicle-list">
    <?php
    foreach ($vehicles as $vehicle) {
        if ($vehicle['category'] === $category): 
    ?>
        <div class="vehicle-item">
            <h3><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' (' . $vehicle['year'] . ')') ?></h3>
            <img src="<?= htmlspecialchars($vehicle['photos']) ?>" alt="Image of <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?>" style="width: 350px; height: 350px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <p style="font-size: 20px;"><strong>Year:</strong> <?= htmlspecialchars($vehicle['year']) ?></p>
            <p style="font-size: 20px;"><strong>Make:</strong> <?= htmlspecialchars($vehicle['make']) ?></p>
            <p style="font-size: 20px;"><strong>Model:</strong> <?= htmlspecialchars($vehicle['model']) ?></p>


            <!--
            <p><strong>Fuel Type:</strong> <?= htmlspecialchars($vehicle['fuel_type']) ?></p>
            <p><strong>Transmission:</strong> <?= htmlspecialchars($vehicle['transmission']) ?></p>
            <p><strong>Ramp Type:</strong> <?= htmlspecialchars($vehicle['ramp_type']) ?></p>
            <p><strong>Seating Configuration:</strong> <?= htmlspecialchars($vehicle['seating_config']) ?></p>
            <p><strong>Wheelchair Spaces:</strong> <?= htmlspecialchars($vehicle['num_wheelchair']) ?></p>
        -->
            <a href="inquire.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="inquiry-button">Inquire</a>
            <a href="book.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="booking-button">Book Now</a>
            <a href="vehicle_details.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="inquiry-button">View Details</a>

            <?php if (isset($bookings[$vehicle['vehicle_id']])): ?>
                <a href="view_booking.php?booking_id=<?= $bookings[$vehicle['vehicle_id']]['booking_id'] ?>" class="view-booking-button">
                    View Booking Status
                </a>
            <?php endif; ?>
        </div>
    <?php
        endif;
    }
    ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No vehicles available at the moment. Please check back later.</p>
    <?php endif; ?>

    <footer>
        <p>Rideupz © 2024. All rights reserved.</p>
    </footer>

    <script>
        function filterCategory(category) {
            const categories = document.querySelectorAll('.vehicle-category');
            categories.forEach(cat => {
                if (cat.getAttribute('data-category') === category || category === 'all') {
                    cat.style.display = 'block';
                } else {
                    cat.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
