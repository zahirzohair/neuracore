<?php

use Zahirzohair\Neuracore\Application\Auth\AuthService;
use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;
use Zahirzohair\Neuracore\Controllers\AuthController;
use Zahirzohair\Neuracore\Controllers\WorkflowController;
use Zahirzohair\Neuracore\Core\Router;

return function (
    Router $router,
    WorkflowService $workflowService,
    AuthService $authService
) {
    $router->get('/', function () {
        echo "Welcome to NeuraCore 🚀. here you go";
    });

    // Controllers
    $authController = new AuthController($authService);
    $workflowController = new WorkflowController($workflowService);

    $router->get('/login', [$authController, 'showLoginForm']);
    $router->post('/login', [$authController, 'login']);

    $router->get('/workflows', [$workflowController, 'index']);
    $router->post('/workflows/create', [$workflowController, 'create']);
    $router->post('/workflows/start', [$workflowController, 'start']);
    $router->post('/workflows/complete', [$workflowController, 'complete']);
};
