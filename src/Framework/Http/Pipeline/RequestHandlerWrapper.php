<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerWrapper implements RequestHandlerInterface
{

    private $callable;

    public function __construct($next)
    {
        $this->callable = $next;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new ($this->callable)($request);
    }

}