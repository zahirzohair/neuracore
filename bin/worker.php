<?php

require __DIR__ . '/../vendor/autoload.php';

use Zahirzohair\Neuracore\Database\Connection;
use Zahirzohair\Neuracore\Infrastructure\Persistence\MySQLJobRepository;
use Zahirzohair\Neuracore\Application\Job\JobService;
use Zahirzohair\Neuracore\Application\Job\Handlers\SendNotificationHandler;

$pdo = Connection::make();
$jobRepo = new MySQLJobRepository($pdo);

$handlers = [
    'send_notification' => new SendNotificationHandler(),
];

$jobService = new JobService($jobRepo, $handlers);

echo "Worker started...\n";

while (true) {
    $jobService->processNext();
    sleep(2);
}
