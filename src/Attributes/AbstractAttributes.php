<?php

namespace Jqqjj\SimpleAntiRobot\Attributes;

use Jqqjj\SimpleAntiRobot\Exception\RuntimeException;

abstract class AbstractAttributes
{
    public function __get($name)
    {
        if(!property_exists($this,$name)){
            throw new RuntimeException("Call to an undefined property[$name] of ".__CLASS__.".");
        }
        return $this->$name;
    }
    
    public function __set($name, $value)
    {
        if(!property_exists($this,$name)){
            throw new RuntimeException("Call to an undefined property[$name] of ".__CLASS__.".");
        }
        $this->$name = $value;
    }
    
    public function __isset($name)
    {
        return property_exists($this, $name);
    }
    
    public function toArray()
    {
        return get_object_vars($this);
    }
}