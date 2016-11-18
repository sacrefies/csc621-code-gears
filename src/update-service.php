<?php

require 'common.php';

$in_service_id = $_GET['in_service_id'];

$q = $db->prepare("
	SELECT customer.name as customer, est_completion,
	       concat_ws(' ', vehicle.year, vehicle.make, vehicle.model) as vehicle,
	       mechanic.name as mechanic,
	       garage.name as garage
	FROM in_service
	JOIN customer USING (customer_id)
	JOIN vehicle USING (vehicle_id)
	JOIN employee AS mechanic ON mechanic = mechanic.employee_id
	JOIN garage USING (garage_id)
	WHERE in_service_id = ?
");
$q->execute([$in_service_id]);

$row = $q->fetch();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Servicing</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<h1>Update service</h1>
<form method="POST" action="update-service.submit.php">
<input type="hidden" name="in_service_id" value="<?= $in_service_id ?>">
<p>Customer: <?= $row['customer'] ?></p>
<p>Vehicle: <?= $row['vehicle'] ?></p>
<p>Estimated completion: <input type="text" name="est_completion" value="<?= $row['est_completion'] ?>"></p>
<input type="submit" value="Update">
</form>
</body>
</html>
