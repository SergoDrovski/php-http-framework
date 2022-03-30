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
            // Проверяем совпадение метода в запросе с методами маршрутов

            if($route->methods && !\in_array($request->getMethod(), $route->methods, true)){
                continue;
            }

            // Получаем рег.выражение для извлечения пути из URLa

            $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use ($route) {
            $argument = $matches[1];
            $replace = $route->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
            }, $route->pattern);

            $addSlashesPattern = addcslashes($pattern, '/');
            $path = $request->getUri()->getPath();

            if (preg_match("/{$addSlashesPattern}/m", $path, $matches)) {
                return new Result(
                  $route->name,
                  $route->handler,
                  array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []) : string
    {
        $arguments = array_filter($params);
        foreach ($this->routes->getRoutes() as $route){
            // Проверяем совпадение имени роута в коллекции маршрутов
            if($name !== $route->name){
                continue;
            }

            // с помощью рег.выражения получаем реальный URL с аргументами вместо маски

            $url = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
                $argument = $matches[1];
                if(!array_key_exists($argument, $arguments)){
                    return new \InvalidArgumentException("Missing parameter {$argument}");
                }
                return $arguments[$argument];
            }, $route->pattern);

            if ($url !== null) {
                return $url;
            }
        }
        throw new RoutNotFoundException($name, $params);
    }
}