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
namespace gears;

require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../models/State.php';

use gears\accounts\AccountController;
use gears\models\State;


/**
 * @var string A string variable to set the page title.
 */
$title = 'Mechanic';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Profiles';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 4;

include __DIR__ . '/../header.php';

$emp = AccountController::getEmployeeById((int)$_GET['empId']);
?>
<div class="panel panel-default">
    <div class="panel-heading">Mechanics</div>
    <div class="panel-body">
        <?php if ($emp) { ?>
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="empCode">Employee Code:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $emp->empCode; ?>" type="text" class="form-control disabled"
                               id="empCode" placeholder="Employee Code" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="fname">First Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $emp->fname; ?>" type="text" class="form-control disabled" id="fname"
                               placeholder="First Name" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lname">Last Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $emp->lname; ?>" type="text" class="form-control disabled" id="lname"
                               placeholder="Last Name" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Phone Number:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $emp->phone; ?>" type="tel" class="form-control disabled" id="phone"
                               placeholder="Phone Number" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="isMan">Employee Type:</label>
                    <div class="col-sm-10">
                        <?php if (!$emp->isMan) { ?>
                            <p><span class="glyphicon glyphicon-wrench" id="isMan"> Mechanic</span></p>
                        <?php } else { ?>
                            <p><span class="glyphicon glyphicon-user" id="isMan"> Manager</span></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="state">Mechanic Status:</label>
                    <div class="col-sm-10">
                        <?php if ($emp->getState() === State::AVAILABLE) { ?>
                            <h4><span class="label label-success" id="state">AVAILABLE</span></h4>
                        <?php } else { ?>
                            <h4><span class="label label-default" id="state">BUSY</span></h4>
                        <?php } ?>
                    </div>
                </div>
            </form>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Mechanic not found
            </div>
        <?php } ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
