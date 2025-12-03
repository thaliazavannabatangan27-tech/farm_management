<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT t.task_id, t.task_name, t.schedule_date, t.status, c.crop_name 
        FROM tasks t 
        JOIN crops c ON t.crop_id = c.crop_id
        ORDER BY t.schedule_date ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Management</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { width: 80%; margin: 40px auto; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
        a { text-decoration: none; }
        .done { color: green; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Task List</h2>
        <a href="add_task.php">➕ Add New Task</a><br><br>
        <table>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Crop</th>
                <th>Schedule Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['task_id']; ?></td>
                <td><?php echo htmlspecialchars($row['task_name']); ?></td>
                <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                <td><?php echo $row['schedule_date']; ?></td>
                <td class="<?php echo strtolower($row['status']); ?>">
                    <?php echo $row['status']; ?>
                </td>
                <td>
                    <a href="edit_task.php?id=<?php echo $row['task_id']; ?>">✏️ Edit</a> | 
                    <a href="update_status.php?id=<?php echo $row['task_id']; ?>&status=Done">✅ Mark Done</a> | 
                    <a href="update_status.php?id=<?php echo $row['task_id']; ?>&status=Pending">🔄 Reset</a> | 
                    <a href="delete_task.php?id=<?php echo $row['task_id']; ?>" onclick="return confirm('Delete this task?');">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <a href="../dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>