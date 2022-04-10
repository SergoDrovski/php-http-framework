<?php declare(strict_types=1);

namespace App\Http\Controllers;


use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;

class NewsController
{
    public function __invoke(ServerRequestInterface $request)
    {

        return new HtmlResponse("<h1>Hello</h1>");
    }

}
