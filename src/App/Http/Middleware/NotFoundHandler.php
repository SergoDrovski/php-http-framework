<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandler
{
   public function __invoke(ServerRequestInterface $request, callable $next = null): HtmlResponse
   {
       return new HtmlResponse("<h1>Not Found</h1>");
   }
}