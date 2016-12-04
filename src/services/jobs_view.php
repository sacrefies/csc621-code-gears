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
namespace gears\checkout;
require_once __DIR__ . '/JobsController.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../accounts/Customer.php';
use gears\appointments\Appointment;
use gears\accounts\Customer;
/**
 * @var string A string variable to set the page title.
 */
$title = 'Jobs';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Jobs';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout
 */
$activeMenu = 2;
include __DIR__ . '/../header.php';
?>
<!-- main content starts here -->
<body>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">Jobs</div>
    <div class="panel-body">
        <div role="tabpanel">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#activeTab" role="tab"
                    data-toggle="tab">Active</a></li>
                <li role="presentation"><a href="#doneTab" role="tab"
                    data-toggle="tab">Done</a></li>
            </ul>
            <div class="tab-content">
                <div id="activeTab" role="tabpanel" class="tab-pane active">
                    <?php
                    $jobs = JobsController::getAllActiveJobs();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Job</th>";
                    echo "<th>Customer</th>";
                    echo "<th>Summary</th>";
                    echo "<th>Time Created</th>";
                    echo "<th></th>";
                    echo "</tr>";
                    foreach ($jobs as $job) {
                        $key = $job->key;
                        $app = $job->appointment;
                        $summary = $job->summary;
                        $cust = Customer::getInstance($app->customer->customerId);
                        $custFirst = $cust->firstName;
                        $custLast = $cust->lastName;
                        $custName = "" . $custFirst . " " . $custLast;
                        echo "<tr>";
                        echo "<td>" . $key . "</td>";
                        echo "<td>" . $custName . "</td>";
                        echo "<td>" . $summary . "</td>";
                        echo "<td>" . $job->createTime->format('Y-m-d H:i:s') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
                <div id="doneTab" role="tabpanel" class="tab-pane">
                    <?php
                    $jobs = JobsController::getAllDoneJobs();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Job</th>";
                    echo "<th>Customer</th>";
                    echo "<th>Summary</th>";
                    echo "<th>Time Created</th>";
                    echo "<th></th>";
                    echo "</tr>";
                    foreach ($jobs as $job) {
                        $key = $job->key;
                        $app = $job->appointment;
                        $summary = $job->summary;
                        $cust = Customer::getInstance($app->customer->customerId);
                        $custFirst = $cust->firstName;
                        $custLast = $cust->lastName;
                        $custName = "" . $custFirst . " " . $custLast;
                        echo "<tr>";
                        echo "<td>" . $key . "</td>";
                        echo "<td>" . $custName . "</td>";
                        echo "<td>" . $summary . "</td>";
                        echo "<td>" . $job->createTime->format('Y-m-d H:i:s') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>