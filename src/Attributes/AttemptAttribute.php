<?php

namespace Jqqjj\SimpleAntiRobot\Attributes;

use Jqqjj\SimpleAntiRobot\Attributes\AbstractAttributes;
use Jqqjj\SimpleAntiRobot\Attributes\AttributesInterface;

class AttemptAttributes extends AbstractAttributes implements AttributesInterface
{
    protected $session_id;
    protected $status;
    protected $add_time;
    protected $ip;
}