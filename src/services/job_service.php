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
$job = JobsController::getJob($jobId);

if ($job):
    JobsController::nextStage($job);
    JobsController::redirectTo("job_individual_view.php?jobId=$jobId");
else:
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

