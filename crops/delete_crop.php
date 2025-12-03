<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$crop_id = $_GET['id'] ?? 0;
$sql = "DELETE FROM crops WHERE crop_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $crop_id);
mysqli_stmt_execute($stmt);

header("Location: view_crops.php");
exit;
?>