<?php
// Database configuration
$servername = "localhost";
$username = "root";  // Default MySQL username
$password = "";      // Default MySQL password (leave empty if no password)
$dbname = "rentalwebsite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password for security
    $role = mysqli_real_escape_string($conn, $_POST['role']);  // Capture the role (renter or host)

    // Check if the email already exists
    $email_check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($email_check);

    if ($result->num_rows > 0) {
        echo "Email already exists!";
    } else {
        // Prepare and bind the SQL statement to include the role
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);  // Bind the role as well

        // Execute the query and check for errors
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
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
    <title>Sign Up - MoveMobility</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        header {
            background-color: #005f99;
            padding: 15px 20px;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            font-weight: 600;
            font-size: 16px;
        }
        nav a:hover {
            background-color: #0077b6;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            padding: 20px;
            background-color: #f5f5f5;
        }
        form {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            box-sizing: border-box;
        }
        form h2 {
            font-size: 26px;
            color: #005f99;
            text-align: center;
            margin-bottom: 25px;
        }
        form h3 {
            font-size: 16px;
            color: #005f99;
            margin-bottom: 10px;
            font-weight: normal;
        }
        form input,
        form select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }
        form input:focus,
        form select:focus {
            border: 1px solid #0077b6;
            outline: none;
        }
        form select {
            background-color: white;
        }
        form input[type="submit"] {
            background-color: #0077b6;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
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

    <div class="form-container">
        <form action="signup.php" method="POST">
            <h2>Create Account</h2>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <h3>Select Role</h3>
            <select name="role" required>
                <option value="renter">Renter</option>
                <option value="host">Host</option>
            </select>
            <input type="submit" value="Sign Up">
        </form>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>

</body>
</html>
