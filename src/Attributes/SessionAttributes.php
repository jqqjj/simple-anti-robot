<?php

namespace Jqqjj\SimpleAntiRobot\Attributes;

use Jqqjj\SimpleAntiRobot\Attributes\AbstractAttributes;
use Jqqjj\SimpleAntiRobot\Attributes\AttributesInterface;

class SessionAttributes extends AbstractAttributes implements AttributesInterface
{
    protected $session_id;
    protected $remaining;
    protected $expired_time;
}