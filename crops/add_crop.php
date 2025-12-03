<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $crop_name = trim($_POST['crop_name']);
    $date_planted = $_POST['date_planted'];
    $expected_harvest = $_POST['expected_harvest'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO crops (user_id, crop_name, date_planted, expected_harvest) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $crop_name, $date_planted, $expected_harvest);
    mysqli_stmt_execute($stmt);

    header("Location: view_crops.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Crop</title>
</head>
<body>
    <h2>Add New Crop</h2>
    <form method="POST">
        <label>Crop Name:</label><br>
        <input type="text" name="crop_name" required><br><br>

        <label>Date Planted:</label><br>
        <input type="date" name="date_planted" required><br><br>

        <label>Expected Harvest:</label><br>
        <input type="date" name="expected_harvest" required><br><br>

        <button type="submit">Save Crop</button>
    </form>
    <br>
    <a href="view_crops.php">⬅ Back</a>
</body>
</html>