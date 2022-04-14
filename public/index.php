<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require __DIR__ . '/../vendor/autoload.php';

// Инициализация маршрутов
$aura = new RouterContainer();
$routes = $aura->getMap();


// маршруты
$routes->get('index', '/{id}', [
    App\Http\Controllers\IndexController::class,
]);
$routes->get('blog', '/blog/{id}', App\Http\Controllers\BlogController::class)->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$app = new Application($resolver, new App\Http\Middleware\NotFoundHandler());

//инициализация посредников
$app->pipe(App\Http\Middleware\ProfilerMiddleware::class);

//посредник определения маршрута
$app->pipe(new RouteMiddleware($router));

//добавление контроллеров из определившегося маршрута (выполнение логики основного маршрута)
$app->pipe(new DispatchMiddleware($resolver));


// запуск программы
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);

// Отправка
$emitter = new SapiEmitter();
$emitter->emit($response);
