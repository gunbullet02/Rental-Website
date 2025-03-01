<?php
session_start();
print_r($_SESSION); // Display session contents

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if vehicle_id is passed in URL or session
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
} elseif (isset($_SESSION['vehicle_id'])) {
    $vehicle_id = $_SESSION['vehicle_id'];
} else {
    // If no vehicle_id found, handle the error
    die("Vehicle ID is missing.");
}





// Connect to the database
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rentalwebsite"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume the payment success has already been confirmed

// Update the payment status to 'paid' after successful payment
$sql = "UPDATE bookings SET payment_status = 'paid' WHERE user_id = ? AND vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $vehicle_id);

if ($stmt->execute()) {
    // Insert the payment details into the payments table
    $payment_amount = 100; // Example amount, should come from actual payment data
    $payment_method = 'PayPal'; // Example payment method, should come from actual payment data

    $sql_payment = "INSERT INTO payments (user_id, vehicle_id, amount) VALUES (?, ?, ?)";
    $stmt_payment = $conn->prepare($sql_payment);
    $stmt_payment->bind_param("iii", $user_id, $vehicle_id, $payment_amount);

    if ($stmt_payment->execute()) {
        $message = "Your booking and payment have been confirmed successfully!";
    } else {
        // Handle error inserting payment
        $message = "There was an error saving your payment. Please contact support.";
    }
} else {
    // Error updating booking status
    $message = "There was an error confirming your booking. Please contact support.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Success - MoveMobility</title>
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
        .message-box {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin: 20px auto;
        }
        .message-box h2 {
            font-size: 24px;
            color: #005f99;
            margin-bottom: 20px;
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
    <div class="message-box">
        <h2><?php echo $message; ?></h2>
        <p>Thank you for your payment. You can view your booking details in your account.</p>
        <a href="user_dashboard.php" style="color: #005f99; font-weight: bold; text-decoration: none;">Go to Dashboard</a>
    </div>
</div>

<footer>
    <p>MoveMobility © 2024. All rights reserved.</p>
</footer>

</body>
</html>
