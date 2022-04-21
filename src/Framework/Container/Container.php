<?php

namespace Framework\Container;

use phpDocumentor\Reflection\Types\Callable_;

class Container
{
    private $definitions = [];
    private $result = [];

    public function get($id)
    {
        if(array_key_exists($id, $this->result)){
            return   $this->result[$id];
        }

        if(!array_key_exists($id, $this->definitions)){
            throw new ServiceNotFoundException("Undefined parameter {$id}");
        }
        if (is_callable($result = $this->definitions[$id])){
            return  $this->result[$id] = $result();
        }
        return $this->result[$id] = $result;
    }

    public function set($id, $value)
    {
        $this->definitions[$id] = $value;
    }

}