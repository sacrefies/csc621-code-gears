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

require_once __DIR__ . '/Appointment.php';
require_once __DIR__.'/../accounts/AccountController.php';

use gears\accounts\AccountController;


// Parses a string into a DateTime object, optionally forced into the given timezone.
function parseDateTime(string $string, \DateTimeZone $timezone = null) {
    $date = new \DateTime(
        $string,
        $timezone ? $timezone : new \DateTimeZone('UTC')
    // Used only when the string is ambiguous.
    // Ignored if string has a timezone offset in it.
    );
    if ($timezone) {
        // If our timezone was ignored above, force it.
        $date->setTimezone($timezone);
    }
    return $date;
}

// Takes the year/month/date values of the given DateTime and converts them to a new DateTime,
// but in UTC.
function stripTime(\DateTime $datetime) {
    return new \DateTime($datetime->format('Y-m-d'));
}


/**
 * Class Event is dedicated to form a json object. This class will be used by data APIs of Appointment.
 * @package gears\models
 */
final class Event {

    /**
     * CONST: Tests whether the given ISO8601 string has a time-of-day or not. It matches strings like "2013-12-29"
     */
    const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/';
    const APPOINTMENT_DETAIL_URL = '/appointments/appointment_detailed.php?apptId=';
    /**
     * @var string
     */
    public $title;
    /**
     * @var bool
     */
    public $allDay;
    /**
     * @var \DateTime
     */
    public $start;
    /**
     * @var \DateTime|null
     */
    public $end;
    /**
     * @var array
     */
    public $properties = array();
    /**
     * @var string the appointment detail page url
     */
    public $url;

    /**
     * Event constructor.
     *
     * @param Appointment $appt
     * @param \DateTimeZone|null $timezone
     */
    public function __construct(Appointment $appt, \DateTimeZone $timezone = null) {
        $this->title = $appt->subject;
        // no appointment is an all-day event
        $this->allDay = false;
        $this->end = null;
        $this->url = Event::APPOINTMENT_DETAIL_URL . $appt->appId;

        if ($this->allDay) {
            // If dates are allDay, we want to parse them in UTC to avoid DST issues.
            $timezone = null;
        }

        // Parse dates
        $this->start = parseDateTime($appt->eventTime->format(DATE_ATOM), $timezone);
        // Record misc properties
        $this->properties['startTime'] = $appt->startTime->format(DATE_ATOM);
        $this->properties['endTime'] = $appt->endTime->format(DATE_ATOM);
        $this->properties['updateTime'] = $appt->updateTime->format(DATE_ATOM);
        $this->properties['customer'] = AccountController::getCustomerFullName($appt->customer);
        $this->properties['desc'] = $appt->desc;
    }

    /**
     * Returns whether the date range of our event intersects with the given all-day range.
     * @param $rangeStart (assumed to be dates in UTC with 00:00:00 time.)
     * @param $rangeEnd (assumed to be dates in UTC with 00:00:00 time.)
     *
     * @return bool
     */
    public function isWithinDayRange($rangeStart, $rangeEnd) : bool {

        // Normalize our event's dates for comparison with the all-day range.
        $eventStart = stripTime($this->start);
        $eventEnd = isset($this->end) ? stripTime($this->end) : null;

        if (!$eventEnd) {
            // No end time? Only check if the start is within range.
            return $eventStart < $rangeEnd && $eventStart >= $rangeStart;
        } else {
            // Check if the two ranges intersect.
            return $eventStart < $rangeEnd && $eventEnd > $rangeStart;
        }
    }

    /**
     * Converts this Event object back to a plain data array, to be used for generating JSON
     * @return array
     */
    public function toArray() : array {

        // Start with the misc properties (don't worry, PHP won't affect the original array)
        $array = $this->properties;

        $array['title'] = $this->title;
        $array['url'] = $this->url;

        // Figure out the date format. This essentially encodes allDay into the date string.
        if ($this->allDay) {
            $format = 'Y-m-d'; // output like "2013-12-29"
        } else {
            $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }

        // Serialize dates into strings
        $array['start'] = $this->start->format($format);
        if ($this->end) {
            $array['end'] = $this->end->format($format);
        }

        return $array;
    }
}
