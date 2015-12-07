<?php


	$db = infra_db(true);
	$ans = array(
		'title' => 'Проверка соединения с базой данных',
	);
	$conf = Infra::config();
	if (!$conf['infra']['db']) {
		$ans['class'] = 'bg-warning';

		return Ans::ret($ans, 'База данных не используется config.db.db:false');
	}
	if (!$db) {
		return Ans::err($ans, 'Нет соединения с базой данных');
	}

	return Ans::ret($ans, 'Есть соединение с базой данных');
