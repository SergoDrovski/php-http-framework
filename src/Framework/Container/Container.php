<?php

namespace Framework\Container;


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

        $result = $this->definitions[$id];

        if (is_callable($result)){
            $this->result[$id] = $result();
            return $this->result[$id];
        }
        $this->result[$id] = $result;
        return $this->result[$id];
    }

    public function set($id, $value)
    {
        if(array_key_exists($id, $this->result)){
            unset($this->result[$id]);
        }
        $this->definitions[$id] = $value;
    }

}