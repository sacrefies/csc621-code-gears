<?php

ini_set('display_errors', 1);

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'repaircar';

function db() {
	global $db_host, $db_user, $db_pass, $db_name;
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	return new PDO($dsn, $db_user, $db_pass, [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	]);
}

