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

declare(strict_types=1);
namespace gears\appointments;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../models/StatefulEntity.php';
require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../models/State.php';

use gears\Controller;
use gears\conf\Settings;
use gears\models\State;

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
     * Get an array of all Appointments
     *
     * @return array Returns an array of all Appointments
     *
     */
    static public function getAllAppointments():array{
        $appointments = Appointment::getAll();
        $allApp = array();
        foreach($appointments as $app){
            if( $app->getState() === STATE::NEW ||
                $app->getState() === STATE::INSERVICE ||
                $app->getState() === STATE::DONE ||
                $app->getState() === STATE::CANCELLED ||
                $app->getState() === STATE::INVOICING){

                $allApp[] = $app;
            }

        }
        return $allApp;
    }

    /**
     * Get an array of all new Appointments.
     *
     * @return array Returns an array of all new Appointments.
     */
    static public function getNewAppointments():array {
        $appointments = Appointment::getAll();
        $new = array();
        foreach($appointments as $app) {
            if($app->getState() === STATE::NEW) {
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
    static public function getInserviceAppointments():array {
        $appointments = Appointment::getAll();
        $inservice = array();
        foreach($appointments as $app) {
            if($app->getState() === STATE::INSERVICE) {
                $inservice[] = $app;
            }
        }
        return $inservice;
    }

    /**
     * Get an array of all done Appointments.
     *
     * @return array Returns an array of all done Appointments.
     */
    static public function getDoneAppointments():array {
        $appointments = Appointment::getAll();
        $done = array();
        foreach($appointments as $app) {
            if($app->getState() === STATE::DONE) {
                $done[] = $app;
            }
        }
        return $done;
    }
    
    /**
     * Get an array of all cancelled Appointments.
     *
     * @return array Returns an array of all cancelled Appointments.
     */
    static public function getCancelledAppointments():array {
        $appointments = Appointment::getAll();
        $cancelled = array();
        foreach($appointments as $app) {
            if($app->getState() === STATE::CANCELLED) {
                $cancelled[] = $app;
            }
        }
        return $cancelled;
    }
    
    /**
     * Get an array of all invoicing Appointments.
     *
     * @return array Returns an array of all invoicing Appointments.
     */
    static public function getInvoicingAppointments():array {
        $appointments = Appointment::getAll();
        $invoicingApp = array();
        foreach($appointments as $appointment) {
            if($appointment->getState() === STATE::INVOICING) {
                $invoicingApp[] = $appointment;
            }
        }
        return $invoicingApp;
    }
    
    /**
     * Get an array of all invoicing Appointments.
     *
     * @return array Returns an array of daily Appointments.
     */
    /*static public function getTimelyAppointments():array{
        $appointments = Appointment::getList();
        $dailyApp = array();
        foreach($appointments as $appointment){
            if(){
                //put in a where clause
                //event_time >= sunday & event_time =< saturday

            }
        }
        return $dailyApp;
    }*/

}

