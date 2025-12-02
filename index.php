<?php
session_start();
require_once "includes/db_connect.php";

if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);

    if ($user && $password === $user['password']) {  
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $success = true;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farm Management System - Login</title>
    <link rel="stylesheet" href="./assets/assets/css/style.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<div class="login-wrapper">
    <div class="login-box">
        <h2>Farm Management</h2>
        <p>Efficiency and Growth in Every Harvest</p>

        <form method="post" action="">
            <div class="input-group">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <button type="submit">Login</button>
        </form>

    </div>
</div>

<?php if ($error): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?php echo $error; ?>',
        confirmButtonColor: '#d33'
    });
});
</script>
<?php endif; ?>

<?php if ($success): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    Swal.fire({
        icon: 'success',
        title: 'Login Successful!',
        text: 'Redirecting to dashboard...',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = "dashboard.php";
    });
});
</script>
<?php endif; ?>

</body>
</html>
