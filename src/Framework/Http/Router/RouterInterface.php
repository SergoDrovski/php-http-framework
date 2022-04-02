<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RoutNotFoundException;
use Psr\Http\Message\ServerRequestInterface;


interface RouterInterface
{
    /**
     * @param  ServerRequestInterface $request
     * @throws RequestNotMatchedException
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result;


    /**
     * @param  $name
     * @param  array $params
     * @throws RoutNotFoundException
     * @return string
     */
    public function generate($name, array $params = []) : string;

}