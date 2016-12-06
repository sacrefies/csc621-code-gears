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
require_once __DIR__ . '/ConventionVehicle.php';
require_once __DIR__ . '/../services/Job.php';

use gears\services\Job;

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

$cvId = (isset($_GET['customer_vehicle_Id']) && $_GET['customer_vehicle_Id']) ? (int)$_GET['customer_vehicle_Id'] : -1;
// get the vehicle
$cv = AccountController::getCustomerVehicle($cvId);
$fullName = implode(' ', [$cv->conventionVehicle->year, $cv->conventionVehicle->make, $cv->conventionVehicle->model, $cv->conventionVehicle->trim]);
$jobs = $cv ? $cv->getServicedJobs() : [];
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $cv ? $fullName . ': ' . $cv->mileage . ' miles' : 'Unknown'; ?></div>
    <div class="panel-body">
        <?php if ($cv): ?>
            <form class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="make">Make:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->conventionVehicle->make; ?>" type="text"
                               class="form-control disabled" id="make" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="model">Model:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->conventionVehicle->model; ?>" type="text"
                               class="form-control disabled" id="model" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="trim">Trim:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->conventionVehicle->trim; ?>" type="text"
                               class="form-control disabled" id="trim" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="year">Year on Market:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->conventionVehicle->year; ?>" type="number"
                               class="form-control disabled" id="year" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mileage">Mileage:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->mileage; ?>" type="number" class="form-control disabled"
                               id="mileage" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="vin">VIN:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $cv->vin; ?>" type="text" class="form-control disabled"
                               id="vin" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="owner">Owner:</label>
                    <div class="col-sm-10">
                        <a href="customer_individual_view.php?customerId=<?php echo $cv->customer->customerId; ?>"
                           id="owner"><?php echo AccountController::getCustomerFullName($cv->customer); ?></a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="jobs">Being serviced:</label>
                    <div class="col-sm-10">
                        <?php if ($cv->isInService()): ?>
                            <?php $job = $cv->getServicingJob(); ?>
                            <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->summary; ?></a> by
                            <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $job->mechanic->empId; ?>"><?php echo $job->mechanic->fname . ' ' . $job->mechanic->lname; ?></a>
                        <?php else: ?>
                            <h4><span class="label label-default" id="jobs">NOT AT STORE</span></h4>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Vehicle not found
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        Service Records: <?php echo (0 === count($jobs)) ? 'No record' : count($jobs); ?></div>
    <div class="panel-body">
        <?php if ($jobs): ?>
            <table class="table table-hover">
                <thead>
                <th>Summary</th>
                <th>Key</th>
                <th>Served At</th>
                <th>Mechanic</th>
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
                        <td><a href="/accounts/mechanic_individual_view.php?empId=<?php echo $job->mechanic->empId; ?>"><?php echo $job->mechanic->fname . ' ' . $job->mechanic->lname; ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h4>No record found</h4>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
