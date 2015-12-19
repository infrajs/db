<?php
namespace infrajs\db;
use infrajs\infra\Infra;

$conf=&Config::get('ascroll');
Db::$conf=array_merge(Db::$conf, $conf);
$conf=Db::$conf;