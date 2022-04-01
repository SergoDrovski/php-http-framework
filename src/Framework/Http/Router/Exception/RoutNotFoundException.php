<?php

namespace Framework\Http\Router\Exception;


class RoutNotFoundException extends \LogicException
{
    private $name;
    private $params;

    public function __construct($name, array $params)
    {
        parent::__construct("RegexpRoute {$name} not found");
        $this->name = $name;
        $this->params = $params;

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}