<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\RouteDate;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $resolver;
    private $default;
    private $router;

    public function __construct(MiddlewareResolver $resolver, RouterInterface $router, callable $default)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->default = $default;
        $this->router = $router;
    }

    public function pipe($middleware)
    {
        parent::pipe($this->resolver->resolve($middleware));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        return $this($request, $response, $this->default);
    }

    public function router($name, $path ,$handler, $methods, $options = []){
        $this->router->addRoute(new RouteDate($name, $path ,$handler, $methods, $options));
    }

    public function get($name, $path ,$handler, $options = []){
        $this->router($name, $path ,$handler, ['GET'], $options);
    }

    public function post($name, $path ,$handler, $options = []){
        $this->router($name, $path ,$handler, ['POST'], $options);
    }

    public function put($name, $path ,$handler, $options = []){
        $this->router($name, $path ,$handler, ['PUT'], $options);
    }

    public function delete($name, $path ,$handler, $options = []){
        $this->router($name, $path ,$handler, ['DELETE'], $options);
    }

    public function patch($name, $path ,$handler, $options = []){
        $this->router($name, $path ,$handler, ['PATCH'], $options);
    }

}