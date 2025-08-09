<?php

namespace App\Middlewares;

use Closure;
use Core\Request;

class RedirectUnauthorized
{
    public function handle(Request $request, array $args = [], Closure $next)
    {
        if (!empty($args) && in_array('api', $args)) {
            $authorization = array_key_exists('Authorization', $request->headers)
                ? $request->headers['Authorization']
                : null;

            if ($authorization != true) return response(401, 'Unauthenticated');
        } else if (!$request?->user) {
            return redirect()->route('auth.login');
        }

        return $next();
    }
}
