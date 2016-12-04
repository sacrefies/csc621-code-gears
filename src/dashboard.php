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
require_once __DIR__ . '/appointments/AppointmentController.php';

use gears\accounts\AccountController;
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
    <div class="col-lg-6" id="pnlApps">
        <div class="panel panel-default">
            <div class="panel-heading">Today's Appointments</div>
            <div class="panel-body">
                <?php
                $appts = AppointmentController::getDailyAppointments();
                echo "<table class='table table-striped'>";
                echo "<tr>";
                echo "<th>Summary</th>";
                echo "<th>Description</th>";
                echo "<th>Event Time</th>";
                echo "<th>Status</th>";
                echo "<th></th>";
                echo "</tr>";
                foreach ($appts as $appt) {
                    $subject = $appt->subject;
                    $desc = $appt->desc;
                    $event = $appt->eventTime;
                    $state = $appt ->getState();

                    echo "<tr>";
                    echo "<td>" . $subject . "</td>";
                    echo "<td>" . $desc . "</td>";
                    echo "<td>" . $event->format('Y-m-d H:i:s') . "</td>";
                    echo "<td>" . $state . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3" id="pnlJobs">
        <div class="panel panel-default">
            <div class="panel-heading">Jobs in Progress</div>
            <div class="panel-body">
                <?php
                $jobs = JobsController::getAllActiveJobs();
                if ($jobs): ?>
                    <table class="table table-hover">
                        <thead>
                        <th>Summary</th>
                        <th>Key</th>
                        <th>Served At</th>
                        <th>state</th>
                        </thead>
                        <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td>
                                    <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->summary ?></a>
                                </td>
                                <td>
                                    <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->key ?></a>
                                </td>
                                <td><?php echo $job->createTime->format('m/d/Y h:i A') ?></td>
                                <td>
                                    <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $job->mechanic->empId; ?>"><?php echo $job->mechanic->fname . ' ' . $job->mechanic->lname; ?></a>
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
                if ($emps) {
                    echo '<table class="table table-condensed"><tbody>' . PHP_EOL;
                    foreach ($emps as $emp) {
                        // roll out the mechanics
                        echo '<tr>';
                        echo '<td><a href="/accounts/single_employee_view.php?empId=' . $emp->empId . '">' . $emp->fname . ' ' . $emp->lname . '</a></td>';
                        // the status
                        if ($emp->getState() === State::BUSY) {
                            echo '<td><span class="label label-default">busy</span></td>';
                        } else if ($emp->getState() === State::AVAILABLE) {
                            echo '<td><span class="label label-success">Available</span></td>';
                        } else {
                            echo '<td>' . $emp->getState() . '</td>';
                        }
                        echo '</tr>' . PHP_EOL;
                    }
                    echo '</tbody></table>' . PHP_EOL;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
