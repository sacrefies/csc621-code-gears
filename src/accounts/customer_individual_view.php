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
<?php include __DIR__ . '/../footer.php'; ?>
