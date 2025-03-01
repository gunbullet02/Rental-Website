<?php
session_start();

// Check if the user is logged in and is a host
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$action = $_GET['action'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($action == 'approve' || $action == 'deny') {
    $status = ($action == 'approve') ? 'Approved' : 'Denied';

    // Update the booking status
    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);

    if ($stmt->execute()) {
        if ($status == 'Approved') {
            // No redirection, just notify the host that the booking is approved
            echo "Booking approved.";
        } else {
            echo "Booking denied.";
        }
    } else {
        echo "Error updating booking: " . $stmt->error;
    }
}

$conn->close();
?>
