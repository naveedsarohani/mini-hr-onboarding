<?php

use Dotenv\Dotenv;

if (file_exists(base_path('.env'))) {
    $dotenv = Dotenv::createImmutable(base_path());
    $dotenv->load();

    foreach ($_ENV as $key => $value) {
        putenv(sprintf('%s=%s', $key, $value));
    }
}