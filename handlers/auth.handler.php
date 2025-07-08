<?php
declare(strict_types=1);

require_once '../bootstrap.php';

use Utils\Auth;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    Auth::logout();
    header('Location: /index.php?message=logged_out');
    exit();
}

if (Auth::login($pdo, $username, $password)) {
    header('Location: /pages/home/index.php');
    exit();
} else {
    header('Location: /index.php?error=invalid_credentials');
    exit();
}
