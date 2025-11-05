<?php
session_start();

// Check if necessary data is set
if (!isset($_POST['orderID']) || !isset($_POST['amount']) || !isset($_POST['vehicle_id']) || !isset($_POST['user_id']) ||
    !isset($_POST['start_date']) || !isset($_POST['end_date']) || !isset($_POST['full_name']) || 
    !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['pickup_location']) || 
    !isset($_POST['dropoff_location']) || !isset($_POST['license']) || !isset($_POST['age'])) {
    http_response_code(400); // Bad request
    echo "Missing or invalid request data.";
    exit;
}

// Retrieve and sanitize POST data
$orderID = $_POST['orderID'];
$amount = $_POST['amount'];
$vehicle_id = $_POST['vehicle_id'];
$user_id = $_POST['user_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$pickup_location = $_POST['pickup_location'];
$dropoff_location = $_POST['dropoff_location'];
$license = $_POST['license'];
$age = $_POST['age'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

// Check if the booking already exists
$booking_check_sql = "SELECT * FROM bookings WHERE user_id = ? AND vehicle_id = ? AND start_date = ? AND end_date = ?";
$booking_check_stmt = $conn->prepare($booking_check_sql);
if (!$booking_check_stmt) {
    http_response_code(500);
    echo "Failed to prepare booking check statement: " . $conn->error;
    exit;
}
$booking_check_stmt->bind_param("iiss", $user_id, $vehicle_id, $start_date, $end_date);
$booking_check_stmt->execute();
$booking_check_result = $booking_check_stmt->get_result();

if ($booking_check_result->num_rows > 0) {
    // Booking exists
    $existing_booking = $booking_check_result->fetch_assoc();
    $booking_id = $existing_booking['booking_id'];

    // Insert payment into payments table
    $payment_sql = "INSERT INTO payments (booking_id, orderID, amount, vehicle_id) VALUES (?, ?, ?, ?)";
    $payment_stmt = $conn->prepare($payment_sql);
    if (!$payment_stmt) {
        http_response_code(500);
        echo "Failed to prepare payment statement: " . $conn->error;
        exit;
    }
    $payment_stmt->bind_param("isdi", $booking_id, $orderID, $amount, $vehicle_id);
    
    if ($payment_stmt->execute()) {
        echo "Payment recorded successfully.";
    } else {
        http_response_code(500);
        echo "Failed to record payment: " . $payment_stmt->error;
    }

} else {
    // Insert booking if it doesn't exist
    $booking_sql = "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, full_name, email, phone, pickup_location, dropoff_location, license, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $booking_stmt = $conn->prepare($booking_sql);
    if (!$booking_stmt) {
        http_response_code(500);
        echo "Failed to prepare booking statement: " . $conn->error;
        exit;
    }
    $booking_stmt->bind_param("iissssssssi", $user_id, $vehicle_id, $start_date, $end_date, $full_name, $email, $phone, $pickup_location, $dropoff_location, $license, $age);

    if ($booking_stmt->execute()) {
        // Insert payment after successful booking insertion
        $booking_id = $booking_stmt->insert_id;
        $payment_sql = "INSERT INTO payments (booking_id, orderID, amount, vehicle_id) VALUES (?, ?, ?, ?)";
        $payment_stmt = $conn->prepare($payment_sql);
        if (!$payment_stmt) {
            http_response_code(500);
            echo "Failed to prepare payment statement after booking insert: " . $conn->error;
            exit;
        }
        $payment_stmt->bind_param("isdi", $booking_id, $orderID, $amount, $vehicle_id);

        if ($payment_stmt->execute()) {
            echo "Booking and payment recorded successfully.";
        } else {
            http_response_code(500);
            echo "Failed to record payment: " . $payment_stmt->error;
        }
    } else {
        http_response_code(500);
        echo "Failed to record booking: " . $booking_stmt->error;
    }
}

$conn->close();
?>
