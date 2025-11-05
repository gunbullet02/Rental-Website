<?php
session_start();

// ✅ 1. Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo "You must be logged in.";
    exit;
}

// ✅ 2. Get Cloudinary URL from frontend
$data = json_decode(file_get_contents("php://input"), true);
$id_url = $data['id_url'] ?? '';

if (!$id_url) {
    http_response_code(400); // Bad request
    echo "Missing ID URL.";
    exit;
}

// ✅ 3. Connect to the database
$conn = new mysqli("localhost", "root", "", "rentalwebsite");
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

// ✅ 4. Save the URL + set status to Pending
$stmt = $conn->prepare("UPDATE users SET id_url = ?, id_verification_status = 'Pending' WHERE user_id = ?");
$stmt->bind_param("si", $id_url, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo "ID successfully saved.";
} else {
    http_response_code(500);
    echo "Failed to save ID.";
}

$stmt->close();
$conn->close();
?>
