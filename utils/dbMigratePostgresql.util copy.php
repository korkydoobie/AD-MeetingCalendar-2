<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once UTILS_PATH . '/envSetter.util.php';
$users = require_once DUMMIES_PATH . '/user.staticData.php';

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// ——— Load schema files FIRST ———
$schemaFiles = [
    'users'          => 'database/user.model.sql',
    'meetings'        => 'database/meeting.model.sql',
    'tasks'          => 'database/task.model.sql',
    'meeting_users'  => 'database/meeting_users.model.sql',
];

foreach ($schemaFiles as $table => $filePath) {
    echo "Applying schema from {$filePath}…\n";
    $sql = file_get_contents($filePath);

    if ($sql === false) {
        throw new RuntimeException("Could not read {$filePath}");
    }

    $pdo->exec($sql);
    echo "✅ Created schema from {$filePath}\n";
}

// Truncate tables
echo "Truncating tables…\n";
foreach (array_keys($schemaFiles) as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}
echo "✅ Tables truncated successfully.\n";

echo "Seeding users…\n";

// query preparations. NOTE: make sure they matches order and number
$stmt = $pdo->prepare("
    INSERT INTO users (username, role, first_name, last_name, password)
    VALUES (:username, :role, :fn, :ln, :pw)
");

// plug-in datas from the staticData and add to the database
foreach ($users as $u) {
  $stmt->execute([
    ':username' => $u['username'],
    ':role' => $u['role'],
    ':fn' => $u['first_name'],
    ':ln' => $u['last_name'],
    ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
  ]);
};

$stmt = $pdo->query("SELECT * FROM users");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "---------------------------\n";
    echo "User ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "First Name: " . $user['first_name'] . "\n";
    echo "Last Name: " . $user['last_name'] . "\n";
    echo "Role: " . $user['role'] . "\n";
    echo "---------------------------\n";
};

echo "Dropping old tables…\n";
foreach ([
  'projects',
  'users',
] as $table) {
  // Use IF EXISTS so it won’t error if the table is already gone
  $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
}

echo "Applying schema from database/users.model.sql…\n";

$sql = file_get_contents('database/users.model.sql');

if ($sql === false) {
    throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql";
}

$pdo->exec($sql);