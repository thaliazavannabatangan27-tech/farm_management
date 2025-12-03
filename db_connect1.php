<?php
// includes/db_connect.php
$host = 'localhost';
$user = 'root';
$pass = ''; // XAMPP default is empty
$db   = 'farm_management';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
