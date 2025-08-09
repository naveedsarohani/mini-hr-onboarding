<?php

return [
    'dropbox' => [
        'key' => env('DROPBOX_KEY'),
        'secret' => env('DROPBOX_SECRET'),
        'refreshToken' => env('DROPBOX_REFRESH_TOKEN'),
    ],

    'redis' => [
        'host' => env('REDIS_HOST'),
        'port' => env('REDIS_PORT'),
        'database' => env('REDIS_DATABASE'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
    ],

    'mongodb' => [
        'uri' => env('MONGO_URI'),
        'database' => env('MONGO_DATABASE'),
        'collection' => env('MONGO_COLLECTION'),
    ],

    'sendgrid' => [
        'key' => env('SENDGRID_KEY'),
        'email' => env('SENDGRID_EMAIL'),
    ]
];
