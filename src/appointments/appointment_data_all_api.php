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

require_once __DIR__ . '/AppointmentController.php';
require_once __DIR__ .'/Event.php';
require_once __DIR__ . '/../conf/Settings.php';

use gears\conf\Settings;

/**
 * This api is for client side calendar view.
 * This api's data exchange protocol strictly follows the fullcalendar json/boostrap extension.
 *
 * See the demo/example: /fullcalendar/demos/php/get-event.php
 */

// 1. get and prepare the parameters received from query string.
//  the needed are:
//      $_GET['start']  -- range start, not event start time
//      $_GET['end']    -- range end, not event end time
//      $_GET['timezone'])  -- optional

// Short-circuit if the client did not give us a date range.
if (!isset($_GET['start'], $_GET['end'])) {
    die('Please provide a date range.');
}

// Parse the start/end parameters.
// These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
// Since no timezone will be present, they will parsed as UTC.
$range_start = parseDateTime($_GET['start']);
$range_end = parseDateTime($_GET['end']);

// Parse the timezone parameter if it is present.
$timezone = null;
if (isset($_GET['timezone'])) {
    $timezone = new \DateTimeZone($_GET['timezone']);
}

// 2. get data source
$appts = AppointmentController::getAllAppointments();
$outputs = [];
foreach ($appts as $appt) {
    $event = new Event($appt, $timezone);
    // If the event is in-bounds, add it to the output
    if ($event->isWithinDayRange($range_start, $range_end)) {
        $outputs[] = $event->toArray();
    }
}

// Send JSON to the client.
echo json_encode($outputs);
?>
