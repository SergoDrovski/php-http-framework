<?php

namespace Framework\Http\Pipeline\Exception;


class UnknownMiddlewareException extends \LogicException
{
    private $handler;

    public function __construct($handler)
    {
        parent::__construct("Unknown middleware type");
        $this->handler = $handler;

    }

    /**
     * @return mixed
     */
    public function getHandler(): mixed
    {
        return $this->handler;
    }

}