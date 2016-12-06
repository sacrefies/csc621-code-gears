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
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/JobsController.php';
require_once __DIR__ . '/Worksheet.php';

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

$jobId = (isset($_POST['jobId']) && !empty($_POST['jobId'])) ? (int)$_POST['jobId'] : -1;
$action = (isset($_POST['action']) && !empty($_POST['action'])) ? $_POST['action'] : null;

$job = JobsController::getJob($jobId);
if ($action && $action === 'createWorksheet') {
    // save and redirect
    $sheet = JobsController::createWorksheet($job);
    $sheet->mileage = (int)$_POST['mileage'];
    $sheet->update();
    JobsController::redirectTo("job_individual_view.php?jobId=$jobId");
} ?>
<div class="panel panel-default">
    <div class="panel-heading">Edit Worksheet</div>
    <div class="panel-body">
        <?php if ($job): ?>
            <form name="frmWorksheet" id="frmWorksheet" class="form-horizontal"
                  action="<?php echo JobsController::getSelfScript(); ?>" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="key">Job key:</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $job->key; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="summary">Job Summary:</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $job->summary; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mileage">Current mileage:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" min="1" pattern="^[1-9]+[0-9]+$" id="mileage"
                               required name="mileage"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="action" class="btn btn-primary" value="createWorksheet">save
                        </button>
                        <button type="reset" name="reset" class="btn btn-default">reset</button>
                        <input type="hidden" name="jobId" id="jobId" value="<?php echo $jobId; ?>"/>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Job not found!
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
