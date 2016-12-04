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

/**
 * @var string A string variable to set the page title.
 */
$title = 'Customers Vehicles';
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

$customerId = -1;
if (isset($_GET['customerId']) && $_GET['customerId']) {
    $customerId = (int)$_GET['customerId'];
}

$cvs = ($customerId !== -1) ? AccountController::getCustomerVehiclesByCustomer($customerId) : AccountController::getAllCustomerVehicles();
?>
<div class="panel panel-default">
    <div
        class="panel-heading"><?php echo (-1 !== $customerId) ? AccountController::getCustomerFullName($cvs[0]->customer) : 'Customer'; ?>
        Owned Vehicles
    </div>
    <div class="panel-body">
        <?php if ($cvs) { ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Owned By</th>
                    <th>Mileage</th>
                    <th>vin</th>
                    <th>Times Serviced</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cvs as $cv): ?>
                    <tr>
                        <td>
                            <a href="single_customer_vehicle_view.php?customer_vehicle_Id=<?php echo $cv->customer_vehicle_id; ?>">
                                <?php echo "{$cv->conventionVehicle->year} {$cv->conventionVehicle->make} {$cv->conventionVehicle->model} {$cv->conventionVehicle->trim}"; ?>
                            </a>
                        </td>
                        <td>
                            <a href="single_customer_view.php?customerId=<?php echo $cv->customer->customerId; ?>">
                                <?php echo AccountController::getCustomerFullName($cv->customer); ?>
                            </a>
                        </td>
                        <td><?php echo $cv->mileage; ?></td>
                        <td><?php echo $cv->vin; ?></td>
                        <td>pending on in-service package</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Mechanics not found
            </div>
        <?php } ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
