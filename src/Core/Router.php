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
        // If action is [object, method]
        if (is_array($action) && is_object($action[0])) {
            [$controller, $method] = $action;
            call_user_func([$controller, $method], $request);
            return;
        }

        // If action is [class, method]
        if (is_array($action) && is_string($action[0])) {
            [$class, $method] = $action;
            $controller = new $class();
            call_user_func([$controller, $method], $request);
            return;
        }

        // Closure
        call_user_func($action, $request);
    }
}
