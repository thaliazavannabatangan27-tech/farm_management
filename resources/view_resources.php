<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT * FROM resources ORDER BY resource_name ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resources Management</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { width: 80%; margin: 40px auto; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #6c757d; color: white; }
        a { text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Resources List</h2>
        <a href="add_resource.php">➕ Add Resource</a><br><br>
        <table>
            <tr>
                <th>ID</th>
                <th>Resource Name</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['resource_id']; ?></td>
                <td><?php echo htmlspecialchars($row['resource_name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <a href="edit_resource.php?id=<?php echo $row['resource_id']; ?>">✏️ Edit</a> | 
                    <a href="delete_resource.php?id=<?php echo $row['resource_id']; ?>" onclick="return confirm('Delete this resource?');">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <a href="../dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>