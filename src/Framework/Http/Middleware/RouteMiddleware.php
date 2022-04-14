<?php

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Result;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteMiddleware
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttributes() as $attribute => $value){
                $request = $request->withAttribute($attribute, $value);
            }

            return $next($request->withAttribute(Result::class, $result));
        } catch (RequestNotMatchedException $exception){
            return $next($request);
        }
    }

}