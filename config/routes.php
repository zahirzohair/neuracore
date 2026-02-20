<?php

use Zahirzohair\Neuracore\Application\Auth\AuthService;
use Zahirzohair\Neuracore\Controllers\AuthController;
use Zahirzohair\Neuracore\Core\Router;
use Zahirzohair\Neuracore\Database\Connection;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLUserRepository;
use Zahirzohair\Neuracore\Controllers\WorkflowController;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLWorkflowRepository;
use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;

return function (Router $router) {
    $router->get('/', function () {
        echo "Welcome to NeuraCore 🚀. here you go";
    });

    //$router->get('/login', [AuthController::class, 'showLoginForm']);
    $router->get('/login', function () {
        $pdo = Connection::make();
        $userRepo = new MySQLUserRepository($pdo);
        $authService = new AuthService($userRepo);
        $controller = new AuthController($authService);
        $controller->showLoginForm();
    });

    // $router->post('/login', [AuthController::class, 'login']);
    $router->post('/login', function ($request) {
        $pdo = Connection::make();
        $userRepo = new MySQLUserRepository($pdo);
        $authService = new AuthService($userRepo);
        $controller = new AuthController($authService);
        $controller->login($request);
    });

    // $router->get('/workflows', [$controller, 'index']);
    $router->get('/workflows', function ($request) {
        $pdo = Connection::make();
        $repo = new MySQLWorkflowRepository($pdo);
        $service = new WorkflowService($repo);
        $controller = new WorkflowController($service);
        $controller->index($request);
    });


    //$router->post('/workflows/create', [$controller, 'create']);
    $router->post('/workflows/create', function ($request) {
        $pdo = Connection::make();
        $repo = new MySQLWorkflowRepository($pdo);
        $service = new WorkflowService($repo);
        $controller = new WorkflowController($service);
        $controller->create($request);
    });

    // $router->post('/workflows/start', [$controller, 'start']);
    $router->post('/workflows/start', function ($request) {
        $pdo = Connection::make();
        $repo = new MySQLWorkflowRepository($pdo);
        $service = new WorkflowService($repo);
        $controller = new WorkflowController($service);
        $controller->start($request);
    });

    // $router->post('/workflows/complete', [$controller, 'complete']);
    $router->post('/workflows/create', function ($request) {
        $pdo = Connection::make();
        $repo = new MySQLWorkflowRepository($pdo);
        $service = new WorkflowService($repo);
        $controller = new WorkflowController($service);
        $controller->complete($request);
    });
};
