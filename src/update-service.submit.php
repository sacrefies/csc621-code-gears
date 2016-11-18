<?php

require 'common.php';

$q = $db->prepare("
	UPDATE in_service
	SET est_completion = ?
	WHERE in_service_id = ?
");
$q->execute([$_POST['est_completion'], $_POST['in_service_id']]);

header('Location: servicing.php');

