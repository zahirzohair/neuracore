<?php

namespace Zahirzohair\Neuracore\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable|array $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $method, string $uri, Request $request)
    {
        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }
        // Handle array controller [Class, 'method']
        if (is_array($action)) {
            [$class, $method] = $action;

            $controller = new $class();
            call_user_func([$controller, $method], $request);
        } else {
            // Closure route
            call_user_func($action, $request);
        }
    }
}
