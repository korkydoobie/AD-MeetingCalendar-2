<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once UTILS_PATH . '/envSetter.util.php';

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