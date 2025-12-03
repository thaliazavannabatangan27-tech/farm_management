<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$task_id = $_GET['id'] ?? 0;
$status = $_GET['status'] ?? "Pending";

$sql = "UPDATE tasks SET status=? WHERE task_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $task_id);
mysqli_stmt_execute($stmt);

header("Location: view_tasks.php");
exit;
?>