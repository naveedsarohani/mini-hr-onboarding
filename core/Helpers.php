<?php

use App\Models\User;
use Core\Redirect;
use Core\Response;
use Core\Session;
use Core\View;

if (! function_exists('base_path')) {
    function base_path(string $filePath = ''): string
    {
        $root = dirname(__DIR__, 1);
        return rtrim($root, '/') . '/' . ltrim($filePath, '/');
    }
}

if (! function_exists('view_path')) {
    function view_path(string $filePath = ''): string
    {
        $root = dirname(__DIR__, 1);
        return rtrim($root, '/') . '/resources/views/' . ltrim($filePath, '/');
    }
}

if (! function_exists('env')) {
    function env($key, $default = null)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

if (! function_exists('config')) {
    function config(string $key, $default = null)
    {
        $segments = explode('.', $key);

        $file = array_shift($segments);
        $path = base_path("config/{$file}.php");

        if (!file_exists($path)) {
            throw new \Exception("Config file not found: {$path}");
        }

        $config = require $path;
        foreach ($segments as $segment) {
            if (is_array($config) && array_key_exists($segment, $config)) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }

        return $config;
    }
}

if (! function_exists('route')) {
    function route(string $name, $parameters = null): string
    {
        return redirect()->route($name, $parameters)->routeURL();
    }
}

if (! function_exists('view')) {
    function view(string $template, array $data = []): View
    {
        return new View($template, $data);
    }
}

if (! function_exists('redirect')) {
    function redirect(?string $route = null): Redirect
    {
        return new Redirect($route);
    }
}

if (! function_exists('response')) {
    function response(int $httpCode, string $message, array $data = [], array $headers = [])
    {
        return (new Response)->send($httpCode, $message, $data, $headers);
    }
}

if (! function_exists('auth')) {
    function auth(): ?User
    {
        return session()->get('authenticated_user');
    }
}

if (! function_exists('session')) {
    function session(?string $key = null): mixed
    {
        if (!$key) return new Session;
        return (new Session)->get($key);
    }
}

if (! function_exists('old')) {
    function old(string $field): ?string
    {
        return (new Session)->get("form-data.{$field}");
    }
}

if (! function_exists('error')) {
    function error(string $field): ?string
    {
        return (new Session)->get("form-errors.{$field}");
    }
}

if (! function_exists('param')) {
    function param(string $key): ?string
    {
        return $_GET[$key] ?? null;
    }
}

if (! function_exists('formatDate')) {
    function formatDate(string $date, string $format = 'l, M d, Y'): ?string
    {
        $date = new DateTime($date);
        return $date?->format($format) ?? null;
    }
}

if (! function_exists('cache_key')) {
    function cache_key(array $args, string $prefix = ''): string
    {
        return $prefix . ':' . md5(http_build_query($args));
    }
}

if (! function_exists('get')) {
    function get(string $key): ?string
    {
        return $_GET[$key] ?? null;
    }
}

if (! function_exists('__')) {
    function __(string $data): ?string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
