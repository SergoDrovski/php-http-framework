<?php

namespace Framework\Http\Router;

class Route
{
    public $name;
    public $pattern;
    public $handler;
    public $methods;
    public $token;

    public function  __construct($name, $pattern, $handler, array $methods, array $token= [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->token = $token;
    }
}