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
$models = [
  'user.model.sql',
  'project.model.sql',
  'task.model.sql',
  'project_users.model.sql'
];

foreach ($models as $modelFile) {
  echo "Applying schema from database/{$modelFile}…\n";
  $sql = file_get_contents("database/{$modelFile}");

  if ($sql === false) {
    throw new RuntimeException("Could not read database/{$modelFile}");
  }

  $pdo->exec($sql);
  echo "✅ Created schema from {$modelFile}\n";
}

// ——— Truncate tables AFTER ———
echo "Truncating tables…\n";
foreach (['project_users', 'tasks', 'projects', 'users'] as $table) {
  $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}
echo "✅ Tables truncated successfully.\n";
