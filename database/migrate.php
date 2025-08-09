<?php

require __DIR__ . '/../vendor/autoload.php';
require_once base_path('core/app.php');

$files = glob(base_path('database/migrations') . '/*.php');
sort($files);

foreach ($files as $file) {
    $filename = basename($file);
    
    $migrationClass = require $file;
    $migration = new $migrationClass();
    
    if (method_exists($migration, 'up')) {
        $migration->up();
    }
    
    echo "Migrating: {$filename}\n";
}
