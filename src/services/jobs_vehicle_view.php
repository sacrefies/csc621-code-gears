<?php
/**
 * Copyright 2016 Saint Joseph's University
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
declare(strict_types = 1);
namespace gears\services;
require_once __DIR__ . '/JobsController.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/../accounts/ConventionVehicle.php';
use gears\appointments\Appointment;
use gears\accounts\Customer;
use gears\accounts\CustomerVehicle;
use gears\accounts\ConventionVehicle;
/**
 * @var string A string variable to set the page title.
 */
$title = 'In Service';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'In Service';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout
 */
$activeMenu = 2;
include __DIR__ . '/../header.php';
?>
<!-- main content starts here -->
<body>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">Vehicles In Service</div>
    <div class="panel-body">
        <?php
        	$jobs = JobsController::getAllActiveJobs();
	        if($jobs){
				echo "<table class='table table-striped'>";
		        echo "<tr>";
		        echo "<th>Job</th>";
		        echo "<th>Customer</th>";
		        echo "<th>Vehicle</th>";
		        echo "<th>Mileage</th>";
		        echo "<th></th>";
		        echo "</tr>";
		        foreach ($jobs as $job) {
		        	$key = $job->key;
		        	$custId = $job->appointment->customer->customerId;
            		$vehicle = CustomerVehicle::getInstanceFromKeys('customer_id = ?', [$custId]);
		        	$vehicleId = $vehicle->customer_vehicle_id;
		        	$cust = $vehicle->customer;
		        	$custFirst = $cust->firstName;
		            $custLast = $cust->lastName;
		            $custName = "" . $custFirst . " " . $custLast;
		            $conVehicle = $vehicle->conventionVehicle;
		            $year = $conVehicle->year;
		            $make = $conVehicle->make;
		            $model = $conVehicle->model;
		            $trim = $conVehicle->trim;
		            $yrMkMdl = ''.$year.' '.$make.' '.$model.' '.$trim;
		            $mileage = $vehicle->mileage;
		            echo "<tr>";
		            echo "<td><a href='/services/job_individual_view.php?jobId=".$job->jobId."'>" . $key . "</a></td>";
		            echo "<td><a href='/accounts/customer_individual_view.php?customerId=".$cust->customerId."'>" . $custName . "</a></td>";
		            echo "<td><a href='/accounts/customer_vehicle_individual_view.php?customer_vehicle_Id=".$vehicleId."'>" . $yrMkMdl . "</a></td>";
		            echo "<td>" . $mileage . "</td>";
		            echo "</tr>";
		        }
		        echo "</table>";
			}
			else{
				echo "<br><p>	No vehicles to display</p>";
			}
        ?>
    </div>
</div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<?php
	function printTable(array $vehicles){
		if($vehicles){
			echo "<table class='table table-striped'>";
	        echo "<tr>";
	        echo "<th>Customer</th>";
	        echo "<th>Customer</th>";
	        echo "<th>Vehicle</th>";
	        echo "<th>Mileage</th>";
	        echo "<th></th>";
	        echo "</tr>";
	        foreach ($vehicles as $vehicle) {
	        	$vehicleId = $vehicle->customer_vehicle_id;
	        	$cust = $vehicle->customer;
	        	$custFirst = $cust->firstName;
	            $custLast = $cust->lastName;
	            $custName = "" . $custFirst . " " . $custLast;
	            $conVehicle = $vehicle->conventionVehicle;
	            $year = $conVehicle->year;
	            $make = $conVehicle->make;
	            $model = $conVehicle->model;
	            $trim = $conVehicle->trim;
	            $yrMkMdl = ''.$year.' '.$make.' '.$model.' '.$trim;
	            $mileage = $vehicle->mileage;
	            echo "<tr>";
	            echo "<td><a href='/accounts/customer_individual_view.php?customerId=".$cust->customerId."'>" . $custName . "</a></td>";
	            echo "<td><a href='/accounts/customer_vehicle_individual_view.php?customer_vehicle_Id=".$vehicleId."'>" . $yrMkMdl . "</a></td>";
	            echo "<td>" . $mileage . "</td>";
	            echo "</tr>";
	        }
	        echo "</table>";
		}
		else{
			echo "<br><p>	No vehicles to display</p>";
		}
	}
?>