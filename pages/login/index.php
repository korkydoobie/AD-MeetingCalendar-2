<?php
if (headers_sent($file, $line)) {
    die("🔥 Headers already sent in $file on line $line");
}
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';

Auth::init();

$error = $_GET['error'] ?? '';
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/pages/login/assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="/handlers/auth.handler.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
