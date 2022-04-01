<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Route\RegexpRoute;
use Framework\Http\Router\Route\RouteInterface;

class RouteCollection
{
    private array $routes = [];

    public function addRoute(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    public function add($name, $pattern, $handler, array $methods, array $tokens = [])
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler, $methods, $tokens));
    }

    public function any($name, $pattern, $handler, array $tokens = [])
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler, ['GET', 'POST', 'PATCH'], $tokens));
    }

    public function get($name, $pattern, $handler, array $tokens = [])
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler, ['GET'], $tokens));
    }

    public function post($name, $pattern, $handler, array $tokens = [])
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler, ['POST'], $tokens));
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

}