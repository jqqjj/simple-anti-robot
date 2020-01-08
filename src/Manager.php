<?php

namespace Jqqjj\SimpleAntiRobot;

use Jqqjj\SimpleAntiRobot\Adapter\AdapterInterface;
use Jqqjj\SimpleAntiRobot\Session;

class Manager
{
    public static $cookie_key = "_simpleAntiRobot";
    public $lifttime = 2592000;//3600 * 24 * 30
    public $path = '/';
    public $domain;
    public $secure = false;
    public $httponly = true;
    
    private $session;
    
    public function __construct(AdapterInterface $adapter, $session_id=null)
    {
		if(empty($session_id)){
			$session_id = !empty($_COOKIE) && !empty($_COOKIE[static::$cookie_key]) ? $_COOKIE[static::$cookie_key] : "";
		}
        $this->session = new Session($session_id, $adapter);
        $this->session->expired_time = date("Y-m-d H:i:s", time() + $this->lifttime);
    }
    
    public function attemptFailure()
    {
        $this->session->addAttempt(0);
    }
    
    public function attemptSuccess()
    {
        $this->session->addAttempt(1);
    }

    public function isHuman()
    {
        return $this->session->getRemaining() > 0;
    }
    
    public function isRobot()
    {
        return $this->session->getRemaining() <= 0;
    }
    
    public function trashData()
    {
        return $this->session->trashData();
    }
	
	public function getCookieKey()
	{
		return static::$cookie_key;
	}
	
	public function getSessionId()
	{
		return $this->session->getId();
	}
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getOutputCookieString()
    {
        $str = static::$cookie_key . "={$this->session->getId()}";
        $str .= ";expires=".gmdate('D, d-M-Y H:i:s T', $this->lifttime + time());
        $str .= ";Max-Age={$this->lifttime}";
        $str .= ";path={$this->path}";
        if(!empty($this->domain)){
            $str .= ";domain={$this->domain}";
        }
        if($this->secure){
            $str .= ";secure";
        }
        if($this->httponly){
            $str .= ";HttpOnly";
        }
        return $str;
    }
    
    public function outputCookie()
    {
        setcookie(static::$cookie_key, $this->session->getId(), $this->lifttime + time(), $this->path, $this->domain, $this->secure, $this->httponly);
    }
}