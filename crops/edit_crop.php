<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$crop_id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM crops WHERE crop_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $crop_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$crop = mysqli_fetch_assoc($result);

if (!$crop) {
    die("Crop not found!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $crop_name = $_POST['crop_name'];
    $date_planted = $_POST['date_planted'];
    $expected_harvest = $_POST['expected_harvest'];

    $update_sql = "UPDATE crops SET crop_name=?, date_planted=?, expected_harvest=? WHERE crop_id=?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sssi", $crop_name, $date_planted, $expected_harvest, $crop_id);
    mysqli_stmt_execute($update_stmt);

    header("Location: view_crops.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Crop</title>
</head>
<body>
    <h2>Edit Crop</h2>
    <form method="POST">
        <label>Crop Name:</label><br>
        <input type="text" name="crop_name" value="<?php echo htmlspecialchars($crop['crop_name']); ?>" required><br><br>

        <label>Date Planted:</label><br>
        <input type="date" name="date_planted" value="<?php echo $crop['date_planted']; ?>" required><br><br>

        <label>Expected Harvest:</label><br>
        <input type="date" name="expected_harvest" value="<?php echo $crop['expected_harvest']; ?>" required><br><br>

        <button type="submit">Update Crop</button>
    </form>
    <br>
    <a href="view_crops.php">⬅ Back</a>
</body>
</html>
