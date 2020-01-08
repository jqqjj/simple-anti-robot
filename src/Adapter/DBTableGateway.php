<?php

namespace Jqqjj\SimpleAntiRobot\Adapter;

use PDO;
use Jqqjj\SimpleAntiRobot\Adapter\AdapterInterface;

class DBTableGateway implements AdapterInterface
{
    protected $session_table = 'simple_anti_robot_sessions';
    protected $attempt_table = 'simple_anti_robot_attempts';
    protected $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function getSession($session_id)
    {
        if(empty($session_id)){
            return null;
        }
        
        $statement = $this->pdo->prepare("SELECT * FROM {$this->session_table} WHERE session_id=:session_id");
        $statement->bindParam('session_id', $session_id, PDO::PARAM_STR);
        
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function addSession($session_id, $remaining, $expired_time)
    {
        $datetime = date("Y-m-d H:i:s", $expired_time);
        $statement = $this->pdo->prepare("INSERT INTO {$this->session_table} (session_id,remaining,expired_time)"
        . " VALUES (:session_id,:remaining,:expired_time)");
        $statement->bindParam('session_id', $session_id, PDO::PARAM_STR);
        $statement->bindParam('remaining', $remaining, PDO::PARAM_INT);
        $statement->bindParam('expired_time', $datetime, PDO::PARAM_STR);
        
        $statement->execute();
        if($statement->rowCount()){
            return $session_id;
        }else{
            return null;
        }
    }
    
    public function updateSession($session_id,array $data)
    {
        $where = array('session_id'=>$session_id);
        $where_keys = array();
        foreach (array_keys($where) AS $value)
        {
            $where_keys[] = "`$value`=?";
        }
        $params_keys = array();
        foreach (array_keys($data) AS $val)
        {
            $params_keys[] = "`{$val}`=?";
        }
        $sql = "UPDATE {$this->session_table} SET ". implode(',', $params_keys) . " WHERE ".  implode(' AND ', $where_keys);
        
        $bind_values = array_merge(array_values($data), array_values($where));
        
        $statement = $this->pdo->prepare($sql);
        for ($index = 0; $index < count($bind_values); ++$index) {
            if (is_string($bind_values[$index])) {
                $statement->bindParam($index + 1, $bind_values[$index], PDO::PARAM_STR);
            }
            elseif (is_bool($bind_values[$index])) {
                $statement->bindParam($index + 1, $bind_values[$index], PDO::PARAM_BOOL);
            }
            elseif (is_int($bind_values[$index])) {
                $statement->bindParam($index + 1, $bind_values[$index], PDO::PARAM_INT);
            }
            else {
                $statement->bindParam($index + 1, $bind_values[$index], PDO::PARAM_NULL);
            }
        }
        return $statement->execute();
    }
    
    public function getSessionAttempts($session_id,$num)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->attempt_table} WHERE session_id=:session_id ORDER BY add_time DESC LIMIT {$num}");
        $statement->bindParam('session_id', $session_id, PDO::PARAM_STR);
        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getIPAttempts($ip,$num)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->attempt_table} WHERE ip=:ip ORDER BY add_time DESC LIMIT {$num}");
        $statement->bindParam('ip', $ip, PDO::PARAM_STR);
        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addAttempt($session_id,$status,$add_time,$ip)
    {
        $datetime = date("Y-m-d H:i:s",$add_time);
        $statement = $this->pdo->prepare("INSERT INTO {$this->attempt_table} (session_id,status,add_time,ip)"
        . " VALUES (:session_id,:status,:add_time,:ip)");
        $statement->bindParam('session_id', $session_id, PDO::PARAM_STR);
        $statement->bindParam('status', $status, PDO::PARAM_INT);
        $statement->bindParam('add_time', $datetime, PDO::PARAM_STR);
        $statement->bindParam('ip', $ip, PDO::PARAM_STR);
        
        $statement->execute();
        if($statement->rowCount()){
            return $session_id;
        }else{
            return null;
        }
    }
    
    public function trashData()
    {
        $this->pdo->query("DELETE FROM {$this->session_table} WHERE expired_time<'".date("Y-m-d H:i:s")."'");
        $this->pdo->query("DELETE FROM {$this->attempt_table} WHERE session_id NOT IN (SELECT session_id FROM {$this->session_table})");
    }
	
	public function setSessionTable($table)
	{
		$this->session_table = $table;
	}
	
	public function setAttemptTable($table)
	{
		$this->attempt_table = $table;
	}
}