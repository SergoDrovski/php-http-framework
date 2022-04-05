<?php declare(strict_types=1);

namespace App\Http\Controllers;


use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class BlogController
{
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        $id = $request->getAttribute('id');
        return new JsonResponse(['id'=> $id, 'title' => 'something text']);
    }

}
