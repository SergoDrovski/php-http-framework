<?php

declare(strict_types=1);

use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

require __DIR__ . '/../vendor/autoload.php';

// Инициализация маршрутов
$aura = new RouterContainer();
$routes = $aura->getMap();

// маршруты

$routes->get('index', '/{id}', function (ServerRequestInterface $request) {
    $profiler = new ProfilerMiddleware();
    $action = [new (App\Http\Controllers\IndexController::class),'index'];
    return $profiler($request, function (ServerRequestInterface $request) use ($action){
        return  $action($request);
    });
});

//echo "<pre>";
//var_dump(new (App\Http\Controllers\NewsController::class . ':index'));
//exit();

$routes->get('index', '/{id}', [
    ProfilerMiddleware::class,
    App\Http\Controllers\NewsController::class . '@index',
]);
$routes->get('blog', '/blog/{id}', [App\Http\Controllers\BlogController::class, 'index'])->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);


$resolver = new MiddlewareResolver();
$pipeline = new Pipeline();

// запуск программы
$request = ServerRequestFactory::fromGlobals();


try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }

    $handler = $result->getHandler();

    echo "<pre>";
    var_dump($resolver->resolve($handler));
    exit();

//    $pipeline->pipe($resolver->resolve($handler));


} catch (RequestNotMatchedException $exception){}
$response = $pipeline($request, new Middleware\NotFoundHandler());

// Отправка
$emitter = new SapiEmitter();
$emitter->emit($response);
