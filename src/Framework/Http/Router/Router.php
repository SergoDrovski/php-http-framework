<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

//    public function match(ServerRequestInterface $request) : Result
//    {
//        foreach ($this->routes->getRoutes() as $route){
//            if(){
//
//            }
//
//            $patern
//
//            if(){
//
//            }
//        }
//    }
//
//    public function generate($name, array $params = []) : string
//    {
//
//    }

}