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

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\Controller;
use gears\models\State;
use gears\appointments\Appointment;


/**
 * Class CheckoutController is a controller for checkout relevant businesses.
 * @package gears\accounts
 */
final class JobsController {
    use Controller;

    /**
     * Get an instance of Job
     *
     * @param int $jobId The unique id of the job.
     *
     * @return Job|null Returns an instance of Job or null if not found.
     */
    public function getJob(int $jobId) {
        return (-1 === $jobId) ? null : Job::getInstance($jobId);
    }

    /**
     * Get an array of all active Jobs.
     *
     *
     * @return array Returns an array of all active Jobs.
     */
    static public function getAllActiveJobs():array {
        $jobs = Job::getAll();
        $active = array();
        foreach ($jobs as $job) {
            $state = $job->getState();
            if ($state === State::NEW || $state === State::INSPECTING || $state === State::ONGOING) {
                $active[] = $job;
            }
        }
        return $active;
    }

    /**
     * Get an array of all new Jobs.
     *
     *
     * @return array Returns an array of all new Jobs.
     */
    static public function getAllNewJobs():array {
        $jobs = Job::getAll();
        $new = array();
        foreach ($jobs as $job) {
            $state = $job->getState();
            if ($state === State::NEW) {
                $new[] = $job;
            }
        }
        return $new;
    }

    /**
     * Get an array of all inspecting Jobs.
     *
     *
     * @return array Returns an array of all inspecting Jobs.
     */
    static public function getAllInspectingJobs():array {
        $jobs = Job::getAll();
        $inspecting = array();
        foreach ($jobs as $job) {
            $state = $job->getState();
            if ($state === State::INSPECTING) {
                $inspecting[] = $job;
            }
        }
        return $inspecting;
    }

    /**
     * Get an array of all ongoing Jobs.
     *
     *
     * @return array Returns an array of all ongoing Jobs.
     */
    static public function getAllOngoingJobs():array {
        $jobs = Job::getAll();
        $ongoing = array();
        foreach ($jobs as $job) {
            $state = $job->getState();
            if ($state === State::ONGOING) {
                $ongoing[] = $job;
            }
        }
        return $ongoing;
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
        foreach ($jobs as $job) {
            $state = $job->getState();
            if ($state === State::CANCELLED || $state === State::DONE) {
                $done[] = $job;
            }
        }
        return $done;
    }


}
