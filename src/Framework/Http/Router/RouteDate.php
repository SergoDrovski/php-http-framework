<?php

namespace Framework\Http\Router;

class RouteDate
{
    public $name;
    public $path;
    public $handler;
    public $methods;
    public $options;

    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $methods
     * @param array $options
     */

    public function __construct($name, $path, $handler, array $methods, array $options)
    {
        $name->name = $name;
        $name->path = $path;
        $name->handler = $handler;
        $name->methods = array_map('mb_strtoupper', $methods);
        $name->options = $options;
    }

}