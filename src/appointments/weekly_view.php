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
require_once __DIR__ . '/../conf/Settings.php';

use gears\appointments\Appointment;
use gears\accounts\Customer;

use gears\appointments\AppointmentController;
use gears\checkout\checkoutController;
use gears\models\State;
use gears\conf\Settings;


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
<!-- calendar view -->
<script src="/fullcalendar/lib/moment.min.js" type="text/javascript"></script>
<script src="/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
    // prepare fullcalendar dependencies
    function prep_fullcalendar_files() {
        $('head').append('<link href="/fullcalendar/fullcalendar.css" rel="stylesheet"/>')
            .append('<link href="/fullcalendar/fullcalendar.print.css" rel="stylesheet" media="print"/>');
    }
    $(document).ready(prep_fullcalendar_files);
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prevYear, prev, next, nextYear today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            weekNumbers: true,
            weekNumbersWithinDays: true,
            weekNumberCalculation: 'ISO',
            defaultView: 'listWeek',
            defaultDate: '<?php echo (new \DateTime())->format('Y-m-d'); ?>',
            editable: false,
            navLinks: true, // can click day/week names to navigate views
            eventLimit: true, // allow "more" link when too many events
            timezone: '<?php echo Settings::timeZone()->getName();?>',
            events: {
                url: 'appointment_data_all_api.php',
                error: function () {
                    $('#script-warning').show();
                }
            },
            loading: function (bool) {
                $('#loading').toggle(bool);
            },
            eventRender: function (event, el) {
                // render the appointment description below the event title
                // el.find('.fc-title').after($('<span class="small">' + event.desc + '</span>'));
                $('.fc-event-content, .fc-event-time, .fc-event-title').css('font-size', '1.85em');
                el.tooltip({
                    title: event.desc,
                    placement: new Date(event.start).getHours() > 12 ? 'top' : 'bottom',
                    html: true
                });
            }
        });
    });
</script>
<div class="panel panel-default">
    <div class="panel-heading">Appointment Calendar</div>
    <div class="panel-body">
        <div id='script-warning'>
            <code>The Appointment API</code> must be running.
        </div>
        <div id='loading'>loading...</div>
        <div id='calendar'></div>
    </div>
</div>
<style type="text/css">
    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }
</style>
<!-- end calendar view -->
<?php include __DIR__ . '/../footer.php'; ?>
