<?php

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Distribute the data using array key
$typeConfig = [
    'key' => $_ENV['ENV_NAME'],
];