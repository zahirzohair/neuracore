<?php

use Zahirzohair\Neuracore\Application\Auth\AuthService;
use Zahirzohair\Neuracore\Application\Event\EventService;
use Zahirzohair\Neuracore\Application\Job\JobService;
use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;
use Zahirzohair\Neuracore\Controllers\AuthController;
use Zahirzohair\Neuracore\Controllers\EventController;
use Zahirzohair\Neuracore\Controllers\JobController;
use Zahirzohair\Neuracore\Controllers\WorkflowController;
use Zahirzohair\Neuracore\Core\Router;

return function (
    Router $router,
    WorkflowService $workflowService,
    AuthService $authService,
    JobService $jobService,
    EventService $eventService
) {
    $router->get('/', function ($request) {
        echo "Welcome to NeuraCore 🚀. here you go";
    });

    // Controllers
    $authController = new AuthController($authService);
    $workflowController = new WorkflowController($workflowService);
    $jobController = new JobController($jobService);
    $eventController = new EventController($eventService);

    $router->get('/login', [$authController, 'showLoginForm']);
    $router->post('/login', [$authController, 'login']);
    $router->get('/register', [$authController, 'showRegisterForm']);
    $router->post('/register', [$authController, 'register']);
    $router->get('/logout', [$authController, 'logout']);

    $router->get('/workflows', [$workflowController, 'index']);
    $router->post('/workflows/create', [$workflowController, 'create']);
    $router->post('/workflows/start', [$workflowController, 'start']);
    $router->post('/workflows/complete', [$workflowController, 'complete']);

    $router->get('/jobs', [$jobController, 'index']);
    $router->get('/events', [$eventController, 'index']);
};
