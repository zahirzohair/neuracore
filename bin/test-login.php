<?php

require __DIR__ . '/../vendor/autoload.php';

use Zahirzohair\Neuracore\Database\Connection;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLUserRepository;
use Zahirzohair\Neuracore\Application\Auth\AuthService;

$pdo = Connection::make();
$userRepo = new MySQLUserRepository($pdo);
$authService = new AuthService($userRepo);


$user = $authService->attempt('test@example.com', 'secret');

if ($user) {
    echo "Login successful! User ID: " . $user->id();
} else {
    echo "Invalid credentials!";
}
