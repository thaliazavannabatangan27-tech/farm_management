<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Crops summary
$crops = mysqli_query($conn, "SELECT COUNT(*) AS total_crops FROM crops");
$crops_count = mysqli_fetch_assoc($crops)['total_crops'];

// Tasks summary
$tasks = mysqli_query($conn, "SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_tasks,
    SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) AS done_tasks,
    COUNT(*) AS total_tasks
FROM tasks");
$tasks_summary = mysqli_fetch_assoc($tasks);

// Resources summary
$resources = mysqli_query($conn, "SELECT COUNT(*) AS total_resources, SUM(quantity) AS total_quantity FROM resources");
$resources_summary = mysqli_fetch_assoc($resources);

// Detailed reports
$crops_detail = mysqli_query($conn, "SELECT c.crop_name, c.date_planted, c.expected_harvest, u.username 
    FROM crops c JOIN users u ON c.user_id = u.user_id ORDER BY c.date_planted DESC");

$tasks_detail = mysqli_query($conn, "SELECT t.task_name, t.schedule_date, t.status, c.crop_name 
    FROM tasks t JOIN crops c ON t.crop_id = c.crop_id ORDER BY t.schedule_date DESC");

$resources_detail = mysqli_query($conn, "SELECT resource_name, quantity FROM resources ORDER BY resource_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farm Reports</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; }
        .container { width: 90%; margin: 40px auto; }
        h2 { background: #343a40; color: #fff; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #6c757d; color: white; }
        .summary-box { display: inline-block; width: 30%; background: #fff; padding: 20px; margin: 10px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.2); text-align: center; }
        .summary-box h3 { margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Farm Management Reports</h2>

        <!-- Summary -->
        <div class="summary-box">
            <h3>Total Crops</h3>
            <p><?php echo $crops_count; ?></p>
        </div>
        <div class="summary-box">
            <h3>Total Tasks</h3>
            <p><?php echo $tasks_summary['total_tasks']; ?> (<?php echo $tasks_summary['done_tasks']; ?> Done, <?php echo $tasks_summary['pending_tasks']; ?> Pending)</p>
        </div>
        <div class="summary-box">
            <h3>Total Resources</h3>
            <p><?php echo $resources_summary['total_resources']; ?> items (<?php echo $resources_summary['total_quantity']; ?> units)</p>
        </div>

        <!-- Crops Detail -->
        <h2>Crops Report</h2>
        <table>
            <tr>
                <th>Crop Name</th>
                <th>Date Planted</th>
                <th>Expected Harvest</th>
                <th>Owner</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($crops_detail)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                <td><?php echo $row['date_planted']; ?></td>
                <td><?php echo $row['expected_harvest']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Tasks Detail -->
        <h2>Tasks Report</h2>
        <table>
            <tr>
                <th>Task Name</th>
                <th>Crop</th>
                <th>Schedule Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($tasks_detail)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['task_name']); ?></td>
                <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                <td><?php echo $row['schedule_date']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Resources Detail -->
        <h2>Resources Report</h2>
        <table>
            <tr>
                <th>Resource Name</th>
                <th>Quantity</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resources_detail)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['resource_name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="../dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>