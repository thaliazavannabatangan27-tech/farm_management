<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resource_name = trim($_POST['resource_name']);
    $quantity = (int) $_POST['quantity'];

    $sql = "INSERT INTO resources (resource_name, quantity) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $resource_name, $quantity);
    mysqli_stmt_execute($stmt);

    header("Location: view_resources.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Resource</title>
</head>
<body>
    <h2>Add Resource</h2>
    <form method="POST">
        <label>Resource Name:</label><br>
        <input type="text" name="resource_name" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" min="0" required><br><br>

        <button type="submit">Save Resource</button>
    </form>
    <br>
    <a href="view_resources.php">⬅ Back</a>
</body>
</html>
