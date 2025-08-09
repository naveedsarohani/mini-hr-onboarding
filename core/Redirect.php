<?php

namespace Core;

use Core\Session;

class Redirect
{
    private string $routeURL;
    private array $sessionData = [];

    public function __construct(?string $routeURL = null)
    {
        $routeURL && $this->routeURL = $routeURL;
    }

    public function with(string $key, $data): Redirect
    {
        $this->sessionData[$key] = $data;
        return $this;
    }

    public function withInputs()
    {
        $this->sessionData['form-data'] = (new Request(array_merge($_POST, $_GET)))->form;
        return $this;
    }

    public function withToast(string $type, string $message): Redirect
    {
        $this->sessionData['toast-message'] = compact('type', 'message');
        return $this;
    }

    public function route(string $name, $parameters = null): Redirect
    {
        $routes = (new Route)->registeredRoutes();

        $matchedRoute = null;
        foreach ($routes as $route => $properties) {
            if (isset($properties->name)) {
                if ($properties->name === $name) {
                    $matchedRoute = $route;
                    break;
                }
            }
        }

        if ($matchedRoute === null) {
            die("Unknown route name [$name] for route.");
        }

        $placeholders = preg_match_all('/{[a-zA-Z0-9%]+}/', $matchedRoute);

        if ($parameters === null) {
            $this->routeURL = $matchedRoute;
            return $this;
        } elseif (is_array($parameters)) {
            if (count($parameters) !== $placeholders) {
                die("Incorrect number of parameters for route [$name].");
            }
            foreach ($parameters as $parameter) {
                $matchedRoute = preg_replace('/{[a-zA-Z0-9%]+}/', $parameter, $matchedRoute, 1);
            }
        } else {
            if ($placeholders !== 1) {
                die("Incorrect number of parameters for route [$name].");
            }
            $matchedRoute = preg_replace('/{[a-zA-Z0-9%]+}/', $parameters, $matchedRoute, 1);
        }

        $this->routeURL = $matchedRoute;
        return $this;
    }

    public static function back(): Redirect
    {
        $previous = $_SERVER['HTTP_REFERER'] ?? '/';
        return new Redirect($previous);
    }

    public function routeURL(): string
    {
        return $this->routeURL;
    }

    public function fallback(?string $statusCode = '404', ?string $status = 'Pages Not Found')
    {
        http_response_code($statusCode);
        return view('pages.static.error', compact('status', 'statusCode'));
    }

    public function __toString()
    {
        if (!empty($this->sessionData)) {
            $session = new Session;

            foreach ($this->sessionData as $key => $data) {
                $session->set($key, $data);
            }
        }

        ob_start();
        header('location: ' . $this->routeURL);
        return ob_get_clean();
    }
}
