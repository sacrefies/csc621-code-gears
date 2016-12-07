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

require_once __DIR__ . '/../appointments/AppointmentController.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\appointments\Appointment;
use gears\accounts\Customer;

use gears\appointments\AppointmentController;
use gears\checkout\checkoutController;
use gears\models\State;


/**
 * @var string A string variable to set the page title.
 */
$title = 'Appointment';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Appointments';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 1;

include __DIR__ . '/../header.php';

/*
 * subject
 * description
 * event
 * state
 */

$appts = AppointmentController::getWeeklyAppointments();
?>


<div class="panel panel-default">
    <div class="panel-heading">Weekly Appointments</div>
    <div class="panel-body">

        <?php
        $appts = AppointmentController::getAllAppointments(); //will need to change to get weekly appointments method in controller
        echo "<table class='table table-striped'>";
        echo "<tr>";
        echo "<th>Summary</th>";
        echo "<th>Description</th>";
        echo "<th>Week Day</th>";
        echo "<th>Status</th>";
        echo "<th></th>";
        echo "</tr>";
        foreach ($appts as $appt) {
            /** @var $appt Appointment */
            $apptId = $appt->appId;
            $subject = $appt->subject;
            $desc = $appt->desc;
            $event = $appt->eventTime; //eventTime
            $state = State::getName($appt->getState());

            echo "<tr>";
            echo "<td><a href=\"appointment_detailed.php?apptId=$apptId\"> $subject </a></td>";
            echo "<td>" . $desc . "</td>";
            echo "<td>" . $event->format('l-m-d-W') . "</td>";
            echo "<td>" . $state . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
