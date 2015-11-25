<?php


	$db = infra_db(true);
	$ans = array(
		'title' => 'Проверка соединения с базой данных',
	);
	$conf = infra_config();
	if (!$conf['infra']['db']) {
		$ans['class'] = 'bg-warning';

		return infra_ret($ans, 'База данных не используется config.db.db:false');
	}
	if (!$db) {
		return infra_err($ans, 'Нет соединения с базой данных');
	}

	return infra_ret($ans, 'Есть соединение с базой данных');
