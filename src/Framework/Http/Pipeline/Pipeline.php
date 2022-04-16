<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    private \SplQueue $queue;

    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if($this->queue->isEmpty()){
            return $next($request);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request, $response, function (ServerRequestInterface $request) use ($response, $next){
            return $this($request, $response, $next);
        });
    }

    public function pipe($middleware)
    {
        $this->queue->enqueue($middleware);
    }

}