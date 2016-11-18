<?php

ini_set('display_errors', 1);

$dsn = "mysql:host=localhost;dbname=repaircar;charset=utf8";
$user = "root";
$pass = "";
$db = new PDO($dsn, $user, $pass, [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
]);

