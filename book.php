<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'];

// Check if vehicle_id is set
if (isset($_GET['vehicle_id']) && !empty($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
} else {
    echo "Error: Vehicle ID is missing.";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate rental agreement checkbox
    if (!isset($_POST['agree_terms'])) {
        echo "Error: You must agree to the rental agreement.";
        exit;
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pickup_location = $_POST['pickup_location'];
    $dropoff_location = $_POST['dropoff_location'];
    $license = $_POST['license'];
    $age = $_POST['age'];

    // Save booking details in the database with status "Pending"
    $stmt = $conn->prepare("INSERT INTO bookings (vehicle_id, user_id, start_date, end_date, full_name, email, phone, pickup_location, dropoff_location, license, age, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iissssssssi", $vehicle_id, $user_id, $start_date, $end_date, $full_name, $email, $phone, $pickup_location, $dropoff_location, $license, $age);

    if ($stmt->execute()) {
        echo "Booking submitted successfully. Waiting for host approval.";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Book Vehicle - MoveMobility</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AXGWGMVjf_G0wl41LpNLG2OeYWYecLuJ4XD1UyEx6WxJz70wXA-q1gob-5j9t9nkkVlgU0Q6yhAGUZUg&currency=USD"></script> 
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
        .booking-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin: 20px auto;
        }
        .booking-form h2 {
            font-size: 24px;
            color: #005f99;
            margin-bottom: 20px;
        }
        .booking-form label {
            font-size: 16px;
            display: block;
            margin-bottom: 8px;
        }
        .booking-form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .booking-form button {
            background-color: #005f99;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .booking-form button:hover {
            background-color: #0077b6;
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

        .rental-agreement {
    margin-top: 20px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.rental-agreement textarea {
    background-color: #f4f4f4;
    border: none;
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
    <div class="booking-form">
        <h2>Book Your Vehicle</h2>
        <form action="book.php?vehicle_id=<?php echo $vehicle_id; ?>" method="POST">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>"> <!-- Hidden field for vehicle_id -->

            <!-- Personal Information -->
            <h3>Personal Information</h3>
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" required>

            <!-- Booking Details -->
            <h3>Booking Details</h3>
            <label for="pickup_location">Pickup Location:</label>
            <input type="text" name="pickup_location" required>

            <label for="dropoff_location">Drop-off Location (optional):</label>
            <input type="text" name="dropoff_location">

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

        

            <!-- Driver Information -->
            <h3>Driver Information</h3>
            <label for="license">Driver's License Number:</label>
            <input type="text" name="license" required>

            <label for="age">Driver's Age:</label>
            <input type="number" name="age" required>

            <!-- Rental Agreement -->
<h3>Rental Agreement</h3>
<div class="rental-agreement">
    <textarea readonly rows="10" style="width: 100%; padding: 10px;">
    Insert your rental agreement text here. This could be terms and conditions about the rental, including insurance, liability, pickup/drop-off details, and any other policies the renter must follow.
    </textarea>
    <label>
        <input type="checkbox" name="agree_terms" required> I have read and agree to the rental agreement.
    </label>
</div>

            <!-- Submit -->
            <button type="submit">Book Now</button>
        </form>
    </div>
</div>


    <?php if ($show_paypal_button) { ?>
    <div class="paypal-button-container">
        <h2>Complete Payment</h2>
        <!-- PayPal Button Container -->
        <div id="paypal-button-container"></div>
    </div>
    <?php } ?>
</div>

<footer>
    <p>MoveMobility © 2024. All rights reserved.</p>
</footer>

<?php if ($show_paypal_button) { ?>
<script>
    paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $_SESSION['amount'] = 100;?>'  // The amount you want to charge
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            alert('Transaction completed by ' + details.payer.name.given_name);

            // Send an AJAX request to record the payment and booking in the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "record_payment.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Redirect to a success page after payment
                    window.location.href = "payment_success.php?orderID=" + data.orderID;
                } else {
                    alert("Failed to record payment.");
                }
            };
            // Send booking info, order ID, and amount to record_payment.php
            xhr.send("orderID=" + data.orderID + 
    "&amount=<?php echo $_SESSION['amount']; ?>" + 
    "&vehicle_id=<?php echo $vehicle_id; ?>" + 
    "&user_id=<?php echo $user_id; ?>" + 
    "&start_date=<?php echo $_SESSION['start_date']; ?>" + 
    "&end_date=<?php echo $_SESSION['end_date']; ?>" + 
    "&full_name=<?php echo $_SESSION['full_name']; ?>" + 
    "&email=<?php echo $_SESSION['email']; ?>" + 
    "&phone=<?php echo $_SESSION['phone']; ?>" +  // Add phone here
    "&pickup_location=<?php echo $_SESSION['pickup_location']; ?>" +  // Add pickup location here
    "&dropoff_location=<?php echo $_SESSION['dropoff_location']; ?>" +  // Add dropoff location here
    "&license=<?php echo $_SESSION['license']; ?>" +  // Add license here
    "&age=<?php echo $_SESSION['age']; ?>");  // Add age here

        });
    }
}).render('#paypal-button-container');
</script>
<?php } ?>

</body>
</html>
