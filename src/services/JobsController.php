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
namespace gears\checkout;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\Controller;
use gears\conf\Settings;
use gears\models\State;
use gears\services\Job;
use gears\appointments\Appointment;


/**
 * Class CheckoutController is a controller for checkout relevant businesses.
 * @package gears\accounts
 */
final class JobsController {
    use Controller;

    /**
     * Get an array of all active Jobs.
     *
     *
     * @return array Returns an array of all active Jobs.
     */
    static public function getAllActiveJobs():array {
        $jobs = Job::getAll();
        $active = array();
        foreach($jobs as $job) {
        	$state = $job->getState();
            if($state === State::NEW || $state === State::INSPECTING || $state === State::ONGOING) {
                $active[] = $job;
            }
        }
        return $active;
    }

    /**
     * Get an array of all done Jobs.
     *
     *
     * @return array Returns an array of all done Jobs.
     */
    static public function getAllDoneJobs():array {
        $jobs = Job::getAll();
        $done = array();
        foreach($jobs as $job) {
        	$state = $job->getState();
            if($state === State::CANCELLED || $state === State::DONE) {
                $done[] = $job;
            }
        }
        return $done;
    }


}