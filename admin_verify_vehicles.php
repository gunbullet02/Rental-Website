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

// Fetch all vehicles with pending status
$sql = "SELECT * FROM vehicles WHERE approval_status = 'pending'";
$result = $conn->query($sql);

// Handle approve/reject action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $action = $_POST['action'];

    // Update the vehicle status based on admin's action
    if ($action == 'approve') {
        $update_sql = "UPDATE vehicles SET approval_status = 'approved' WHERE vehicle_id = '$vehicle_id'";
    } elseif ($action == 'reject') {
        $update_sql = "UPDATE vehicles SET approval_status = 'rejected' WHERE vehicle_id = '$vehicle_id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "Vehicle status updated successfully.";
    } else {
        echo "Error updating vehicle status: " . $conn->error;
    }

    // Redirect after action to prevent form resubmission
    header("Location: admin_verify_vehicles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Vehicle Verification - MoveMobility</title>
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
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        form button {
            background-color: #0077b6;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-weight: bold;
        }
        form button:hover {
            background-color: #0096c7;
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
            </div>
        </nav>
    </header>

    <h1 style="text-align: center; color: #005f99;">Admin Vehicle Verification</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Vehicle ID</th>
                <th>Host ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Registration Document</th>
                <th>Insurance Document</th>
                <th>Inspection Report</th>
                <th>Action</th>            
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['vehicle_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['make']; ?></td>
                    <td><?php echo $row['model']; ?></td>
                    <td><?php echo $row['year']; ?></td>

                    <td>
                <?php if ($row['registration_document']): ?>
                    <a href="<?php echo $row['registration_document']; ?>" target="_blank">View</a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['insurance_document']): ?>
                    <a href="<?php echo $row['insurance_document']; ?>" target="_blank">View</a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['inspection_report']): ?>
                    <a href="<?php echo $row['inspection_report']; ?>" target="_blank">View</a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>

                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="vehicle_id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="vehicle_id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No vehicles are pending verification.</p>
    <?php endif; ?>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>
