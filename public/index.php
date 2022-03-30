<?php

declare(strict_types=1);

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

require __DIR__ . '/../vendor/autoload.php';

// Инициализация маршрутов
$routes = new RouteCollection();


$routes->get('home', '/index', function (){
    return new HtmlResponse('<h1>Hello, bro!</h1>');
});

$routes->get('blog', '/blog/{id}', function (ServerRequestInterface $request){
    $id = $request->getAttribute('id');
    return new JsonResponse(['id'=> $id, 'title' => 'something text']);
}, ['id' => '\d+']);

$router = new Router($routes);

// запуск программы
$request = ServerRequestFactory::fromGlobals();


try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $result->getHandler();
    $response = $action($request);
} catch (RequestNotMatchedException $exception){
    $response = new HtmlResponse('<h1>Not found!</h1>', 404);
}


// Отправка

$emitter = new SapiEmitter();
$emitter->emit($response);
