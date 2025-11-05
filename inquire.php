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

// Check if a vehicle ID is passed via the URL
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];

    // Fetch vehicle details from the database
    $sql = "SELECT * FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $vehicle_result = $stmt->get_result();

    if ($vehicle_result->num_rows > 0) {
        $vehicle = $vehicle_result->fetch_assoc();
    } else {
        echo "Vehicle not found.";
        exit;
    }
}

// Handle inquiry form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $vehicle_id = $_POST['vehicle_id'];

    // Insert the inquiry into the database
    $inquiry_sql = "INSERT INTO inquiries (name, email, message, vehicle_id) VALUES (?, ?, ?, ?)";
    $inquiry_stmt = $conn->prepare($inquiry_sql);
    $inquiry_stmt->bind_param("sssi", $name, $email, $message, $vehicle_id);

    if ($inquiry_stmt->execute()) {
        echo "Your inquiry has been sent successfully!";
    } else {
        echo "Error sending inquiry. Please try again later.";
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
    <title>Inquire - MoveMobility</title>
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
        }
        .inquiry-form {
            width: 50%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .inquiry-form label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .inquiry-form input, .inquiry-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .inquiry-form button {
            background-color: #005f99;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .inquiry-form button:hover {
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
                <a href="index.php">Home</a>
                <a href="ourVehicles.php">Our Vehicles</a>
                <a href="signup.php">Sign Up</a>
                <a href="signin.php">Sign In</a>
            </div>
        </nav>
    </header>

    <h1>Inquire About the Vehicle</h1>

    <div class="inquiry-form">
        <?php if (isset($vehicle)): ?>
            <h2>Vehicle: <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' (' . $vehicle['year'] . ')') ?></h2>

            <form action="inquire.php" method="POST">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($vehicle['vehicle_id']) ?>">

                <button type="submit">Send Inquiry</button>
            </form>
        <?php else: ?>
            <p>Vehicle not found. Please go back and select a valid vehicle.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
