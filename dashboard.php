<?php
// dashboard.php
session_start();
require_once "includes/db_connect.php";

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

function getCount($conn, $table) {
    // validate table name (simple whitelist)
    $allowed = ['crops','tasks','resources','users'];
    if (!in_array($table, $allowed)) return 0;
    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    return (int)($row['total'] ?? 0);
}

$crops_count = getCount($conn,'crops');
$tasks_count = getCount($conn,'tasks');
$resources_count = getCount($conn,'resources');
$users_count = getCount($conn,'users');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Farm</title>
    <style>
        body{font-family:Arial;background:#f4f6f8;padding:20px}
        .wrap{max-width:1000px;margin:20px auto}
        header{display:flex;justify-content:space-between;align-items:center}
        .cards{display:flex;gap:16px;margin-top:20px}
        .card{flex:1;background:#fff;padding:18px;border-radius:8px;box-shadow:0 0 8px rgba(0,0,0,.06);text-align:center}
        a{color:#007bff;text-decoration:none}
        .toplinks a{margin-left:12px}
    </style>
</head>
<body>
<div class="wrap">
    <header>
        <div>
            <h2>Farm Dashboard</h2>
            <div style="color:#666">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</div>
        </div>
        <div class="toplinks">
            <a href="users.php">Users (<?php echo $users_count; ?>)</a>
            <a href="logout.php" style="color:#b00">Logout</a>
        </div>
    </header>

    <div class="cards">
        <div class="card">
            <h3>Crops</h3>
            <p style="font-size:24px;font-weight:bold"><?php echo $crops_count; ?></p>
            <p><a href="crops/index.php">Manage Crops</a></p>
        </div>
        <div class="card">
            <h3>Tasks</h3>
            <p style="font-size:24px;font-weight:bold"><?php echo $tasks_count; ?></p>
            <p><a href="tasks/index.php">Manage Tasks</a></p>
        </div>
        <div class="card">
            <h3>Resources</h3>
            <p style="font-size:24px;font-weight:bold"><?php echo $resources_count; ?></p>
            <p><a href="resources/index.php">Manage Resources</a></p>
        </div>
    </div>
</div>
</body>
</html>
