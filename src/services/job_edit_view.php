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

require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../accounts/ConventionVehicle.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/JobsController.php';
require_once __DIR__ . '/InventoryItem.php';
require_once __DIR__ . '/Worksheet.php';
require_once __DIR__ . '/Task.php';

use gears\appointments\AppointmentController;
use gears\models\State;
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

$apptId = (isset($_GET['apptId']) && !empty($_GET['apptId'])) ? (int)$_GET['apptId'] : -1;
// $jobId = (isset($_GET['jobId']) && !empty($_GET['jobId'])) ? (int)$_GET['jobId'] : -1;
$appt = AppointmentController::getAppointmentById($apptId);
if (!$appt || $appt->getJob()): ?>
    <div class="alert alert-info alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Failed!</strong> Appointment not found or This appointment has had a job already.
    </div>
<?php endif; ?>
<!-- job -->
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="POST" action="job_edit_view.php">
            <div class="form-group">
                <label class="control-label col-sm-3" for="key">Job key:</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $job->key; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="summary">Summary:</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo htmlentities($job->summary); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="desc">Description:</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo htmlentities($job->desc); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="createTime">Time Created:</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $job->createTime->format('m/d/Y h:i A'); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="appointment">For appointment:</label>
                <div class="col-sm-9">
                    <p class="form-control-static">
                        <a href="/appointments/appointment_detail.php?apptId=<?php echo $job->appointment->appId; ?>">
                            <?php echo $job->appointment->eventTime->format('m/d/Y h:i A') . ' - ' . $job->appointment->subject; ?>
                        </a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
