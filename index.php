<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$status = $_GET['status'] ?? '';
$message = '';

if ($status == 'invalid') {
    $message = 'Invalid username or password.';
}

if ($status == 'empty') {
    $message = 'Please fill in all fields.';
}

if ($status == 'login_required') {
    $message = 'Please login first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Information System - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">

<div class="login-card">
    <div class="login-logo">B</div>

    <h1>Barangay Information System</h1>
    <p>Login using your barangay staff account.</p>

    <?php if ($message != ''): ?>
        <div class="alert error"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="handlers/login_handler.php" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password">
        </div>

        <button type="submit" name="login" class="btn primary full">
            Login
        </button>
    </form>

    <div class="demo-account">
        <strong>Default account</strong><br>
        Username: admin<br>
        Password: admin123
    </div>
</div>

</body>
</html>
