<?php declare(strict_types=1);

namespace App\Http\Controllers;


use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class NewsController
{
    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $id = (int) $request->getAttribute('id');
        return new HtmlResponse("<h1>id = {$id}</h1>");
    }

}
