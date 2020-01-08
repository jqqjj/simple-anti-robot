<?php

namespace Jqqjj\SimpleAntiRobot;

use Jqqjj\SimpleAntiRobot\Attributes\AttemptAttributes;

class Attempt
{
    protected $attributes;
    
    public function __construct(AttemptAttributes $attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function __get($name)
    {
        return $this->attributes->$name;
    }
    
    public function __set($name, $value)
    {
        $this->attributes->$name = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->attributes->$name);
    }
}