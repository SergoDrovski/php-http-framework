<?php

namespace Framework\Http\Pipeline;

use Framework\Http\Pipeline\Exception\UnknownMiddlewareException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;


class MiddlewareResolver
{
    public function resolve($handler): callable
    {
        if(\is_array($handler)){
            return $this->createPipe($handler);
        }

        if (\is_string($handler)){
            return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
                $middleware = $this->resolve(new $handler);
                return $middleware($request, $response, $next);
            };
        }
        if ($handler instanceof MiddlewareInterface){
            return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
                return $handler->process($request, new RequestHandlerWrapper($next));
            };
        }
        if (\is_object($handler)){
            $reflection = new \ReflectionObject($handler);
            if($reflection->hasMethod('__invoke')){
                $method = $reflection->getMethod('__invoke');
                $params = $method->getParameters();
                if (count($params) === 2 && $this->declaresCallable($params[1])) {
                    return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler){
                        return $handler($request, $next);
                    };
                }
                return $handler;
            }
        }

        throw new UnknownMiddlewareException($handler);
    }

    private function createPipe(array $handlers): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }
        return $pipeline;
    }

    public function declaresCallable(ReflectionParameter $reflectionParameter): bool
    {
        $reflectionType = $reflectionParameter->getType();

        if (!$reflectionType) return false;

        $types = $reflectionType instanceof ReflectionUnionType
            ? $reflectionType->getTypes()
            : [$reflectionType];

        return in_array('callable', array_map(fn(ReflectionNamedType $t) => $t->getName(), $types));
    }

}