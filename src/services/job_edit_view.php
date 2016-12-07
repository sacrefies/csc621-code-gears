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
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/../appointments/AppointmentController.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/JobsController.php';

use gears\accounts\CustomerVehicle;
use gears\accounts\Employee;
use gears\appointments\Appointment;
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
if (-1 === $apptId) {
    $apptId = (isset($_POST['apptId']) && !empty($_POST['apptId'])) ? (int)$_POST['apptId'] : -1;
}
// $jobId = (isset($_GET['jobId']) && !empty($_GET['jobId'])) ? (int)$_GET['jobId'] : -1;
$appt = AppointmentController::getAppointmentById($apptId);
if (!$appt || $appt->getJob()): ?>
    <div class="alert alert-warning alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Failed!</strong> Appointment not found or This appointment has had a job already.
    </div>
<?php else:
    // create a new job for the appointment
    $job = JobsController::createNewJob($appt);

    // create the job and redirect
    $action = (isset($_POST['action']) && !empty($_POST['action'])) ? strtolower($_POST['action']) : '';
    if ($action === 'createnewjob'):
        $job->createTime = \DateTime::createFromFormat('m/d/Y h:i:s A', $_POST['createTime']);
        $job->customerVehicle = CustomerVehicle::getInstance((int)$_POST['vehicle']);
        $job->mechanic = Employee::getInstance((int)$_POST['mechanic']);
        $job->summary = $_POST['summary'];
        $job->desc = $_POST['desc'];
        $job->key = $job->getComputedKey();
        if (0 >= $job->update()) {
            error_log(__FILE__.': Failed to create job for appointment id='.$apptId);
        }
        $job->mechanic->setState(State::BUSY);
        $job->mechanic->update();
        $job->appointment->setState(State::INSERVICE);
        $job->appointment->startTime = new \DateTime();
        $job->appointment->update();
        // refresh the job object
        $job = $appt->getJob();
        JobsController::redirectTo("job_individual_view.php?jobId=$job->jobId");
    endif;
    // for form display
    $mechanics = AccountController::getEmployeesByState(State::AVAILABLE);
    $cvs = $job->appointment->customer->getVehicles();
    $cvs = array_filter($cvs, function (CustomerVehicle $cv) {
        return (!$cv->isInService());
    });
    ?>
    <!-- job -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Job: <?php echo $job->key ?>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" name="frmJobEdit" id="frmJobEdit" method="POST" action="job_edit_view.php">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="createTime">Time Created:</label>
                    <div class="col-sm-10">
                        <input type="datetime" name="createTime" id="createTime" readonly class="form-control disabled"
                               value="<?php echo $job->createTime->format('m/d/Y h:i:s A'); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="appointment">For appointment:</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            <a href="/appointments/appointment_detailed.php?apptId=<?php echo $job->appointment->appId; ?>">
                                <?php
                                $msg = $job->appointment->eventTime->format('m/d/Y h:i A') . ' - ';
                                $msg .= AccountController::getCustomerFullName($job->appointment->customer) . ': ';
                                $msg .= $job->appointment->subject;
                                echo $msg; ?>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="summary">Summary:</label>
                    <div class="col-sm-10">
                        <input placeholder="Input job summary" class="form-control" type="text" id="summary"
                               name="summary"
                               value="<?php echo htmlentities($job->summary); ?>" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="desc">Description:</label>
                    <div class="col-sm-10">
                        <textarea placeholder="Input job description" class="form-control" id="desc"
                                  name="desc"><?php echo htmlentities($job->desc); ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="vehicle">Vehicle:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="vehicle" id="vehicle" required>
                            <option disabled selected value>Select one of the customer's vehicles</option>
                            <?php foreach ($cvs as $cv):
                                /** @var $cv CustomerVehicle */ ?>
                                <option id="optVehicle_<?php echo $cv->customer_vehicle_id; ?>"
                                        value="<?php echo $cv->customer_vehicle_id; ?>">
                                    <?php
                                    $dict = [$cv->conventionVehicle->year,
                                        $cv->conventionVehicle->make,
                                        $cv->conventionVehicle->model,
                                        $cv->conventionVehicle->trim];
                                    echo number_format($cv->mileage) . ' miles: ' . implode(' ', $dict); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mechanic">Assign to mechanic:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="mechanic" id="mechanic" required>
                            <option disabled selected value>Select one available mechanic</option>
                            <?php foreach ($mechanics as $mech): ?>
                                <option id="optMech_<?php echo $mech->empId ?>" value="<?php echo $mech->empId ?>">
                                    <?php echo $mech->fname . ' ' . $mech->lname; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="action" class="btn btn-primary" value="createNewJob">save
                        </button>
                        <button type="reset" name="reset" class="btn btn-default">reset</button>
                        <input type="hidden" name="apptId" id="apptId" value="<?php echo $apptId; ?>"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>
