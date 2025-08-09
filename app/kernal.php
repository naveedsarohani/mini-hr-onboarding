<?php

namespace App;

return [
    'middlewares' => [
        'auth' => \App\Middlewares\RedirectUnauthorized::class,
        'guest' => \App\Middlewares\AlreadyAuthorized::class,
    ]
];
