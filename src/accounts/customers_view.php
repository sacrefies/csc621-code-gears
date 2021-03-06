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


/**
 * @var string A string variable to set the page title.
 */
$title = 'Customers';
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

$cvs = AccountController::getAllCustomers();
?>
<div class="panel panel-default">
    <div class="panel-heading">Customers</div>
    <div class="panel-body">
        <?php if ($cvs) { ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>zip</th>
                    <th>Vehicles Registered</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cvs as $cust): ?>
                    <tr>
                        <td>
                            <a href="customer_individual_view.php?customerId=<?php echo $cust->customerId; ?>">
                                <?php echo AccountController::getCustomerFullName($cust); ?>
                            </a>
                        </td>
                        <td><?php echo $cust->phoneNumber; ?></td>
                        <td><?php echo $cust->zip; ?></td>
                        <td>
                            <?php
                            $veh = AccountController::getCustomerVehiclesByCustomer($cust->customerId);
                            if ($veh) {
                                echo '<a href="customer_vehicles_view.php?customerId=' . $cust->customerId . '"><span class="badge">' . count($veh) . '</span></a>' . PHP_EOL;
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Modal -->
            <div id="jobSelect" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select One Job</h4>
                        </div>
                        <div class="modal-body">
                            <p>Job List Should Be Displayed Here</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Mechanics not found
            </div>
        <?php } ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
