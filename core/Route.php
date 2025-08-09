<?php

namespace Core;

use Closure;
use Exception;
use ReflectionMethod;

class Route
{
    private static array $routes = [];
    private static array $groupStack = [];

    public static function get(string $uri, Closure|array|string $action): self
    {
        return static::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, Closure|array|string $action): self
    {
        return static::addRoute('POST', $uri, $action);
    }

    private static function addRoute(string $method, string $uri, Closure|array|string $action): self
    {
        $context = static::mergeGroupContext();
        $fullUri = '/' . trim(($context['prefix'] ?? '') . '/' . ltrim($uri, '/'), '/');

        static::$routes[$fullUri] = (object) [
            'method'     => $method,
            'action'     => $action,
            'controller' => $context['controller'] ?? null,
            'middleware' => $context['middleware'] ?? [],
            'name'       => null,
        ];

        return new static;
    }

    public static function prefix(string $prefix): self
    {
        return static::pushGroup(['prefix' => rtrim($prefix, '/')]);
    }

    public static function controller(string $controllerClass): self
    {
        return static::pushGroup(['controller' => $controllerClass]);
    }

    public static function middleware(string ...$middlewares): self
    {
        return static::pushGroup(['middleware' => $middlewares]);
    }

    public static function group(Closure $routes): void
    {
        $routes();
        array_pop(static::$groupStack);
    }

    private static function pushGroup(array $attributes): self
    {
        $current = static::$groupStack[count(static::$groupStack) - 1] ?? [];

        // Start with current group context
        $merged = $current;

        // Merge prefix cleanly
        if (isset($attributes['prefix'])) {
            $parentPrefix = isset($current['prefix']) ? rtrim($current['prefix'], '/') : '';
            $childPrefix  = ltrim(rtrim($attributes['prefix'], '/'), '/');
            $merged['prefix'] = $parentPrefix . '/' . $childPrefix;
            $merged['prefix'] = '/' . trim($merged['prefix'], '/'); // ensure single leading slash
        }

        // Merge controller (override)
        if (isset($attributes['controller'])) {
            $merged['controller'] = $attributes['controller'];
        }

        // Merge middleware
        if (isset($attributes['middleware'])) {
            $merged['middleware'] = isset($current['middleware'])
                ? array_merge($current['middleware'], $attributes['middleware'])
                : $attributes['middleware'];
        }

        static::$groupStack[] = $merged;
        return new static;
    }

    private static function mergeGroupContext(): array
    {
        return static::$groupStack ? static::$groupStack[count(static::$groupStack) - 1] : [];
    }

    public function name(string $name): self
    {
        end(static::$routes)->name = $name;
        return $this;
    }

    public function dispatchRequest(object $request)
    {
        foreach (static::$routes as $pattern => $route) {
            $regex = "#^" . preg_replace('/\{([a-z_][a-z0-9_-]*)\}/i', '(?P<$1>[a-zA-Z0-9_\-%]+)', $pattern) . "$#";
            $uri   = rtrim($request->route, '/') ?: '/';

            if (preg_match($regex, $uri, $matches)) {
                if ($route->method !== $request->method) {
                    die($this->fallback('405', 'Method Not Allowed'));
                }

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!empty($route->middleware)) {
                    $this->applyMiddleware($route->middleware, fn() => $this->handleRequest($route, $request, $params));
                } else {
                    $this->handleRequest($route, $request, $params);
                }
                return;
            }
        }

        die($this->fallback('404', 'Page Not Found'));
    }

    private function handleRequest(object $route, object $request, array $parameters = [])
    {
        $httpRequest = new Request(array_merge($_POST, $_GET), $_FILES);

        if ($route->controller) {
            $controller = $route->controller;
            $method     = is_array($route->action) ? $route->action[1] : $route->action;
        } elseif (is_array($route->action)) {
            $controller = $route->action[0];
            $method     = $route->action[1];
        } else {
            echo call_user_func($route->action, $httpRequest, ...$parameters);
            return;
        }

        if (!class_exists($controller)) {
            die("Controller [$controller] not found.");
        }
        if (!method_exists($controller, $method)) {
            die("Method [$method] not found in [$controller]");
        }

        $args = $this->resolveMethodArguments($controller, $method, $httpRequest, $parameters);
        echo (new $controller)->{$method}(...$args);
    }

    private function applyMiddleware(array $middlewareList, Closure $finalHandler)
    {
        $middlewares = (require_once base_path('app/kernal.php'))['middlewares'];
        $request = new Request($_POST, $_FILES);

        $pipeline = array_reverse($middlewareList);
        $next = $finalHandler;

        foreach ($pipeline as $alias) {
            [$name, $params] = explode(':', $alias) + [null, ''];
            $params = $params !== '' ? explode(',', $params) : [];

            if (!isset($middlewares[$name])) {
                die("Middleware alias '{$name}' not defined.");
            }

            $class = $middlewares[$name];
            $instance = new $class();

            $currentNext = $next;
            $next = fn() => $instance->handle($request, $params, $currentNext);
        }

        $response = $next();
        if ($response) echo $response;
    }

    public function registeredRoutes()
    {
        return static::$routes;
    }

    private function fallback(string $statusCode, string $status)
    {
        return view('pages.static.fallback', compact('statusCode', 'status'));
    }

    private function resolveMethodArguments(string $controller, string $method, Request $httpRequest, array $parameters = []): array
    {
        $reflection = new ReflectionMethod($controller, $method);
        $args = [];

        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();
                $args[] = $className === Request::class ? $httpRequest : new $className();
            } elseif (!empty($parameters)) {
                $args[] = array_shift($parameters);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new Exception("Missing required parameter: {$param->getName()}");
            }
        }

        return $args;
    }
}
