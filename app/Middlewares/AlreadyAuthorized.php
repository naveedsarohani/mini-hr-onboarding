<?php

namespace App\Middlewares;

use Core\Request;

class AlreadyAuthorized
{
    public function handle(Request $request, array $args = [], $next)
    {
        if ($request?->user) {
            return redirect('/');
        }

        return $next();
    }
}
