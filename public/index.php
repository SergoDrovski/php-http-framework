<?php

declare(strict_types=1);

use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require __DIR__ . '/../vendor/autoload.php';

// Initialization

$request = ServerRequestFactory::fromGlobals();

// Action------------------------------------------------------

$routes = new RouteCollection();

//$routes->get('home', '/index', function (){ return 'Hello!';});
$routes->post('home', '/change', function ($request){ return 'Create Data';});
$routes->any('home', '/', function (){ return 'About any';});

$router = new Router($routes);

$result = $router->match($request);

echo "<pre>";
var_dump($result);
exit();

//$response = (new HtmlResponse($body, 200,))
//    ->withHeader('X-developer', 'ok');

// Sending

//$emitter = new SapiEmitter();
//$emitter->emit($response);
