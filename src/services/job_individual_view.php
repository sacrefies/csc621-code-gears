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
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../accounts/ConventionVehicle.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/JobsController.php';
require_once __DIR__ . '/InventoryItem.php';
require_once __DIR__ . '/Worksheet.php';

use gears\models\State;
use gears\services\JobsController;
use gears\accounts\AccountController;

/**
 * @var string A string variable to set the page title.
 */
$title = 'Services';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Services';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 2;

include __DIR__ . '/../header.php';

$jobId = (isset($_GET['jobId']) && !empty($_GET['jobId'])) ? (int)$_GET['jobId'] : -1;
$job = JobsController::getJob($jobId);
$sh = $job ? $job->getWorksheet() : null;
$tasks = $sh ? $sh->getTasks() : [];

if (!$tasks): ?>
    <div class="alert alert-info alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Note!</strong> This job needs a worksheet and at least 1 task to proceed.
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-9">
        <!-- job -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if ($job):
                    $state = $job->getState(); ?>
                    <span class="label label-primary"><?php echo State::getName($state); ?></span>
                    <?php if (!$job->isFinished() && $sh && $tasks): ?>
                    <div class="btn-group pull-right">
                        <form class="form-horizontal" method="POST" action="job_service.php">
                            <input type="hidden" value="<?php echo $job->jobId; ?>>" name="jobId"/>
                            <button class="btn btn-primary btn-sm" type="submit" name="btnNext" value="next">
                                Next <span class="glyphicon glyphicon-forward"></span>
                            </button>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>
                <?php else: ?>
                    Unknown Job
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if ($job): ?>
                    <form class="form-horizontal" method="POST" action="job_edit_view.php">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="key">Job Unique Key:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $job->key; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="summary">Summary:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo htmlentities($job->summary); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="desc">Description:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo htmlentities($job->desc); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="createTime">Time Created:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $job->createTime->format('m/d/Y h:i A'); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="appointment">For appointment:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <a href="/appointments/appointment_detail.php?apptId=<?php echo $job->appointment->appId; ?>">
                                        <?php echo $job->appointment->eventTime->format('m/d/Y h:i A') . ' - ' . $job->appointment->subject; ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Failed!</strong> Job not found
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel-group">
            <!-- mechanic -->
            <div class="panel panel-default">
                <div class="panel-heading">Mechanic</div>
                <div class="panel-body">
                    <?php if ($job): ?>
                        <form>
                            <div class="form-group">
                                <label class="control-label" for="name">Name:</label>
                                <p class="form-control-static">
                                    <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $job->mechanic->empId; ?>">
                                        <?php echo $job->mechanic->fname . ' ' . $job->mechanic->lname; ?>
                                    </a>
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="phone">Tel:</label>
                                <p class="form-control-static"><?php echo $job->mechanic->phone; ?></p>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <!-- customer -->
            <div class="panel panel-default">
                <div class="panel-heading">Customer Vehicle</div>
                <div class="panel-body">
                    <?php if ($job): ?>
                        <form>
                            <div class="form-group">
                                <label class="control-label" for="name">Customer:</label>
                                <p class="form-control-static">
                                    <a href="/accounts/customer_individual_view.php?customerId=<?php echo $job->customerVehicle->customer->customerId; ?>">
                                        <?php echo AccountController::getCustomerFullName($job->customerVehicle->customer); ?>
                                    </a>
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="phone">Tel:</label>
                                <p class="form-control-static"><?php echo $job->customerVehicle->customer->phoneNumber; ?></p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="vehicle">Vehicle:</label>
                                <p class="form-control-static">
                                    <a href="/accounts/customer_vehicle_individual_view.php?customer_vehicle_Id=<?php echo $job->customerVehicle->customer_vehicle_id; ?>">
                                        <?php $conVec = $job->customerVehicle->conventionVehicle;
                                        echo $conVec->year . ' ' . $conVec->make . ' ' . $conVec->model . ' ' . $conVec->trim . ' - ' . $job->customerVehicle->mileage . ' miles'; ?>
                                    </a>
                                </p>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($job): ?>
    <!-- worksheet -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Worksheet
            <div class="btn-group pull-right">
                <form class="form-horizontal" method="POST" action="worksheet_service.php">
                    <input type="hidden" value="<?php echo $job->jobId; ?>" name="jobId"/>
                    <?php if (!$sh): ?>
                        <button class="btn btn-default btn-sm" type="submit" name="btnAddWorksheet"
                                value="addWorkSheet">
                            Worksheet <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    <?php else: ?>
                        <input type="hidden" value="<?php echo $job->jobId; ?>" name="jobId"/>
                        <?php if ($tasks): ?>
                            <button class="btn btn-default btn-sm" type="submit" name="btnAllTasksDone"
                                    value="allTasksDone">
                                Done <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-primary btn-sm" type="submit" name="btnAddTask" value="addTask">
                            Task <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <?php if ($sh): ?>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mileage">Vehicle's Current Mileage:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?php echo $sh->mileage; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="startTime">Started At:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?php echo $sh->startTime->format('m/d/Y h:i A'); ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="endTime">End At:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?php echo $sh->startTime->format('m/d/Y h:i A'); ?></p>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                No worksheet found.
            <?php endif; ?>
        </div>
    </div>
    <!-- tasks -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Tasks
        </div>
        <div class="panel-body">

        </div>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>
