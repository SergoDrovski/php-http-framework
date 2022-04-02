<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RoutNotFoundException;
use Psr\Http\Message\ServerRequestInterface;


class AuraRouterAdapter implements RouterInterface
{
    private RouterContainer $aura;

    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }

    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        $route = $matcher->match($request);
        if ($route) {
            return new Result($route->name,$route->handler,$route->attributes);
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []) : string
    {
        $generator = $this->aura->getGenerator();
        try {
            return $generator->generate($name, $params);
        } catch (RouteNotFound $exception) {
            throw new RoutNotFoundException($name, $params, $exception);
        }
    }
}