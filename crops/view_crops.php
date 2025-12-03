<?php
session_start();
require_once "../includes/db_connect.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT c.crop_id, c.crop_name, c.date_planted, c.expected_harvest, u.username 
        FROM crops c 
        JOIN users u ON c.user_id = u.user_id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Crops</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { width: 80%; margin: 40px auto; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #28a745; color: white; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crops List</h2>
        <a href="add_crop.php">➕ Add New Crop</a><br><br>
        <table>
            <tr>
                <th>ID</th>
                <th>Crop Name</th>
                <th>Date Planted</th>
                <th>Expected Harvest</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['crop_id']; ?></td>
                <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                <td><?php echo $row['date_planted']; ?></td>
                <td><?php echo $row['expected_harvest']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <a href="edit_crop.php?id=<?php echo $row['crop_id']; ?>">✏️ Edit</a> | 
                    <a href="delete_crop.php?id=<?php echo $row['crop_id']; ?>" onclick="return confirm('Delete this crop?');">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <a href="../dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>