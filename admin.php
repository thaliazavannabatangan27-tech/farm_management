<?php
session_start();
include("includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check user in database
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        // Save session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farm Management System - Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="login-container">
    <h2>Farm Management System</h2>
    <form action="index.php" method="POST">
      <label>Username</label>
      <input type="text" name="username" required>
      
      <label>Password</label>
      <input type="password" name="password" required>
      
      <button type="submit">Login</button>
    </form>

    <?php if (isset($error)) { ?>
      <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>
  </div>
</body>
</html>