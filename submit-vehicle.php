<?php
// submit-vehicle.php

// Start session to ensure user is logged in
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

// Database connection
$host = 'localhost'; // or your database host
$username = 'root'; // your database username
$password = ''; // your database password
$database = 'rentalwebsite'; // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_id = $_SESSION['user_id']; // assuming user_id is stored in session
    $make = $_POST['make'];
    $year = $_POST['year'];
    $model = $_POST['model'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $ramp_type = $_POST['ramp_type'];
    $securement_system = $_POST['securement_system'];
    $num_wheelchair = $_POST['num_wheelchair'];
    $height_clearance = $_POST['height_clearance'];
    $seating_config = $_POST['seating_config'];
    $drive_from_wc = $_POST['drive_from_wc'];
    $vin = $_POST['vin'];
    $accessibility_type = $_POST['accessibility_type'];
    $interior_height = $_POST['interior_height'];
    $door_clearance = $_POST['door_clearance'];
    $ramp_lift_width = $_POST['ramp_lift_width'];
    $registration_document = $_FILES['registration_document']; // file upload for registration document
    $insurance_document = $_FILES['insurance_document']; // file upload for insurance document
    $inspection_report = $_FILES['inspection_report']; // file upload for inspection report
    $vehicle_history = $_POST['vehicle_history'];
    $wheelchair_securement_system = $_POST['wheelchair_securement_system'];
    $num_wheelchair_positions = $_POST['num_wheelchair_positions'];
    $ramp_or_lift_functional = $_POST['ramp_or_lift_functional'];
    $safety_features = $_POST['safety_features'];
    $emergency_equipment = $_POST['emergency_equipment'];
    $roadworthiness = $_POST['roadworthiness'];
    $photos = $_FILES['photos']; // file upload for photos
    $rental_rates = $_POST['rental_rates'];
    $rules_and_restrictions = $_POST['rules_and_restrictions'];
    $availability_schedule = $_POST['availability_schedule'];
    $availability = $_POST['availability']; // added availability field
    $category = $_POST['category']; // added category field

    // Get the current date for date_added
    $date_added = date('Y-m-d H:i:s');

    // Handle file uploads
    $uploads_dir = 'uploads/'; // directory where files will be stored

    // Validate and upload photos
    $uploaded_photos = [];
    foreach ($photos['tmp_name'] as $key => $tmp_name) {
        $photo_name = basename($photos['name'][$key]);
        $photo_path = $uploads_dir . $photo_name;
        // Check if file was uploaded without error
        if (move_uploaded_file($tmp_name, $photo_path)) {
            $uploaded_photos[] = $photo_path;
        } else {
            echo "Error uploading photo: $photo_name.<br>";
        }
    }

    // Save file paths for registration, insurance, and inspection documents
    $registration_doc_path = $uploads_dir . basename($registration_document['name']);
    $insurance_doc_path = $uploads_dir . basename($insurance_document['name']);
    $inspection_report_path = $uploads_dir . basename($inspection_report['name']);

    // Move uploaded files and check for errors
    if (!move_uploaded_file($registration_document['tmp_name'], $registration_doc_path)) {
        echo "Error uploading registration document.<br>";
    }
    if (!move_uploaded_file($insurance_document['tmp_name'], $insurance_doc_path)) {
        echo "Error uploading insurance document.<br>";
    }
    if (!move_uploaded_file($inspection_report['tmp_name'], $inspection_report_path)) {
        echo "Error uploading inspection report.<br>";
    }

    // Convert photos array to a string for database storage
    $photos_string = implode(',', $uploaded_photos);

    // Validate data types for SQL binding
    $year = (int)$year; // Ensure year is an integer
    $num_wheelchair = (int)$num_wheelchair; // Ensure num_wheelchair is an integer
    $height_clearance = (float)$height_clearance; // Ensure height_clearance is a float
    $rental_rates = (float)$rental_rates; // Ensure rental_rates is a float

    // SQL query to insert data into the database
    $sql = "INSERT INTO vehicles (user_id, make, year, model, fuel_type, transmission, ramp_type, securement_system, num_wheelchair, height_clearance, seating_config, drive_from_wc, vin, accessibility_type, interior_height, door_clearance, ramp_lift_width, registration_document, insurance_document, inspection_report, vehicle_history, wheelchair_securement_system, num_wheelchair_positions, ramp_or_lift_functional, safety_features, emergency_equipment, roadworthiness, photos, rental_rates, rules_and_restrictions, availability_schedule, date_added, availability, category)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        if ($stmt->bind_param('isssssssssssssssssssssssssssssssss', 
        $user_id, $make, $year, $model, $fuel_type, $transmission, 
        $ramp_type, $securement_system, $num_wheelchair, $height_clearance, 
        $seating_config, $drive_from_wc, $vin, $accessibility_type, $interior_height, 
        $door_clearance, $ramp_lift_width, $registration_doc_path, $insurance_doc_path, 
        $inspection_report_path, $vehicle_history, $wheelchair_securement_system, 
        $num_wheelchair_positions, $ramp_or_lift_functional, $safety_features, 
        $emergency_equipment, $roadworthiness, $photos_string, $rental_rates, 
        $rules_and_restrictions, $availability_schedule, $date_added, $availability, 
        $category)) {
    
            // Execute the statement
            if ($stmt->execute()) {
                // Redirect after successful insertion
                header("Location: ourVehicles.php?message=Vehicle enlisted successfully");
                exit;
            } else {
                // Error during execution
                echo "Execute error: " . $stmt->error . "<br>";
            }
        } else {
            // Error binding parameters
            echo "Error binding parameters: " . $stmt->error . "<br>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        echo "Error preparing statement: " . $conn->error . "<br>";
    }

    // Close the database connection
    $conn->close();
}
?>
