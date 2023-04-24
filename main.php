<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new \App\Controllers\GifController();
$app->run();

//http://localhost:8000/main.php