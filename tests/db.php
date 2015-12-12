<?php
namespace infrajs\db;

use infrajs\view\View;
use infrajs\ans\Ans;
use infrajs\path\Path;
use infrajs\infra\Infra;

if (!is_file('vendor/autoload.php')) {
    chdir('../../../../');
    require_once('vendor/autoload.php');
}
$db = Db::pdo(true);
$ans = array(
	'title' => 'Проверка соединения с базой данных',
);
$conf = Db::$conf;
if (!$conf['db']) {
	$ans['class'] = 'bg-warning';

	return Ans::ret($ans, 'База данных не используется config.db.db:false');
}
if (!$db) {
	return Ans::err($ans, 'Нет соединения с базой данных');
}

return Ans::ret($ans, 'Есть соединение с базой данных');
