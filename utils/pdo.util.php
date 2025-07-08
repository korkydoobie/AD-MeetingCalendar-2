<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/utils/envSetter.util.php';

use Dotenv\Dotenv;

$envPath = dirname(__DIR__);
Dotenv::createImmutable($envPath)->load();

$dsn = sprintf(
    'pgsql:host=%s;port=%s;dbname=%s',
    $_ENV['PG_HOST'],
    $_ENV['PG_PORT'],
    $_ENV['PG_DB']
);

try {
    $pdo = new PDO($dsn, $_ENV['PG_USER'], $_ENV['PG_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
