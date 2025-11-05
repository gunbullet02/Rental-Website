<?php
// enlist-vehicle.php

// Start session and check if the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in page if not logged in
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enlist Vehicle - MoveMobility</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            width: 70%;
            margin: 50px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-title {
            text-align: center;
            color: #005f99;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        select, input, textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            background-color: #f9f9f9;
        }
        button {
            background-color: #005f99;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0077b6;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #005f99;
            color: white;
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
        <h2 class="form-title">Enlist Your Vehicle</h2>
        <form action="submit-vehicle.php" method="POST" enctype="multipart/form-data">
            
            <!-- Vehicle Information and Documentation -->
            <h3>Vehicle Information and Documentation</h3>
            <div class="form-group">
                <label for="make">Vehicle Make</label>
                <select name="make" id="make" required>
                    <option value="Toyota">Toyota</option>
                    <option value="Honda">Honda</option>
                    <option value="DGC">DGC</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" name="model" id="model" required placeholder="Enter vehicle model">
            </div>

            <div class="form-group">
                <label for="year">Year</label>
                <select name="year" id="year" required>
                    <?php
                    for ($year = 1900; $year <= date('Y'); $year++) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="vin">Vehicle Identification Number (VIN)</label>
                <input type="text" name="vin" id="vin" required placeholder="Enter vehicle VIN">
            </div>

            <div class="form-group">
                <label for="fuel_type">Fuel Type</label>
                <select name="fuel_type" id="fuel_type" required>
                    <option value="Gasoline">Gasoline</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Electric">Electric</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
            </div>

            <div class="form-group">
                <label for="transmission">Transmission Type</label>
                <select name="transmission" id="transmission" required>
                    <option value="Automatic">Automatic</option>
                    <option value="Manual">Manual</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ramp_type">Ramp Type</label>
                <select name="ramp_type" id="ramp_type" required>
                    <option value="Foldable">Foldable</option>
                    <option value="Fixed">Fixed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="securement_system">Securement System</label>
                <select name="securement_system" id="securement_system" required>
                    <option value="Tie-down straps">Tie-down straps</option>
                    <option value="Docking system">Docking system</option>
                </select>
            </div>

            <div class="form-group">
                <label for="num_wheelchair">Number of Wheelchairs</label>
                <input type="number" name="num_wheelchair" id="num_wheelchair" required placeholder="Enter number of wheelchairs">
            </div>

            <div class="form-group">
                <label for="height_clearance">Height Clearance</label>
                <input type="number" name="height_clearance" id="height_clearance" required placeholder="Enter height clearance in inches">
            </div>

            <div class="form-group">
                <label for="seating_config">Seating Configuration</label>
                <input type="text" name="seating_config" id="seating_config" required placeholder="Enter seating configuration (e.g., 2+2)">
            </div>

            <div class="form-group">
                <label for="drive_from_wc">Drive from Wheelchair Accessible</label>
                <select name="drive_from_wc" id="drive_from_wc" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>

            <!-- Availability and Category Information -->
<h3>Availability and Category</h3>

<div class="form-group">
    <label for="category">Vehicle Category</label>
    <select name="category" id="category" required>
        <option value="MINIVAN">MINIVAN</option>
        <option value="FULL SIZE  VAN">FULL SIZE VAN</option>
       
    </select>
</div>

<div class="form-group">
    <label for="availability">Availability</label>
    <textarea name="availability" id="availability" rows="4" placeholder="Enter vehicle availability (e.g., available weekends, available from 9 AM to 5 PM)"></textarea>
</div>

            <div class="form-group">
                <label for="accessibility_type">Accessibility Type</label>
                <select name="accessibility_type" id="accessibility_type" required>
                    <option value="Side-entry ramp">Side-entry ramp</option>
                    <option value="Rear-entry lift">Rear-entry lift</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="interior_height">Interior Height</label>
                <input type="number" name="interior_height" id="interior_height" required placeholder="Enter interior height in inches">
            </div>

            <div class="form-group">
                <label for="door_clearance">Door Clearance</label>
                <input type="number" name="door_clearance" id="door_clearance" required placeholder="Enter door clearance in inches">
            </div>

            <div class="form-group">
                <label for="ramp_lift_width">Ramp/Lift Width</label>
                <input type="number" name="ramp_lift_width" id="ramp_lift_width" required placeholder="Enter ramp/lift width in inches">
            </div>

            <div class="form-group">
                <label for="registration_document">Registration Document</label>
                <input type="file" name="registration_document" id="registration_document" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="form-group">
                <label for="insurance_document">Insurance Document</label>
                <input type="file" name="insurance_document" id="insurance_document" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="form-group">
                <label for="inspection_report">Inspection Report</label>
                <input type="file" name="inspection_report" id="inspection_report" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="form-group">
                <label for="vehicle_history">Vehicle History</label>
                <textarea name="vehicle_history" id="vehicle_history" rows="4" placeholder="Enter vehicle history"></textarea>
            </div>

            <div class="form-group">
                <label for="wheelchair_securement_system">Wheelchair Securement System</label>
                <select name="wheelchair_securement_system" id="wheelchair_securement_system" required>
                    <option value="Tie-down straps">Tie-down straps</option>
                    <option value="Docking system">Docking system</option>
                </select>
            </div>

            <div class="form-group">
                <label for="num_wheelchair_positions">Number of Wheelchair Positions</label>
                <input type="number" name="num_wheelchair_positions" id="num_wheelchair_positions" required placeholder="Enter number of wheelchair positions">
            </div>

            <div class="form-group">
                <label for="ramp_or_lift_functional">Is Ramp/Lift Functional?</label>
                <select name="ramp_or_lift_functional" id="ramp_or_lift_functional" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="form-group">
            <label for="safety_features">Safety Features</label>
            <textarea name="safety_features" id="safety_features" rows="4" placeholder="Enter vehicle safety features (e.g., airbags, ABS)"></textarea>
        </div>

        <div class="form-group">
            <label for="emergency_equipment">Emergency Equipment</label>
            <textarea name="emergency_equipment" id="emergency_equipment" rows="4" placeholder="Enter emergency equipment (e.g., fire extinguisher, first aid kit)"></textarea>
        </div>

        <div class="form-group">
            <label for="roadworthiness">Roadworthiness</label>
            <textarea name="roadworthiness" id="roadworthiness" rows="4" placeholder="Enter roadworthiness status (e.g., vehicle inspection, certification)"></textarea>
        </div>

            <div class="form-group">
                <label for="photos">Upload Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept=".jpg,.jpeg,.png" required>
            </div>

            <!-- Rental and Availability Information -->
            <h3>Rental and Availability Information</h3>
            <div class="form-group">
                <label for="rental_rates">Rental Rates (USD)</label>
                <input type="number" name="rental_rates" id="rental_rates" required placeholder="Enter daily rental rate">
            </div>

            <div class="form-group">
                <label for="rules_and_restrictions">Rules and Restrictions</label>
                <textarea name="rules_and_restrictions" id="rules_and_restrictions" rows="4" placeholder="Enter any rules and restrictions for vehicle rental"></textarea>
            </div>

            <div class="form-group">
                <label for="availability_schedule">Availability Schedule</label>
                <textarea name="availability_schedule" id="availability_schedule" rows="4" placeholder="Enter availability schedule for vehicle"></textarea>
            </div>

            <button type="submit">Submit Vehicle</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 MoveMobility. All rights reserved.</p>
    </footer>
</body>
</html>
