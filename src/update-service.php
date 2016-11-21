<?php

require 'common.php';

$service_id = $_GET['service_id'];

$serviceq = db()->prepare("
	SELECT customer.name as customer, service.est_completion, service.summary,
	       concat_ws(' ', vehicle.year, vehicle.make, vehicle.model) as vehicle,
	       mechanic.name as mechanic,
	       garage.name as garage
	FROM service
	JOIN customer USING (customer_id)
	JOIN vehicle USING (vehicle_id)
	JOIN employee AS mechanic ON mechanic = mechanic.employee_id
	JOIN garage USING (garage_id)
	WHERE service_id = ?
");
$serviceq->execute([$service_id]);
$service = $serviceq->fetch();

$taskq = db()->prepare("
	SELECT task_id, description, start_time, end_time, cost_usd
	FROM task
	WHERE service_id = ?
");
$taskq->execute([$service_id]);

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
<input type="hidden" name="service_id" value="<?= $service_id ?>">
<p>Customer: <?= $service['customer'] ?></p>
<p>Vehicle: <?= $service['vehicle'] ?></p>
<p>Description: <input type="text" name="summary" value="<?= $service['summary'] ?>"></p>
<p>Estimated completion: <input type="text" name="est_completion" value="<?= $service['est_completion'] ?>"></p>
<h2>Tasks:</h2>
<table>
<thead><tr><th>Description</th><th>Start time</th><th>End time</th><th>Cost, $</th></tr></thead>
<tbody id="tasks-tbody">
<?php while ($task = $taskq->fetch()): ?>
	<tr>
	<td><input type="text" name="description[]" value="<?= $task['description']; ?>"></td>
	<td><input type="text" name="start_time[]" value="<?= $task['start_time']; ?>"></td>
	<td><input type="text" name="end_time[]" value="<?= $task['end_time']; ?>"></td>
	<td><input type="text" name="cost[]" value="<?= $task['cost_usd']; ?>"></td>
	<td><button type="button" onclick="delete_row(this);">Delete</button></td>
	</tr>
<?php endwhile; ?>
</tbody>
</table>
<p><button type="button" onclick="add_task();">Add task</button></p>
<p><input type="submit" value="Update"> <button type="button" onclick="cancel();">Cancel</button></p>
</form>
<script>
function add_task() {
	var task_table = document.getElementById('tasks-tbody');

	var tr = document.createElement('tr');
	var names = ["description", "start_time", "end_time", "cost"];
	for (var i = 0; i < names.length; i++) {
		tr.innerHTML += '<td><input type="text" name="' + names[i] + '[]"></td>';
	}
	tr.innerHTML += '<td><button type="button" onclick="delete_row(this);">Delete</button></td>';
	task_table.append(tr);
	return false;
}

function cancel() {
	window.location = 'services.php';
}

function delete_row(button) {
	var tr = button.parentElement.parentElement;
	tr.parentElement.removeChild(tr);
}
</script>
</body>
</html>
