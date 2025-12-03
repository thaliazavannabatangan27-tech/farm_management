<?php
// users.php
session_start();
require_once "includes/db_connect.php";
if (!isset($_SESSION['id'])) { header("Location: index.php"); exit; }

$res = mysqli_query($conn, "SELECT id, username, name, email, role FROM users ORDER BY id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Users</title></head><body>
<h2>Users</h2>
<p><a href="dashboard.php">Back to Dashboard</a></p>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Role</th></tr>
<?php while($u = mysqli_fetch_assoc($res)): ?>
<tr>
<td><?php echo $u['id']; ?></td>
<td><?php echo htmlspecialchars($u['username']); ?></td>
<td><?php echo htmlspecialchars($u['name']); ?></td>
<td><?php echo htmlspecialchars($u['email']); ?></td>
<td><?php echo htmlspecialchars($u['role']); ?></td>
</tr>
<?php endwhile; ?>
</table>
</body></html>
