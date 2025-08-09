<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once base_path('core/app.php');
require_once base_path('routes/web.php');

use Core\Route;


Route::prefix('/api')->group(function () {
    require_once base_path('routes/api.php');
});

$request = (object) [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'method' => $_SERVER['REQUEST_METHOD'],
];

try {
    (new Route())->dispatchRequest($request);
} catch (Throwable $e) {
    $statusCode = 500;

    $status = $e->getMessage() ?: 'Something went wrong';
    http_response_code($statusCode);

    require view_path('pages/static/error.php');
}
