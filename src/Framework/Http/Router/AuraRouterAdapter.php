<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\UknoweMiddlewareExcaption;
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
            throw new UknoweMiddlewareExcaption($name, $params, $exception);
        }
    }

    /**
     * @throws \Aura\Router\Exception\RouteAlreadyExists
     * @throws \Aura\Router\Exception\ImmutableProperty
     */
    public function addRoute(RouteDate $date) : void
    {
        $map = $this->aura->getMap();
        $rout = new Route();

        $rout->name($date->name);
        $rout->path($date->path);
        $rout->handler($date->handler);

        if($date->methods){
            $rout->allows($date->methods);
        }

        foreach ($date->options as $key => $value){
            switch ($key) {
                case 'tokens':
                    $rout->tokens($value);
                    break;
                case 'wildcard':
                    $rout->wildcard($value);
                    break;
                case 'defaults':
                    $rout->defaults($value);
                    break;
            }
        }

        $map->addRoute($rout);
    }
}