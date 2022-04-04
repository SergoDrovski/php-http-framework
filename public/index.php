<?php

declare(strict_types=1);

use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
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


//$routes->get('index', '/{id}', [App\Http\Controllers\NewsController::class, 'index']);
$routes->get('blog', '/blog/{id}', [App\Http\Controllers\BlogController::class, 'index'])->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();

// запуск программы
$request = ServerRequestFactory::fromGlobals();


try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());



    $response = $action($request);

//    echo "<pre>";
//    var_dump($response);
//    exit();
} catch (RequestNotMatchedException $exception){
    $response = new HtmlResponse('<h1>Not found!</h1>', 404);
}


// Отправка
$emitter = new SapiEmitter();
$emitter->emit($response);
