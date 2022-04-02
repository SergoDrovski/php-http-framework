<?php

namespace Framework\Http\Router\Exception;


class RoutNotFoundException extends \LogicException
{
    private mixed $name;
    private array $params;

    public function __construct($name, array $params, $previous = null)
    {
        parent::__construct("RegexpRoute {$name} not found", $previous);
        $this->name = $name;
        $this->params = $params;

    }

    /**
     * @return mixed
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}