<?php
namespace infrajs\db;
use infrajs\infra\Infra;

$conf=&Infra::config('ascroll');
Db::$conf=array_merge(Db::$conf, $conf);
$conf=Db::$conf;