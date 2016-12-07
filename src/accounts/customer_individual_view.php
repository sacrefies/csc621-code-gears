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
namespace gears\accounts;

require_once __DIR__ . '/AccountController.php';
require_once __DIR__ . '/Customer.php';
require_once __DIR__ . '/CustomerVehicle.php';
require_once __DIR__ . '/ConventionVehicle.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../models/State.php';

use gears\appointments\Appointment;
use gears\models\State;

/**
 * @var string A string variable to set the page title.
 */
$title = 'Customer';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Profiles';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 5;

include __DIR__ . '/../header.php';

$custId = (isset($_GET['customerId']) && $_GET['customerId']) ? (int)$_GET['customerId'] : -1;
// get our beloved customer object
$customer = AccountController::getCustomerById($custId);
?>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">
        Customer <?php echo $customer ? AccountController::getCustomerFullName($customer) : 'Unknown'; ?></div>
    <div class="panel-body">
        <?php if ($customer): ?>
            <form class="form-horizontal" action="customer_edit.php" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="firstName">First Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->firstName; ?>" type="text" class="form-control disabled"
                               id="firstName" placeholder="First Name" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->lastName; ?>" type="text" class="form-control disabled"
                               id="lastName" placeholder="Last Name" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Phone Number:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->phoneNumber; ?>" type="tel" class="form-control disabled"
                               id="phone" placeholder="Phone Number" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="zip">Zip Code:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->zip; ?>" type="text" class="form-control disabled" id="zip"
                               placeholder="Zip Code" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Edit</button>
                        <input type="hidden" name="customerId" id="customerId" value="<?php echo $custId; ?>"/>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Customer not found
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Customer Vehicles
        <div class="pull-right">
            <?php if ($customer): ?>
                <form method="POST" action="vehicle_edit.php" name="vehicle" id="frmVehicle">
                    <input type="hidden" value="<?php echo $custId; ?>" name="custId"/>
                    <button type="submit" class="btn btn-default btn-sm" name="addVehicleSubmit"
                            value="addVehicle" form="frmVehicle">
                        Add Vehicle <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <table class="table">
        <?php
            $vehicles = CustomerVehicle::getList('customer_id = ?', [$custId]);
            foreach($vehicles as $vehicle){
                $conVehicle = $vehicle->conventionVehicle;
                $vehicleId = $vehicle->customer_vehicle_id;
                $year = $conVehicle->year;
                $make = $conVehicle->make;
                $model = $conVehicle->model;
                $trim = $conVehicle->trim;
                $yrMkMdl = ''.$year.' '.$make.' '.$model.' '.$trim;
                echo "<tr>";
                echo "<td><a href='/accounts/customer_vehicle_individual_view.php?customer_vehicle_Id=".$vehicleId."'>" . $yrMkMdl . "</a></td>";
                echo "<td><button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteMsg' 
                            data-yourParameter='$vehicleId'>Delete <span class='glyphicon glyphicon-remove'>
                        </button></td>";
                echo "</tr>";
            }
        ?>
        </table>
        <button id="output" style='visibility:hidden;' class='btn btn-info btn-sm' type="hidden" data-toggle='modal' data-target='#errorMsg'></button>
    </div>
</div>
<div id="deleteMsg" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title edit-content">Delete Vehicle</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:deleteVehicle()" role="form">
                    <div class="form-group">
                        <p> Are you sure you want to delete this vehicle? </p>
                        <input name="vehicleId" id="vehicleId" type="number" class="form-control" style="visibility:hidden;"/>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-danger" type="submit" name="submit" value="Yes, Delete"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No, Close</button>
            </div>
        </div>
    </div>
</div>
<div id="errorMsg" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title edit-content">Error</h4>
            </div>
            <div class="modal-body">
                <p> Cannot delete this vehicle. It is being serviced </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Customer Appointmnets</div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th>Subject</th>
                <th>Description</th>
                <th>Date Made</th>
                <th>Status</th>
        <?php
            $appts = Appointment::getList('customer_id = ?', [$custId]);
            foreach($appts as $appt){
                $id = $appt->appId;
                $subject = $appt->subject;
                $desc = $appt->desc;
                $endTime = $appt->endTime->format('m/d/Y h:i A');
                $state = State::getName($appt->getState());
                $cust = Customer::getInstance($appt->customer->customerId);
                echo "<tr>";
                echo "<td><a href='/appointments/appointment_detailed.php?apptId=$id'> $subject </a></td>";
                echo "<td>$desc</td>";
                echo "<td>$endTime</td>";
                echo "<td>$state</td>";
                echo "</tr>";
            }
        ?>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#deleteMsg').on('shown.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.yourparameter;
            document.getElementById("vehicleId").value = id;
        });
    });

    function deleteVehicle() {
        var id = document.getElementById("vehicleId").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
                console.log(data);
                if(data === '-1'){
                    document.getElementById("output").click();
                }
                else{
                    location.reload();
                }
            }
        }//end onreadystatechange
        var link = "deleteVehicle.php?id=" + id;
        xhttp.open("GET", link, true);
        xhttp.send();
    }
</script>
<?php include __DIR__ . '/../footer.php'; ?>
