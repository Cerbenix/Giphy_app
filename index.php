<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

$loader = new Twig\Loader\FilesystemLoader('app/View');
$twig = new Twig\Environment($loader);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [\App\Controllers\GifController::class, 'trending']);
    $r->addRoute('POST', '/search', [\App\Controllers\GifController::class, 'searched']);
    $r->addRoute('GET', '/random', [\App\Controllers\GifController::class, 'random']);
    $r->addRoute('GET', '/error/{message}', [\App\Controllers\ErrorController::class, 'show']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header('Location: /error/404');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header('Location: /error/405');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controllerName, $methodName] = $handler;
        $controller = new $controllerName;
        $response = $controller->{$methodName}($vars);
        echo $response;
        break;
}
