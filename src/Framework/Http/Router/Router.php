<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
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
            if($route->methods && !\in_array($request->getMethod(), $route->methods, true)){
                continue;
            }

            $pattern = preg_replace_callback('/\{([^\}]+)\}/', function ($matches) use ($route) {
            $argument = $matches[1];
            $replace = $route->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
            }, $route->pattern);


//            if (preg_match($pattern, $request->getUri()->getPath(), $matches)) {
//                return new Result(
//                  $route->name,
//                  $route->handler,
//                  array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
//                );
//            }
        }
        return 'rout not found';
//        throw new RequestNotMatchedException($request);
    }

//    public function generate($name, array $params = []) : string
//    {
//
//    }

    /**
     * @return RouteCollection
     */
    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }

}