<?php
// signin.php

// Start the session
session_start();

// Database connection
$servername = "localhost"; // Adjust with your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rentalwebsite"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];  // Assuming 'user_id' is the primary key
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];  // Store the role in session
            $_SESSION['is_admin'] = $user['is_admin'];  // Store the is_admin status in session

            // Redirect based on the user's role and admin status
            if ($user['is_admin'] == 1) {
                // Redirect admin to the admin dashboard
                header("Location: admin_dashboard.php");
                exit;
            } elseif ($user['role'] == 'renter') {
                // Redirect renter to vehicles page
                header("Location: ourVehicles.php");
                exit;
            } elseif ($user['role'] == 'host') {
                // Redirect host to the dashboard page (vehicle listing page)
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that email.";
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
    <title>Sign In - MoveMobility</title>
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
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
            padding: 20px;
        }
        form {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        form h2 {
            text-align: center;
            font-size: 28px;
            color: #005f99;
            margin-bottom: 30px;
        }
        form input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        form input:focus {
            border-color: #0077b6;
        }
        form input[type="submit"] {
            background-color: #0077b6;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #005f99;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
        .signup-link a {
            color: #0077b6;
            text-decoration: none;
            font-size: 14px;
        }
        .signup-link a:hover {
            text-decoration: underline;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 20px;
                width: 90%;
            }
            form h2 {
                font-size: 24px;
            }
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
        <form action="signin.php" method="POST">
            <h2>Sign In</h2>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Sign In">

            <div class="signup-link">
                <p>Need an account? <a href="signup.php">Sign up</a></p>
            </div>
        </form>
    </div>

    <footer>
        <p>MoveMobility © 2024. All rights reserved.</p>
    </footer>
</body>
</html>
