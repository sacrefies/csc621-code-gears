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
require_once __DIR__ . '/ConventionVehicle.php';

/**
 * @var string A string variable to set the page title.
 */
$title = 'Profiles';
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

$error = '';
$custId = (isset($_POST['custId']) && !empty($_POST['custId'])) ? (int)$_POST['custId'] : -1;
$action = (isset($_POST['action']) && !empty($_POST['action'])) ? $_POST['action'] : null;

// get our beloved customer object
$customer = AccountController::getCustomerById($custId);
if ($action && $action === 'addVehicle') {
    // save and redirect
    AccountController::createNewVehicle($custId, $_POST['car'], $_POST['mileage'], $_POST['vin']);
    AccountController::redirectTo("customer_individual_view.php?customerId=$custId");
} ?>
<div class="panel panel-default">
    <div class="panel-heading">Add Vehicle</div>
    <div class="panel-body">
        <?php if ($customer): ?>
            <form name="frmVehicle" id="frmVehicle" class="form-horizontal"
                  action="<?php echo AccountController::getSelfScript(); ?>" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="car">Vehicle:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="car" id="car">
                            <?php
                            $vehicles = ConventionVehicle::getAll();
                            foreach($vehicles as $vehicle){
                                $id = $vehicle->vehicleId;
                                $year = $vehicle->year;
                                $make = $vehicle->make;
                                $model = $vehicle->model;
                                $trim = $vehicle->trim;
                                $yrMkMdl = ''.$year.' '.$make.' '.$model.' '.$trim;
                                echo "<option value='$id'>$yrMkMdl</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mileage">Mileage:</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="mileage" id="mileage"
                               placeholder="30000" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="vin">VIN:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                                name="vin" id="vin" placeholder="1FAFP40634F172825" required/>
                    </div>
                </div>                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="action" class="btn btn-primary" value="addVehicle">save
                        </button>
                        <button type="reset" name="reset" class="btn btn-default">reset</button>
                        <input type="hidden" name="custId" id="custId" value="<?php echo $custId; ?>"/>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Customer not found!
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
