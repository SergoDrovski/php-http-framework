<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RoutNotFoundException;
use Psr\Http\Message\ServerRequestInterface;


class Router
{
    private RouteCollection $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request)
    {
        foreach ($this->routes->getRoutes() as $route){
            $result = $route->match($request);
            if($result){
                return $result;
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []) : string
    {
        foreach ($this->routes->getRoutes() as $route){
            $result = $route->generate($name, $params);
            if($result !== null){
                return $result;
            }
        }
        throw new RoutNotFoundException($name, $params);
    }
}