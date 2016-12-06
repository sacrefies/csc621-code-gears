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


$sheetId = (isset($_POST['worksheetJobId']) && !empty($_POST['worksheetJobId'])) ? (int)$_POST['worksheetJobId'] : -1;
$itemId = (isset($_POST['action']) && !empty($_POST['action'])) ? (int)explode('_', $_POST['action'])[1] : -1;


echo '<pre>';
print_r($_POST);
print_r($itemId);
echo '</pre>';


$sheet = JobsController::getWorkSheet($sheetId);
$item = JobsController::getInventoryItem($itemId);

if (JobsController::deleteTask($sheet, $item)) {
    JobsController::redirectTo('job_individual_view.php?jobId=' . $sheetId);
}
?>
