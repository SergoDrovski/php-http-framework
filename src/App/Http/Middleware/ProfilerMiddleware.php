<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProfilerMiddleware
{
   public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
   {
       $start = microtime(true);

       $response = $next($request);

       $stop = microtime(true);

       return $response->withHeader("X-Time", $stop - $start);

   }
}