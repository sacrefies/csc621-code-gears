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

$sheetId = (isset($_POST['worksheetJobId']) && !empty($_POST['worksheetJobId'])) ? (int)$_POST['worksheetJobId'] : -1;
$sheet = JobsController::getWorkSheet($sheetId);

if (isset($_POST['action']) && !empty($_POST['action'])):
    $action = strtolower($_POST['action']);
    switch ($action):
        case 'addtask':
            $job = JobsController::getJob($sheetId);
            $cv = $job ? $job->customerVehicle : null;
            $invItems = JobsController::getInventoryItemsForCustomerVehicle($cv); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Task
                </div>
                <div class="panel-body">
                    <?php if (!$sheet): ?>
                        <div class="alert alert-info alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Failed!</strong> Worksheet not found.
                        </div>
                    <?php else:
                        $avails = JobsController::getAvailableInventoryItems($sheet, $cv);
                        // group tasks to 2 groups: LABOR and PARTS
                        $labors = array_filter($avails, function (/**@var InventoryItem $item */
                            $item) {
                            return 'LABOR' === $item->category;
                        });
                        $parts = array_filter($avails, function (/**@var InventoryItem $item */
                            $item) {
                            return 'PARTS' === $item->category;
                        });
                        ?>
                        <form class="form-horizontal" id="frmTask"
                              action="<?php echo JobsController::getSelfScript(); ?>"
                              method="POST">
                            <div class="form-group">
                                <lable class="control-label col-sm-2" for="jobKey">Job Key:</lable>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        <?php echo $job->key; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <lable class="control-label col-sm-2" for="jobSummary">Job Summary:</lable>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        <?php echo $job->summary; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <lable class="control-label col-sm-2" for="itemId">Select Inventory Item:</lable>
                                <div class="col-sm-10">
                                    <?php if ($avails): ?>
                                        <select class="form-control" name="itemId" id="itemId" required>
                                            <option value="-1">Select one item</option>
                                            <?php if ($labors): ?>
                                                <optgroup label="Labor">
                                                    <?php foreach ($labors as $item): /** @var InventoryItem $item */ ?>
                                                        <option value="<?php echo $item->itemId; ?>">
                                                            <?php echo $item->code . ': ' . $item->part . ' ' . $item->unitPrice . '/' . $item->unit ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </optgroup>
                                            <?php endif; ?>
                                            <?php if ($parts): ?>
                                                <optgroup label="Parts">
                                                    <?php foreach ($parts as $item): /** @var InventoryItem $item */ ?>
                                                        <option value="<?php echo $item->itemId; ?>">
                                                            <?php echo $item->code . ': ' . $item->part . ' ' . $item->unitPrice . '/' . $item->unit ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </optgroup>
                                            <?php endif; ?>
                                        </select>
                                    <?php else: ?>
                                        No available inventory items.
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <lable class="control-label col-sm-2" for="jobSummary">Quantity:</lable>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" required id="quantity" name="quantity"
                                           placeholder="Input Quantity" min="1" pattern="[-+]?[0-9]*[.,]?[0-9]+"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php if ($avails): ?>
                                        <button type="submit" name="action" class="btn btn-primary" value="saveTask">
                                            save
                                        </button>
                                        <button type="reset" name="reset" class="btn btn-default">reset</button>
                                        <input type="hidden" name="worksheetJobId" id="worksheetJobId"
                                               value="<?php echo $sheetId; ?>"/>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php break;
        case 'savetask':
            $itemId = (isset($_POST['itemId']) && !empty($_POST['itemId'])) ? (int)$_POST['itemId'] : -1;
            $invItem = JobsController::getInventoryItem($itemId);
            $task = JobsController::createTask($sheet, $invItem);
            $task->quantity = (int)$_POST['quantity'];
            $task->cost = $invItem->unitPrice * $task->quantity;
            $task->isDone = 0;
            $rc = $task->update();
            JobsController::redirectTo("/services/job_individual_view.php?jobId=$sheetId");
            break;
    endswitch;
    if ('deltask' === $action):
    endif;
    if ('addtask' === $action): ?>
    <?php endif;
endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>
