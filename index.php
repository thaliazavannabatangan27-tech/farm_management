<?php
// index.php (login)
session_start();
require_once "includes/db_connect.php";

if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);

    // NOTE: this example assumes plain-text passwords in DB (for quick testing).
    // For production, store hashed passwords (password_hash) and use password_verify.
    if ($user && $password === $user['password']) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Farm System</title>
    <style>
        body{font-family:Arial; background:#eef; display:flex; align-items:center; justify-content:center; height:100vh}
        .box{background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 8px rgba(0,0,0,.12);width:320px}
        input{width:100%;padding:8px;margin:6px 0;border:1px solid #ccc;border-radius:4px}
        button{width:100%;padding:10px;background:#28a745;border:none;color:#fff;border-radius:4px}
        .error{color:#b00;text-align:center}
    </style>
</head>
<body>
<div class="box">
    <h2 style="text-align:center;margin:0 0 12px">Farm Management</h2>
    <form method="post" action="">
        <label>Username</label>
        <h1>TITE</h1>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <p style="font-size:12px;color:#666;margin-top:10px">Use <strong>admin / 123</strong> (if you imported sample DB)</p>
</div>
</body>
</html>
