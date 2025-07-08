<?php
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';

Auth::init();

$user = Auth::user();
if (!$user) {
    header('Location: /pages/login/index.php?error=unauthorized');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="/pages/homepage/assets/css/styles.css">
</head>
<body>
    <div class="home-container">
        <h1>Welcome, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!</h1>
        <form action="/handlers/auth.handler.php?action=logout" method="POST">
    <button type="submit">Logout</button>
</form>
    </div>
</body>
</html>
