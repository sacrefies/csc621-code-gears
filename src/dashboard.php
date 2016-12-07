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

require_once __DIR__ . '/accounts/AccountController.php';
require_once __DIR__ . '/accounts/Employee.php';
require_once __DIR__ . '/services/JobsController.php';
require_once __DIR__ . '/models/State.php';
require_once __DIR__ . '/appointments/Appointment.php';
require_once __DIR__ . '/appointments/AppointmentController.php';

use gears\accounts\AccountController;
use gears\appointments\Appointment;
use gears\models\State;
use gears\services\JobsController;
use gears\appointments\AppointmentController;


/**
 * @var string A string variable to set the page title.
 */
$title = 'My Dashboard';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Dashboard';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout
 */
$activeMenu = 0;

include __DIR__ . '/header.php';
?>
<!-- main content starts here -->

<!-- here we place 3 panels, from left to right: 0: appointments( like to-dos), 1: jobs status, 2: mechanics status -->
<div class="row">
    <div class="col-lg-5" id="pnlApps">
        <div class="panel panel-default">
            <div class="panel-heading">Today's Appointments</div>
            <div class="panel-body">
                <?php
                $appts = AppointmentController::getDailyAppointments();
                error_log(print_r($appts, true));
                if ($appts): ?>
                    <table class="table table-condensed">
                        <tbody>
                        <?php foreach ($appts as $appt): /** @var $appt Appointment */ ?>
                            <tr>
                                <td><?php echo $appt->eventTime->format('h:i A'); ?></a></td>
                                <td><a href="appointments/appointment_detailed.php?apptId=<?php echo $appt->appId; ?>">
                                    <?php echo $appt->subject; ?></td>
                                <td>
                                    <span class="label label-info">
                                        <?php echo State::getName($appt->getState()); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    No appointments for today
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4" id="pnlJobs">
        <div class="panel panel-default">
            <div class="panel-heading">Jobs in Progress</div>
            <div class="panel-body">
                <?php
                $jobs = JobsController::getAllActiveJobs();
                if ($jobs): ?>
                    <table class="table table-condensed">
                        <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td>
                                    <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->summary ?></a>
                                </td>
                                <td>
                                    <span class="label label-primary">
                                        <?php echo State::getName($job->getState()); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    No ongoing jobs.
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3" id="pnlMech">
        <div class="panel panel-default">
            <div class="panel-heading">Who is Busy?</div>
            <div class="panel-body">
                <!-- list of mechanics (name, status badge) -->
                <?php
                $emps = AccountController::getAllEmployees();
                if ($emps): ?>
                    <table class="table table-condensed">
                        <tbody>
                        <?php foreach ($emps as $emp): ?>
                            <tr>
                                <td>
                                    <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $emp->empId; ?>>"><?php echo $emp->fname . ' ' . $emp->lname; ?></a>
                                </td>
                                <td>
                                    <?php if (State::AVAILABLE === $emp->getState()): ?>
                                        <span class="label label-success">
                                        <?php echo State::getName($emp->getState()) ?>
                                    </span>
                                    <?php elseif (State::BUSY === $emp->getState()): ?>
                                        <span class="label label-warning">
                                        <?php echo State::getName($emp->getState()) ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
