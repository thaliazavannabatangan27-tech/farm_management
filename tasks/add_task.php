<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Fetch crops for dropdown
$crops = mysqli_query($conn, "SELECT crop_id, crop_name FROM crops");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = trim($_POST['task_name']);
    $schedule_date = $_POST['schedule_date'];
    $crop_id = $_POST['crop_id'];

    $sql = "INSERT INTO tasks (crop_id, task_name, schedule_date) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $crop_id, $task_name, $schedule_date);
    mysqli_stmt_execute($stmt);

    header("Location: view_tasks.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body>
    <h2>Add New Task</h2>
    <form method="POST">
        <label>Task Name:</label><br>
        <input type="text" name="task_name" required><br><br>

        <label>Schedule Date:</label><br>
        <input type="date" name="schedule_date" required><br><br>

        <label>Crop:</label><br>
        <select name="crop_id" required>
            <option value="">-- Select Crop --</option>
            <?php while ($row = mysqli_fetch_assoc($crops)) : ?>
                <option value="<?php echo $row['crop_id']; ?>">
                    <?php echo htmlspecialchars($row['crop_name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Save Task</button>
    </form>
    <br>
    <a href="view_tasks.php">⬅ Back</a>
</body>
</html>