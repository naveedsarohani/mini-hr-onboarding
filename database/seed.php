<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once base_path('core/app.php');

use Database\Seeders\DepartmentSeeder;
use Database\Seeders\UserSeeder;

$seeders = [
    UserSeeder::class,
    DepartmentSeeder::class
];

foreach ($seeders as $seederClass) {
    echo "Running: " . $seederClass . "\n";
    (new $seederClass)->run();
}
