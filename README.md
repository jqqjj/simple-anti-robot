# Simple Anti Robot

Simple APIs for protecting your application far away from robots.

### Installation

Run the following to include this via Composer
```php
composer require jqqjj/simple-anti-robot
```
### Usage

Create mysql tables(when using  DBTableGateway adapter)
```sql
CREATE TABLE `simple_anti_robot_sessions` (
  `session_id` char(32) NOT NULL,
  `remaining` int(10) unsigned DEFAULT NULL,
  `expired_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `simple_anti_robot_attempts` (
  `session_id` char(32) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `add_time` timestamp NULL DEFAULT NULL,
  `ip` char(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
Code:
```php
use Jqqjj\SimpleAntiRobot\Manager;
use Jqqjj\SimpleAntiRobot\Adapter\DBTableGateway;

//Create a PDO object
$pdo = new \PDO('mysql:host=localhost;port=3306;dbname=yourdbname','dbuser','dbpasswd');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

//Create an adapter(Only MySQL adapter is supported Currently)
$adapter = new DBTableGateway($pdo);
$object = new Manager($adapter);

//Run the codes when the client do something incorrectly
$object->attemptFailure();

//Run the codes when the client do things as you want to be
$object->attemptSuccess();

//Check it, it will return false when the client is a robot
if($object->isHuman()){
	//human
}
if($object->isRobot()){
	//robot
}

//Finally, output the cookie
$object->outputCookie();
//OR
header("Set-Cookie:".$object->getOutputCookieString());
```

### License
This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
