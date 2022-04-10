<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;

class ProfilerMiddleware
{
   public function __invoke(ServerRequestInterface $request, callable $next)
   {
       $start = microtime(true);

       $response = $next($request);

       $stop = microtime(true);

       return $response->withHeader("X-Time", $stop - $start);

   }
}