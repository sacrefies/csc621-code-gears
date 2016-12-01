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
namespace gears\appointments;


/**
 * @var string A string variable to set the page title.
 */
$title = 'Mechanic';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Profiles';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 1;

include __DIR__ . '/../header.php';

// here we have the appointments
$appointments = null;
?>
    <div class="panel panel-default">
        <div class="panel-heading">Mechanics</div>
        <div class="panel-body">
            <?php if ($appointments) { ?>
            <?php } else { ?>
                <div class="alert alert-warning alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Failed!</strong> Appointments not found
                </div>
            <?php } ?>
        </div>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>
