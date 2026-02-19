<?php

namespace Zahirzohair\Neuracore\Core;

class App
{
    public function run(): void
    {
        $router = new Router();

        // Load routes
        $routes = require __DIR__ . '/../../config/routes.php';
        $routes($router);

        // Create a Request object
        $request = new Request();

        // Dispatch the route
        $router->dispatch($request->method(), $request->uri(), $request);
    }
}
