<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandler
{
   public function __invoke(ServerRequestInterface $request, callable $next)
   {
       $response = new Response();
       return  $response->getBody()->write("<h1>Not Found</h1>");
   }
}