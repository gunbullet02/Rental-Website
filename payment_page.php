<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the approved booking details
$sql = "SELECT * FROM bookings WHERE booking_id = ? AND status = 'Approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Booking not found or not approved.";
    exit;
}

// Set booking details in session for PayPal processing
$_SESSION['amount'] = 100;  // Example amount
$_SESSION['vehicle_id'] = $booking['vehicle_id'];
$_SESSION['start_date'] = $booking['start_date'];
$_SESSION['end_date'] = $booking['end_date'];
$_SESSION['full_name'] = $booking['full_name'];
$_SESSION['email'] = $booking['email'];
$_SESSION['phone'] = $booking['phone'];
$_SESSION['pickup_location'] = $booking['pickup_location'];
$_SESSION['dropoff_location'] = $booking['dropoff_location'];
$_SESSION['license'] = $booking['license'];
$_SESSION['age'] = $booking['age'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AXGWGMVjf_G0wl41LpNLG2OeYWYecLuJ4XD1UyEx6WxJz70wXA-q1gob-5j9t9nkkVlgU0Q6yhAGUZUg&currency=USD"></script>
</head>
<body>

<h2>Complete Payment for Your Booking</h2>

<div id="paypal-button-container"></div>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $_SESSION['amount']; ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);

                // Send an AJAX request to record the payment and finalize the booking
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "record_payment.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        window.location.href = "payment_success.php?orderID=" + data.orderID;
                    } else {
                        console.log("Response text: " + xhr.responseText);  // Log response for debugging
                        alert("Failed to record payment.");
                    }
                };

                // Log the data being sent
                console.log("Sending data: ", {
                    orderID: data.orderID,
                    amount: <?php echo $_SESSION['amount']; ?>,
                    vehicle_id: <?php echo $_SESSION['vehicle_id']; ?>,
                    start_date: "<?php echo $_SESSION['start_date']; ?>",
                    end_date: "<?php echo $_SESSION['end_date']; ?>",
                    full_name: "<?php echo $_SESSION['full_name']; ?>",
                    email: "<?php echo $_SESSION['email']; ?>",
                    phone: "<?php echo $_SESSION['phone']; ?>",
                    pickup_location: "<?php echo $_SESSION['pickup_location']; ?>",
                    dropoff_location: "<?php echo $_SESSION['dropoff_location']; ?>",
                    license: "<?php echo $_SESSION['license']; ?>",
                    age: <?php echo $_SESSION['age']; ?>
                });

                xhr.send("orderID=" + data.orderID + 
                    "&amount=" + <?php echo $_SESSION['amount']; ?> + 
                    "&vehicle_id=" + <?php echo $_SESSION['vehicle_id']; ?> + 
                    "&user_id=" + <?php echo $_SESSION['user_id']; ?> + 
                    "&start_date=" + "<?php echo $_SESSION['start_date']; ?>" +
                    "&end_date=" + "<?php echo $_SESSION['end_date']; ?>" +
                    "&full_name=" + "<?php echo $_SESSION['full_name']; ?>" +
                    "&email=" + "<?php echo $_SESSION['email']; ?>" +
                    "&phone=" + "<?php echo $_SESSION['phone']; ?>" +
                    "&pickup_location=" + "<?php echo $_SESSION['pickup_location']; ?>" +
                    "&dropoff_location=" + "<?php echo $_SESSION['dropoff_location']; ?>" +
                    "&license=" + "<?php echo $_SESSION['license']; ?>" +
                    "&age=" + "<?php echo $_SESSION['age']; ?>"
                );
            });
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
