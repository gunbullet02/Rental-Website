<?php
session_start();

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: signin.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "rentalwebsite");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users with uploaded IDs
$sql = "SELECT user_id, email, id_url, id_verification_status FROM users WHERE id_url IS NOT NULL ORDER BY id_verification_status ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Verification - MoveMobility Admin</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #005f99; padding: 10px 20px; color: white; }
        nav a { color: white; text-decoration: none; padding: 14px 20px; font-weight: bold; }
        nav a:hover { background-color: #0077b6; transition: 0.3s; }
        .container { padding: 40px; }
        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #005f99; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        a { color: #0077b6; font-weight: bold; }
        footer { text-align: center; padding: 15px; background: #005f99; color: white; position: fixed; width: 100%; bottom: 0; }
    </style>
</head>
<body>

<header>
    <nav>
        <h1>MoveMobility - Admin Panel</h1>
    </nav>
</header>

<div class="container">
    <h1>User ID Submissions</h1>
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>User Email</th>
                    <th>ID Uploaded</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['id_url']); ?>" target="_blank">View ID</a>
                        </td>
                        <td><?php echo htmlspecialchars($row['id_verification_status']); ?></td>
                        <td>
                            <?php if ($row['id_verification_status'] == 'Pending'): ?>
                                <a href="verify_action.php?user_id=<?php echo $row['user_id']; ?>&action=verify">Verify</a> |
                                <a href="verify_action.php?user_id=<?php echo $row['user_id']; ?>&action=reject">Reject</a>
                            <?php else: ?>
                                <span>No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No ID submissions yet.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>MoveMobility Admin &copy; 2024</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
