<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$task_id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM tasks WHERE task_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($result);

if (!$task) die("Task not found!");

// Fetch crops for dropdown
$crops = mysqli_query($conn, "SELECT crop_id, crop_name FROM crops");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = $_POST['task_name'];
    $schedule_date = $_POST['schedule_date'];
    $crop_id = $_POST['crop_id'];

    $update_sql = "UPDATE tasks SET crop_id=?, task_name=?, schedule_date=? WHERE task_id=?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "issi", $crop_id, $task_name, $schedule_date, $task_id);
    mysqli_stmt_execute($update_stmt);

    header("Location: view_tasks.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <h2>Edit Task</h2>
    <form method="POST">
        <label>Task Name:</label><br>
        <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required><br><br>

        <label>Schedule Date:</label><br>
        <input type="date" name="schedule_date" value="<?php echo $task['schedule_date']; ?>" required><br><br>

        <label>Crop:</label><br>
        <select name="crop_id" required>
            <?php while ($row = mysqli_fetch_assoc($crops)) : ?>
                <option value="<?php echo $row['crop_id']; ?>" 
                    <?php echo $row['crop_id'] == $task['crop_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['crop_name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Update Task</button>
    </form>
    <br>
    <a href="view_tasks.php">⬅ Back</a>
</body>
</html>