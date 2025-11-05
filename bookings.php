<?php
session_start();

// Check if the user is logged in and is a host
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's details from the database
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rentalwebsite"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Updated SQL query to also fetch the email of the user who requested the booking
$sql = "
    SELECT bookings.*, users.email
    FROM bookings
    JOIN users ON bookings.user_id = users.user_id
    WHERE bookings.vehicle_id IN (SELECT vehicle_id FROM vehicles WHERE user_id = ?)
    ORDER BY bookings.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Requests - MoveMobility</title>
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
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #005f99;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #0077b6;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #005f99;
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
                <a href="index.html">Home</a>
                <a href="our-vehicles.html">Our Vehicles</a>
                <a href="signup.html">Sign Up</a>
                <a href="signin.php">Sign In</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Your Booking Requests</h1>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>User Email</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Pending'): ?>
                                    <a href="approve_booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=approve">Approve</a> |
                                    <a href="approve_booking.php?booking_id=<?php echo $row['booking_id']; ?>&action=deny">Deny</a>
                                <?php else: ?>
                                    <span>Closed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No new booking requests.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>