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

$emps = AccountController::getAllEmployees();
?>
<div class="panel panel-default">
    <div class="panel-heading">Mechanics</div>
    <div class="panel-body">
        <form id="frmJobAssignment" action="mechanics_view.php" method="post">
            <?php if ($emps) { ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Work Phone</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($emps as $emp) { ?>
                    <tr>
                        <td>
                            <a href="mechanic_individual_view.php?empId=<?php echo $emp->empId; ?>"><?php echo $emp->empCode; ?></a>
                        </td>
                        <td><?php echo $emp->fname; ?></td>
                        <td><?php echo $emp->lname; ?></td>
                        <td><?php echo $emp->phone; ?></td>
                        <td>
                            <?php if ($emp->isMan) { ?>
                                <span class="glyphicon glyphicon-user"></span>
                            <?php } else { ?>
                                <span class="glyphicon glyphicon-wrench"></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($emp->getState() === State::AVAILABLE) { ?>
                                <h5><span class="label label-success">AVAILABLE</span></h5>
                            <?php } else { ?>
                                <h5><span class="label label-default">BUSY</span></h5>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($emp->getState() === State::AVAILABLE) { ?>
                                <button type="button" name="btnPickJob" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#jobSelect">Assign Job
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
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
        </form>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Mechanics not found
            </div>
        <?php } ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
