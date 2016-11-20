<?php

require 'common.php';
$db = db();

$q = $db->query("
	SELECT service_id as id,
	       customer.name as customer, est_completion,
	       vehicle.year, vehicle.make, vehicle.model,
	       mechanic.name as mechanic, garage.name as garage,
	       service.description as service
	FROM service
	JOIN customer USING (customer_id)
	JOIN vehicle USING (vehicle_id)
	JOIN employee AS mechanic ON mechanic = mechanic.employee_id
	JOIN garage USING (garage_id)
");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Servicing</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
	<h1>In Service</h1>
	<form>
	<p>
		<button formmethod="get" formaction="update-service.php">Update</button>
		<button formmethod="post" formaction="move-to-invoicing.php">Move to invoicing</button>
	</p>
	<table>
	<thead>
	<tr>
	<th></th>
	<th>Customer</th>
	<th>Vehicle</th>
	<th>Est Completion</th>
	<th>Mechanic</th>
	<th>Garage</th>
	<th>Service</th>
	</tr>
	</thead>
	<tbody>
	<?php while ($row = $q->fetch()) { ?>
		<tr>
		<td><input type="radio" name="in_service_id" value="<?= $row['id'] ?>"></td>
		<td><?= $row['customer']; ?></td>
		<td><?= $row['year'] . ' ' . $row['make'] . ' ' . $row['model'] ?></td>
		<td><?= $row['est_completion']; ?></td>
		<td><?= $row['mechanic']; ?></td>
		<td><?= $row['garage']; ?></td>
		<td><?= $row['service']; ?></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</body>
</html>


