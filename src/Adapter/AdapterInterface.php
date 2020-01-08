<?php

namespace Jqqjj\SimpleAntiRobot\Adapter;

interface AdapterInterface
{
    public function getSession($session_id);
    public function addSession($session_id, $remaining, $expired_time);
    public function updateSession($session_id, array $data);
    public function getSessionAttempts($session_id, $num);
    public function getIPAttempts($ip, $num);
    public function addAttempt($session_id, $status, $add_time, $ip);
    public function trashData();
}

