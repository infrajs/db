<?php

namespace infrajs\db;

use infrajs\access\Access;
use infrajs\router\Router;
use PDO;

class Db
{
	public static $conf = array(
		"db" => false,
		"user" => "some",
		"host" => "host",
		"database" => "some",
		"password" => "pass",
		"port" => "3306"
	);
	public static $once = array();
	public static function &cpdo($debug = null)
	{
		$key = 'pdo:';
		if (isset(Db::$once[$key])) return Db::$once[$key];


		$conf = Db::$conf;
		if (is_null($debug)) $debug = Access::debug();

		$ans = false;

		if (!$conf['db']) {
			//if($debug)die('Нет конфига для соединения с базой данных. Нужно добавить запись mysql: '.Load::json_encode($conf['/mysql']));
			return Db::$once[$key] = $ans;
		}
		if (!$conf['user']) {
			//if($debug)die('Не указан пользователь для соединения с базой данных');
			return Db::$once[$key] = $ans;
		}
		if (!class_exists('PDO')) {
			return Db::$once[$key] = $ans;
		}

		try {
			$db = new PDO('mysql:host=' . $conf['host'] . ';dbname=' . $conf['database'] . ';port=' . $conf['port'], $conf['user'], $conf['password']);
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
		Db::$once[$key] = &$db;
		return $db;
	}
	public static function &pdo($debug = null)
	{
		header('Cache-Control: no-store'); //no-store ключевое слово используемое в infra_cache
		$key = 'pdo:';
		if (isset(Db::$once[$key])) return Db::$once[$key];
		
		$conf = Db::$conf;
		if (is_null($debug)) $debug = Access::debug();

		$ans = false;

		if (!$conf['db']) {
			//if($debug)die('Нет конфига для соединения с базой данных. Нужно добавить запись mysql: '.Load::json_encode($conf['/mysql']));
			return  Db::$once[$key] = $ans;
		}
		if (!$conf['user']) {
			//if($debug)die('Не указан пользователь для соединения с базой данных');
			return  Db::$once[$key] = $ans;
		}
		if (!class_exists('PDO')) {
			return  Db::$once[$key] = $ans;
		}

		try {
			$db = new PDO('mysql:host=' . $conf['host'] . ';dbname=' . $conf['database'] . ';port=' . $conf['port'], $conf['user'], $conf['password']);
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
		Db::$once[$key] = &$db;
		return $db;
	}
	public static function stmt($sql)
	{
		$key = 'sql:'.$sql;
		if (isset(Db::$once[$key])) return Db::$once[$key];

		$db = Db::pdo();

		return Db::$once[$key] = $db->prepare($sql);
		
	}
	public static function cstmt($sql)
	{
		$key = 'sql:'.$sql;
		if (isset(Db::$once[$key])) return Db::$once[$key];
		
		$db = Db::cpdo();

		return Db::$once[$key] = $db->prepare($sql);
	}
	public static function fetch($sql, $args = [])
	{
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		return $stmt->fetch();
	}
	public static function col($sql, $args = [])
	{
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		return $stmt->fetchColumn();
	}
	public static function lastId($sql, $args = [])
	{
		$db = &Db::cpdo();
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		return $db->lastInsertId();
	}
	public static function fetchto($sql, $name, $args = [])
	{ //Колонки в аргументах $func
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		$list = array();
		while ($row = $stmt->fetch()) $list[$row[$name]] = $row;
		return $list;
	}
	public static function all($sql, $args = [])
	{ //Колонки в аргументах $func
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		return $stmt->fetchAll();
	}
	public static function exec($sql, $args = [])
	{
		$stmt = Db::cstmt($sql);
		$stmt->execute($args);
		return $stmt->rowCount();
	}
}
