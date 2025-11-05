<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['user_id'], $_GET['action'])) {
    exit("Invalid request.");
}

$conn = new mysqli("localhost", "root", "", "rentalwebsite");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = intval($_GET['user_id']);
$action = $_GET['action'];

if ($action == 'verify') {
    $status = 'Verified';
} elseif ($action == 'reject') {
    $status = 'Rejected';
} else {
    exit("Invalid action.");
}

$stmt = $conn->prepare("UPDATE users SET id_verification_status = ? WHERE user_id = ?");
$stmt->bind_param("si", $status, $user_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: verifyID.php");
exit;
