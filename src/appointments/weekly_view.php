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

$appts = AppointmentController::getAllAppointments();
$monAppts = array_filter($appts, function (Appointment $appt) {
    return (1 === date('N', $appt->eventTime->getTimestamp()));
});

$tuesAppts = array_filter($appts, function (Appointment $appt) {
    return (2 === date('N', $appt->eventTime->getTimestamp()));
});

$wedAppts = array_filter($appts, function (Appointment $appt) {
    return (3 === date('N', $appt->eventTime->getTimestamp()));
});

$thursAppts = array_filter($appts, function (Appointment $appt) {
    return (4 === date('N', $appt->eventTime->getTimestamp()));
});

$friAppts = array_filter($appts, function (Appointment $appt) {
    return (5 === date('N', $appt->eventTime->getTimestamp()));
});

$satAppts = array_filter($appts, function (Appointment $appt) {
    return (6 === date('N', $appt->eventTime->getTimestamp()));
});

$sunAppts = array_filter($appts, function (Appointment $appt) {
    return (7 === date('N', $appt->eventTime->getTimestamp()));
});

if (!$monAppts) {
    echo '<tr class="bg-info"><td colspan="4">Monday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$tuesAppts) {
    echo '<tr class="bg-info"><td colspan="4">Tuesday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$wedAppts) {
    echo '<tr class="bg-info"><td colspan="4">Wednesday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$thursAppts) {
    echo '<tr class="bg-info"><td colspan="4">Thursday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$friAppts) {
    echo '<tr class="bg-info"><td colspan="4">Friday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$satAppts) {
    echo '<tr class="bg-info"><td colspan="4">Saturday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

if (!$sunAppts) {
    echo '<tr class="bg-info"><td colspan="4">Sunday</td></tr>';
    // echo columns
    echo '<tr>';
    // tds
    echo '</tr>';
}

?>


<div class="panel panel-default">
    <div class="panel-heading">Weekly Appointments</div>
    <div class="panel-body">

        <?php
        $appts = AppointmentController::getAllAppointments();
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
            $event = $appt->eventTime;
            $state = State::getName($appt->getState()); //getName($appt->getState())

            echo "<tr>";
            echo "<td><a href=\"appointment_detailed.php?apptId=$apptId\"> $subject </a></td>";
            echo "<td>" . $desc . "</td>";
            echo "<td>" . $event->format('l') . "</td>"; //format('Y-m-d H:i:s')
            echo "<td>" . $state . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
