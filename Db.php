<?php
namespace infrajs\db;

use infrajs\once\Once;
use infrajs\access\Access;

class Db {
	public static $conf=array(
		"db"=>false,
		"user"=>"some",
		"host"=>"host",
		"database"=>"some",
		"password"=>"pass",
		"port"=>"3306"
	);
	public static function &pdo($debug = false)
	{
		header('Cache-Control: no-store'); //no-store ключевое слово используемое в infra_cache

		return Once::exec('Db::pdo', function &($debug) {
			$conf = Db::$conf;
			if (!$debug) {
				$debug = Access::debug();
			}
			$ans = false;

			if (!$conf['db']) {
				//if($debug)die('Нет конфига для соединения с базой данных. Нужно добавить запись mysql: '.Load::json_encode($conf['/mysql']));
				return $ans;
			}

			if (!$conf['user']) {
				//if($debug)die('Не указан пользователь для соединения с базой данных');
				return $ans;
			}
			if (!class_exists('PDO')) {
				return $ans;
			}
			try {
				@$db = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['database'].';port='.$conf['port'], $conf['user'], $conf['password']);
				$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				if ($debug) {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} else {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				}
					/*array(
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => true,
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING 
				)*/
				$db->exec('SET CHARACTER SET utf8');
			} catch (PDOException $e) {
				//if($debug)throw $e;
				$db = false;
				/*if(!$debug){
					print "Error!: " . Path::toutf($e->getMessage()) . "<br/>";
					die();
				}*/
			}

			return $db;
		}, array($debug));
	}
	public static function stmt($sql)
	{
		return Once::exec('Db::stmt', function ($sql) {
			$db = Db::pdo();

			return $db->prepare($sql);
		}, array($sql));
	}
}