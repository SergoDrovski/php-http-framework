<?php declare(strict_types=1);

namespace App\Http\Controllers;


use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    public function index(ServerRequestInterface $request): Response
    {
        $response = new Response();
        $response->getBody()->write("<h1>Hello</h1>");

        return $response->withHeader('Content-Type', 'text/html;')->withHeader("X-Show-Something", "something");
    }
}
