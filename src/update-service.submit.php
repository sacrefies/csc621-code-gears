<?php

require 'common.php';

$db = db();

$db->beginTransaction();
$db->prepare("
	UPDATE service
	SET est_completion = ?, summary = ?
	WHERE service_id = ?
")->execute([$_POST['est_completion'], $_POST['summary'], $_POST['service_id']]);

$db->prepare("
	DELETE FROM task WHERE service_id = ?
")->execute([$_POST['service_id']]);

$q = $db->prepare("
	INSERT INTO task (service_id, description, start_time, end_time, cost_usd)
	VALUES (?, ?, ?, ?, ?)
");

for ($i = 0; $i < count($_POST['description']); $i++) {
	$q->execute([
		$_POST['service_id'],
		$_POST['description'][$i],
		$_POST['start_time'][$i],
		$_POST['end_time'][$i],
		$_POST['cost'][$i],
	]);
}

$db->commit();

header('Location: services.php');

