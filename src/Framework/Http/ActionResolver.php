<?php

namespace Framework\Http;


class ActionResolver
{

    public function resolve($handler)
    {
        if(is_array($handler)){
            if (count($handler) == 2) {
                $method = $handler[1];
                $class = new ($handler[0]);
                return [$class,$method];
            }
            return new ($handler[0]);
        }

        return $handler;
    }

}