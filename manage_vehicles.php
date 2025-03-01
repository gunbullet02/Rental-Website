<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    // Redirect to sign-in page if not logged in or not an admin
    header("Location: signin.php");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentalwebsite";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all vehicles from the database
$sql = "SELECT * FROM vehicles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Vehicles - MoveMobility</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #005f99;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">
                <h1>MoveMobility</h1>
            </div>
            <div class="nav-links">
                <a href="index.html">Home</a>
                <a href="our-vehicles.html">Our Vehicles</a>
                <a href="signup.html">Sign Up</a>
                <a href="signin.php">Sign In</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Vehicle Management</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                <tr>
                    <th>Vehicle ID</th>
                    <th>User ID</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Fuel Type</th>
                    <th>Transmission</th>
                    <th>Ramp Type</th>
                    <th>Securement System</th>
                    <th>Num Wheelchairs</th>
                    <th>Height Clearance</th>
                    <th>Seating Config</th>
                    <th>Drive from WC</th>
                    <th>Date Added</th>
                    <th>Availability</th>
                    <th>Category</th>
                    <th>VIN</th>
                    <th>Accessibility Type</th>
                    <th>Interior Height</th>
                    <th>Door Clearance</th>
                    <th>Ramp/Lift Width</th>
                    <th>Registration Document</th>
                    <th>Insurance Document</th>
                    <th>Inspection Report</th>
                    <th>Vehicle History</th>
                    <th>Wheelchair Securement System</th>
                    <th>Num Wheelchair Positions</th>
                    <th>Ramp or Lift Functional</th>
                    <th>Safety Features</th>
                    <th>Emergency Equipment</th>
                    <th>Roadworthiness</th>
                    <th>Photos</th>
                    <th>Rental Rates</th>
                    <th>Rules and Restrictions</th>
                    <th>Availability Schedule</th>
                </tr>

                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo $row['vehicle_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['make']; ?></td>
                    <td><?php echo $row['model']; ?></td>
                    <td><?php echo $row['year']; ?></td>
                    <td><?php echo $row['fuel_type']; ?></td>
                    <td><?php echo $row['transmission']; ?></td>
                    <td><?php echo $row['ramp_type']; ?></td>
                    <td><?php echo $row['securement_system']; ?></td>
                    <td><?php echo $row['num_wheelchair']; ?></td>
                    <td><?php echo $row['height_clearance']; ?></td>
                    <td><?php echo $row['seating_config']; ?></td>
                    <td><?php echo $row['drive_from_wc']; ?></td>
                    <td><?php echo $row['date_added']; ?></td>
                    <td><?php echo $row['availability']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['vin']; ?></td>
                    <td><?php echo $row['accessibility_type']; ?></td>
                    <td><?php echo $row['interior_height']; ?></td>
                    <td><?php echo $row['door_clearance']; ?></td>
                    <td><?php echo $row['ramp_lift_width']; ?></td>
                    <td><?php echo $row['registration_document']; ?></td>
                    <td><?php echo $row['insurance_document']; ?></td>
                    <td><?php echo $row['inspection_report']; ?></td>
                    <td><?php echo $row['vehicle_history']; ?></td>
                    <td><?php echo $row['wheelchair_securement_system']; ?></td>
                    <td><?php echo $row['num_wheelchair_positions']; ?></td>
                    <td><?php echo $row['ramp_or_lift_functional']; ?></td>
                    <td><?php echo $row['safety_features']; ?></td>
                    <td><?php echo $row['emergency_equipment']; ?></td>
                    <td><?php echo $row['roadworthiness']; ?></td>
                    <td><?php echo $row['photos']; ?></td>
                    <td><?php echo $row['rental_rates']; ?></td>
                    <td><?php echo $row['rules_and_restrictions']; ?></td>
                    <td><?php echo $row['availability_schedule']; ?></td>

                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No vehicles found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>
