<?php

declare(strict_types=1);

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require __DIR__ . '/../vendor/autoload.php';

// Initialization

$request = ServerRequestFactory::fromGlobals();

// Action
$body = "<h1>Привет Бро!</h1>";

$response = (new HtmlResponse($body, 200,))
    ->withHeader('X-developer', 'ok');

// Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
