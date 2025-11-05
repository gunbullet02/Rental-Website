<?php
session_start();


// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

// ✅ Include dependencies (Cloudinary)
require 'vendor/autoload.php';
use Cloudinary\Cloudinary;

// ✅ Initialize Cloudinary
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dsgpuansp', // your Cloudinary name
        'api_key'    => '655227261787373', // replace with your key
        'api_secret' => 'F2--gml-atX6mCahq6JF1TCjC6g', // replace with your secret
    ],
]);

// ✅ Connect to Database
$conn = new mysqli('localhost', 'root', '', 'rentalwebsite');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $make = $_POST['make'];
    $year = (int)$_POST['year'];
    $model = $_POST['model'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $ramp_type = $_POST['ramp_type'];
    $securement_system = $_POST['securement_system'];
    $num_wheelchair = (int)$_POST['num_wheelchair'];
    $height_clearance = (float)$_POST['height_clearance'];
    $seating_config = $_POST['seating_config'];
    $drive_from_wc = $_POST['drive_from_wc'];
    $vin = $_POST['vin'];
    $accessibility_type = $_POST['accessibility_type'];
    $interior_height = $_POST['interior_height'];
    $door_clearance = $_POST['door_clearance'];
    $ramp_lift_width = $_POST['ramp_lift_width'];
    $vehicle_history = $_POST['vehicle_history'];
    $wheelchair_securement_system = $_POST['wheelchair_securement_system'];
    $num_wheelchair_positions = $_POST['num_wheelchair_positions'];
    $ramp_or_lift_functional = $_POST['ramp_or_lift_functional'];
    $safety_features = $_POST['safety_features'];
    $emergency_equipment = $_POST['emergency_equipment'];
    $roadworthiness = $_POST['roadworthiness'];
    $rental_rates = (float)$_POST['rental_rates'];
    $rules_and_restrictions = $_POST['rules_and_restrictions'];
    $availability_schedule = $_POST['availability_schedule'];
    $availability = $_POST['availability'];
    $category = $_POST['category'];
    $date_added = date('Y-m-d H:i:s');

    // ✅ Upload vehicle photos to Cloudinary
    $photos = $_FILES['photos'];
    $uploaded_photos = [];

    if (isset($photos['tmp_name']) && is_array($photos['tmp_name'])) {
        foreach ($photos['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $upload = $cloudinary->uploadApi()->upload($tmp_name, [
                    'folder' => 'vehicle_uploads/photos'
                ]);
                $uploaded_photos[] = $upload['secure_url'];
            }
        }
    }

    $photos_string = implode(',', $uploaded_photos);

    // ✅ Upload documents to Cloudinary
    $registration_doc_path = '';
    $insurance_doc_path = '';
    $inspection_report_path = '';

    if (isset($_FILES['registration_document']['tmp_name'])) {
        $upload = $cloudinary->uploadApi()->upload($_FILES['registration_document']['tmp_name'], [
            'folder' => 'vehicle_uploads/documents'
        ]);
        $registration_doc_path = $upload['secure_url'];
    }

    if (isset($_FILES['insurance_document']['tmp_name'])) {
        $upload = $cloudinary->uploadApi()->upload($_FILES['insurance_document']['tmp_name'], [
            'folder' => 'vehicle_uploads/documents'
        ]);
        $insurance_doc_path = $upload['secure_url'];
    }

    if (isset($_FILES['inspection_report']['tmp_name'])) {
        $upload = $cloudinary->uploadApi()->upload($_FILES['inspection_report']['tmp_name'], [
            'folder' => 'vehicle_uploads/documents'
        ]);
        $inspection_report_path = $upload['secure_url'];
    }

    // ✅ Insert into Database
    $sql = "INSERT INTO vehicles (
        user_id, make, year, model, fuel_type, transmission, ramp_type, securement_system, 
        num_wheelchair, height_clearance, seating_config, drive_from_wc, vin, accessibility_type, 
        interior_height, door_clearance, ramp_lift_width, registration_document, insurance_document, 
        inspection_report, vehicle_history, wheelchair_securement_system, num_wheelchair_positions, 
        ramp_or_lift_functional, safety_features, emergency_equipment, roadworthiness, photos, 
        rental_rates, rules_and_restrictions, availability_schedule, date_added, availability, category
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            'isssssssssssssssssssssssssssssssss',
            $user_id, $make, $year, $model, $fuel_type, $transmission,
            $ramp_type, $securement_system, $num_wheelchair, $height_clearance,
            $seating_config, $drive_from_wc, $vin, $accessibility_type, $interior_height,
            $door_clearance, $ramp_lift_width, $registration_doc_path, $insurance_doc_path,
            $inspection_report_path, $vehicle_history, $wheelchair_securement_system,
            $num_wheelchair_positions, $ramp_or_lift_functional, $safety_features,
            $emergency_equipment, $roadworthiness, $photos_string, $rental_rates,
            $rules_and_restrictions, $availability_schedule, $date_added, $availability, $category
        );

        if ($stmt->execute()) {
            header("Location: ourVehicles.php?message=Vehicle enlisted successfully");
            exit;
        } else {
            echo "Execute error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Prepare error: " . $conn->error;
    }

    $conn->close();
}
?>
