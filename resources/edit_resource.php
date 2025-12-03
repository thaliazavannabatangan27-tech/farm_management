<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$resource_id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM resources WHERE resource_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $resource_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resource = mysqli_fetch_assoc($result);

if (!$resource) die("Resource not found!");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resource_name = $_POST['resource_name'];
    $quantity = (int) $_POST['quantity'];

    $update_sql = "UPDATE resources SET resource_name=?, quantity=? WHERE resource_id=?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sii", $resource_name, $quantity, $resource_id);
    mysqli_stmt_execute($update_stmt);

    header("Location: view_resources.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Resource</title>
</head>
<body>
    <h2>Edit Resource</h2>
    <form method="POST">
        <label>Resource Name:</label><br>
        <input type="text" name="resource_name" value="<?php echo htmlspecialchars($resource['resource_name']); ?>" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="<?php echo $resource['quantity']; ?>" min="0" required><br><br>

        <button type="submit">Update Resource</button>
    </form>
    <br>
    <a href="view_resources.php">⬅ Back</a>
</body>
</html>