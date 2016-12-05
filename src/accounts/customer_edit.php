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

$error = '';
$custId = (isset($_POST['customerId']) && !empty($_POST['customerId'])) ? (int)$_POST['customerId'] : -1;
// get our beloved customer object
$customer = AccountController::getCustomerById($custId);
// do update or create new
if (isset($_POST['updateType']) && !empty($_POST['updateType'])) {
    if ($_POST['updateType'] === 'new') {
        if (AccountController::createNewCustomer($_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['zip'])) {
            AccountController::redirectTo('customers_view.php');
        } else {
            $error = 'Saving customer information failed.';
        }
    } else if ($_POST['updateType'] === 'update') {
        if (AccountController::updateCustomer($customer, $_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['zip'])) {
            AccountController::redirectTo('customers_view.php');
        } else {
            $error = 'Saving customer information failed.';
        }
    }
}
?>
<div class="panel panel-default">
    <div class="panel-heading">
        Customer <?php echo $customer ? AccountController::getCustomerFullName($customer) : 'Unknown'; ?></div>
    <div class="panel-body">
        <?php if ($error): ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form class="form-horizontal" method="POST" action="<?php echo AccountController::getSelfScript(); ?>">
            <?php if ($customer): ?>
                <input type="hidden" name="updateType" id="updateType" value="update"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="firstName">First Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->firstName; ?>" type="text" class="form-control"
                               id="firstName" name="firstName" placeholder="First Name" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->lastName; ?>" type="text" class="form-control" id="lastName"
                               name="lastName" placeholder="Last Name" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Phone Number:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $customer->phoneNumber; ?>" type="tel" class="form-control" id="phone"
                               name="phone" placeholder="123-456-7890" pattern="^\d{3}[\-]\d{3}[\-]\d{4}$" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="zip">Zip Code:</label>
                    <div class="col-sm-10">
                        <input value="<?php $customer->zip; ?>" pattern="^(\d{5}([\-]\d{4})?)$" type="text"
                               name="zip" class="form-control" id="zip" placeholder="19000 or 19000-0000" required/>
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="updateType" id="updateType" value="new"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="firstName">First Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Phone Number:</label>
                    <div class="col-sm-10">
                        <input type="tel" class="form-control" name="phone" id="phone"
                               pattern="^\d{3}[\-]\d{3}[\-]\d{4}$" placeholder="123-456-7890" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="zip">Zip Code:</label>
                    <div class="col-sm-10">
                        <input type="text" pattern="^(\d{5}([\-]\d{4})?)$" class="form-control" name="zip" id="zip"
                               placeholder="19000 or 19000-0000" required/>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">save</button>
                    <button type="reset" name="reset" class="btn btn-default">reset</button>
                    <input type="hidden" name="customerId" id="customerId" value="<?php echo $custId; ?>"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
