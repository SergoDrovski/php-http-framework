<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\RouterInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(Application::class, function ($container){
    return new Application(
        $container->get(MiddlewareResolver::class),
        $container->get(RouterInterface::class),
        new App\Http\Middleware\NotFoundHandler()
    );
});

$container->set(MiddlewareResolver::class, function (){
    return new MiddlewareResolver();
});

$container->set(DispatchMiddleware::class, function ($container){
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(RouteMiddleware::class, function ($container){
    return new RouteMiddleware($container->get(RouterInterface::class));
});

$container->set(RouterInterface::class, function (){
    return new AuraRouterAdapter(new RouterContainer());
});



//инициализация приложения
/** @var Application $app */
$app = $container->get(Application::class);


//инициализация посредников
$app->pipe(App\Http\Middleware\ProfilerMiddleware::class);

//посредник определения маршрута
$app->pipe($container->get(RouteMiddleware::class));

//добавление контроллеров из определившегося маршрута (выполнение логики основного маршрута)
$app->pipe($container->get(MiddlewareResolver::class));


// маршруты
$app->get('index', '/{id}', [
    App\Http\Controllers\IndexController::class,
]);
$app->get('blog', '/blog/{id}', App\Http\Controllers\BlogController::class, ['id' => '\d+']);




// запуск программы
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Laminas\Diactoros\Response());

// Отправка
$emitter = new SapiEmitter();
$emitter->emit($response);
