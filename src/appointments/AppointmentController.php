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

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../models/StatefulEntity.php';
require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__.'/../conf/Settings.php';

use gears\Controller;
use gears\conf\Settings;
use gears\models\State;
use gears\accounts\Customer;

/* appointment states
    NEW
    INSERVICE
    DONE
    CANCELLED
    INVOICING
*/

class AppointmentController {

    use Controller;

    /**
     * Get an array of all Appointments order by event time
     *
     * @return Appointment[] Returns an array of all Appointments
     *
     */
    static public function getAllAppointments(): array {
        $order = 'event_time';
        return Appointment::getAll($order);
    }

    /**
     * Get all appointments of one specific date. If $date is given null, this method returns the appointments of today.
     *
     * @param \DateTime|null $date
     *
     * @return array Appointment[] Returns an array of one day's Appointments
     */
    static public function getDailyAppointments(\DateTime $date = null): array {
        if (!$date) {
            $date = new \DateTime();
        }
        $start = $date->format('Y-m-d') . ' 00:00:00';
        $end = $date->add(new \DateInterval('P1D'))->format('Y-m-d') . '00:00:00';
        $where = 'event_time >= ? AND event_time < ?';
        $values = [$start, $end];
        $order = 'event_time';
        return Appointment::getList($where, $values, $order);
    }

    /**
     * Get all appointments for one week
     * @return array
     */

    /**
     * Get all appointments for the week in which the specified date is. If $date is given null, this method returns
     * the appointments of the week where today is.
     *
     * @param \DateTime|null $date
     *
     * @return array
     */
    static public function getWeeklyAppointments(\DateTime $date = null): array {
        if (!$date) {
            $date = new \DateTime();
        }
        // date: 1 - Monday, 7 - Sunday
        $sunday = 7;
        $monday = 1;
        $dayOfWeek = (int)date('N', $date);
        // always create a clone of the current date to do the calculation
        $dt = new \DateInterval($dayOfWeek - $monday);
        $start = (new \DateTime($date->format(Settings::$MYSQL_DATETIME_FORMAT)))->sub($dt);
        $startDate = $start->format('Y-m-d'). ' 00:00:00';
        $dt = new \DateInterval($sunday - $dayOfWeek);
        $end = (new \DateTime($date->format(Settings::$MYSQL_DATETIME_FORMAT)))->add($dt);
        $endDate = $end->format('Y-m-d'). ' 00:00:00';

        $where = 'event_time >= ? AND event_time < ?';
        $values = [$startDate, $endDate];
        $order = 'event_time';
        return Appointment::getList($where, $values, $order);
    }

    /**
     * Get an array of all new Appointments.
     *
     * @return array Returns an array of all new Appointments.
     */
    static public function getNewAppointments(): array {
        $appointments = Appointment::getAll();
        $new = array();
        foreach ($appointments as $app) {
            if ($app->getState() === STATE::NEW) {
                $new[] = $app;
            }
        }
        return $new;
    }

    /**
     * Get an array of all inservice Appointments.
     *
     * @return array Returns an array of all inservice Appointments.
     */
    static public function getInserviceAppointments(): array {
        $appointments = Appointment::getAll();
        return array_filter($appointments, function(Appointment $appt) {
            return $appt->getState() === State::INSERVICE;
        });
    }

    /**
     * Get an array of all done Appointments.
     *
     * @return array Returns an array of all done Appointments.
     */
    static public function getDoneAppointments(): array {
        $appointments = Appointment::getAll();
        return array_filter($appointments, function(Appointment $appt) {
            return $appt->getState() === State::DONE;
        });
    }

    /**
     * Get an array of all cancelled Appointments.
     *
     * @return array Returns an array of all cancelled Appointments.
     */
    static public function getCancelledAppointments(): array {
        $appointments = Appointment::getAll();
        return array_filter($appointments, function(Appointment $appt) {
            return $appt->getState() === State::CANCELLED;
        });
    }

    /**
     * Get an array of all invoicing Appointments.
     *
     * @return array Returns an array of all invoicing Appointments.
     */
    static public function getInvoicingAppointments(): array {
        $appointments = Appointment::getAll();
        return array_filter($appointments, function(Appointment $appt) {
            return $appt->getState() === State::INVOICING;
        });
    }

    /**
     * @param int $appId
     *
     * @return Appointment|null gets appointment by id
     */
    public static function getAppointmentById(int $appId) {
        return (0 > $appId) ? null : Appointment::getInstance($appId);
    }

    public static function createNewAppointment(string $custId, string $subject, string $desc, string $date): bool {
        $dateTime = new \DateTime($date);
        $app = Appointment::createNew();
        $app->subject = $subject;
        $app->desc = $desc;
        $app->eventTime = $dateTime;
        $custId = (int)$custId;
        $cust = Customer::getInstance($custId);
        $app->customer = $cust;
        $rc = $app->update();
        return (-1 !== $rc);
    }

    public static function updateAppointment(Appointment $app, string $subject, string $desc, string $date): bool {
        $dateTime = new \DateTime($date);
        $app->subject = $subject;
        $app->desc = $desc;
        $app->eventTime = $dateTime;
        $rc = $app->update();
        return (-1 !== $rc);
    }

}

