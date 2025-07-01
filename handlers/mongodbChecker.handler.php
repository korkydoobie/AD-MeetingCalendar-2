<?php

require_once UTILS_PATH . '/envSetter.util.php';

try {
    $mongo = new MongoDB\Driver\Manager($mongoConfig['uri']);
    $mongo->executeCommand($mongoConfig['db'], new MongoDB\Driver\Command(['ping' => 1]));
    echo "✅ Connected to MongoDB successfully.<br>";
} catch (Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage() . "<br>";
}
