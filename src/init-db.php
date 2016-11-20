<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
	require 'common.php';

	$db = new PDO("mysql:host=$db_host", $db_user, $db_pass, [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	]);

	$db->beginTransaction();

	$db->exec("
		DROP DATABASE IF EXISTS `$db_name`;
		CREATE DATABASE `$db_name`;
		USE `$db_name`;
	");

	foreach (explode(';', file_get_contents('database/db_init.sql')) as $query) {
		if (trim($query) !== '') {
			$db->exec($query);
		}
	}

	$db->commit();

	$now = date('c');
	echo "Successfully reinitialized database $db_name at $now";
}
?>
<form method="POST">
<input type="submit" value="Re-initialize database from database/db_init.sql">
</form>

