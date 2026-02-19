<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Zahirzohair\Neuracore\Core\App;

$app = new App();
$app->run();
