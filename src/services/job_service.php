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

require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/JobsController.php';

$jobId = (isset($_POST['jobId']) && !empty($_POST['jobId'])) ? (int)$_POST['jobId'] : -1;
$toDel = (isset($_POST['btnDelete']) && !empty($_POST['btnDelete']) && $_POST['btnDelete'] === 'delete');
$toNext = (isset($_POST['btnNext']) && !empty($_POST['btnNext']) && $_POST['btnNext'] === 'next');
$job = JobsController::getJob($jobId);

if ($job && $toNext):
    JobsController::nextStage($job);
    JobsController::redirectTo("job_individual_view.php?jobId=$jobId");
elseif ($job && $toDel):
    $apptId = $job->appointment->appId;
    JobsController::deleteJob($job);
    JobsController::redirectTo("/appointments/appointment_detailed.php?apptId=$apptId");
else:

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
?>
<div class="panel panel-default">
    <div class="panel-heading">Job: Unknown</div>
    <div class="panel-body">
        <div class="alert alert-warning alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Failed!</strong> Jobs not found.
        </div>
    </div>
    <?php
    include __DIR__ . '/../footer.php';
    endif; ?>

