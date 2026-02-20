<?php

namespace Zahirzohair\Neuracore\Core;

use Zahirzohair\Neuracore\Application\Auth\AuthService;
use Zahirzohair\Neuracore\Application\Event\EventService;
use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;
use Zahirzohair\Neuracore\Core\Router;
use Zahirzohair\Neuracore\Database\Connection;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLEventRepository;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLUserRepository;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLWorkflowRepository;

class App
{
    public function run(): void
    {
        $router = new Router();

        // Create shared services
        $pdo = Connection::make();
        $dispatcher = new EventDispatcher();

        // 👉 Register listener here (OK for now)
        $dispatcher->listen('workflow.created', function ($payload) {
            error_log('Workflow created ID: ' . $payload->workflow_id);
        });

        // Repositories
        $workflowRepo = new MySQLWorkflowRepository($pdo);
        $eventRepo = new MySQLEventRepository($pdo);
        $userRepo = new MySQLUserRepository($pdo);

        // Services
        $eventService = new EventService($eventRepo, $dispatcher);
        $workflowService = new WorkflowService($workflowRepo, $eventService);
        $authService = new AuthService($userRepo);

        // Load routes
        $routes = require __DIR__ . '/../../config/routes.php';
        $routes($router, $workflowService, $authService);

        // Create a Request object
        $request = new Request();

        // Dispatch the route
        $router->dispatch($request->method(), $request->uri(), $request);
    }
}
