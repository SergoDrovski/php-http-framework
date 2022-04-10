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

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {

        if($this->queue->isEmpty()){
            return $next($request);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request,  function (ServerRequestInterface $request) use ($next){
            return $this($request, $next);
        });
    }

    public function pipe($middleware)
    {
        $this->queue->enqueue($middleware);
    }

}