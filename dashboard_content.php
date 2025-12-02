<?php
session_start();
require_once "includes/db_connect.php";

function getCount($conn, $table) {
    $allowed = ['crops','tasks','resources','users'];
    if (!in_array($table, $allowed)) return 0;
    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    return (int)$row['total'];
}

$crops_count = getCount($conn,'crops');
$tasks_count = getCount($conn,'tasks');
$resources_count = getCount($conn,'resources');
$users_count = getCount($conn,'users');
?>

<header>
    <h2>Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
</header>

<div class="cards">
    <div class="card">
        <h3>Crops</h3>
        <p class="count"><?php echo $crops_count; ?></p>
        <a href="#" data-page="crops/index.php">Manage Crops</a>
    </div>

    <div class="card">
        <h3>Tasks</h3>
        <p class="count"><?php echo $tasks_count; ?></p>
        <a href="#" data-page="tasks/index.php">Manage Tasks</a>
    </div>

    <div class="card">
        <h3>Resources</h3>
        <p class="count"><?php echo $resources_count; ?></p>
        <a href="#" data-page="resources/index.php">Manage Resources</a>
    </div>

    <div class="card">
        <h3>Users</h3>
        <p class="count"><?php echo $users_count; ?></p>
        <a href="#" data-page="users.php">Manage Users</a>
    </div>
</div>
