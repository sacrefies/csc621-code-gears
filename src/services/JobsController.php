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
require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/Worksheet.php';
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
    public static function getJob(int $jobId) {
        return (-1 === $jobId) ? null : Job::getInstance($jobId);
    }

    /**
     * Get a worksheet of a Job
     *
     * @param int $sheetId The unique id of the worksheet (it's also a job's id)
     *
     * @return Worksheet|null Returns an instance of Worksheet or null if not found.
     */
    public static function getWorkSheet(int $sheetId) {
        return (-1 === $sheetId) ? null : Worksheet::getInstance($sheetId);
    }

    /**
     * Create a new instance of Worksheet for a job.
     *
     * @param Job $job The job to have a new worksheet
     *
     * @return Worksheet|null Returns an instance of Worksheet or null if creation failed.
     */
    public static function createWorksheet(Job $job) {
        if (!$job || $job->getWorksheet()) {
            return null;
        }
        $sheet = Worksheet::createNew();
        $sheet->job = $job;
        return $sheet;
    }

    /**
     * Create a new instance of Task for a worksheet
     *
     * @param Worksheet $sheet       The Worksheet to have a new task
     * @param InventoryItem $invItem The InventoryItem with which the new task associates.
     *
     * @return Task|null Returns an instance of Task or null if creation failed.
     */
    public static function createTask(Worksheet $sheet, InventoryItem $invItem) {
        if (!$sheet || !$invItem) {
            return null;
        }
        if (Task::getTaskInstance($sheet->job->jobId, $invItem->itemId)) {
            return null;
        }
        $task = Task::createNew();
        $task->invItem = $invItem;
        $task->worksheet = $sheet;
        return $task;
    }

    /**
     * Proceed to the next step of job's process.
     *
     * @param Job $job
     *
     * @return bool
     */
    public static function nextStage(Job $job): bool {
        // TODO: nextStage
        if (!$job || $job->isFinished()) {
            return false;
        }
        switch ($job->getState()) {
            case State::INSPECTING:
                $job->setState(State::ONGOING);
                return (0 < $job->update());
                break;
            case State::NEW:
                $job->setState(State::INSPECTING);
                return (0 < $job->update());
                break;
            case State::ONGOING:
                $job->setState(State::DONE);
                return self::setJobDone($job);
                break;
        }
        return false;
    }

    /**
     * Set the given job done. This action will modify states and date time attributes of all entities which associate
     * with this job.
     *
     * @param Job $job
     *
     * @return bool
     */
    public static function setJobDone(Job $job): bool {
        if (!$job || $job->isFinished()) {
            return false;
        }
        $job->setState(State::DONE);
        $sheet = $job->getWorksheet();
        if (!$sheet) {
            return false;
        }
        $sheet->endTime = new \DateTime();
        $tasks = $sheet->getTasks();
        if (!$tasks) {
            return false;
        }
        foreach ($tasks as $task) {
            $task->finishTime = new \DateTime();
            $task->isDone = 1;
            if (0 >= $task->update()) {
                return false;
            }
        }
        return (0 < $sheet->update() && 0 < $job->update());
    }

    /**
     * Delete a task.
     *
     * @param Task $task The task to delete
     *
     * @return bool Returns true if deletion is successful; returns false otherwise.
     */
    public static function deleteTask(Task $task): bool {
        if (!$task) {
            return false;
        }
        return (0 < $task->remove());
    }


    /**
     * Get an array of all active Jobs.
     *
     *
     * @return array Returns an array of all active Jobs.
     */
    static public function getAllActiveJobs(): array {
        $where = 'state IN (?,?,?)';
        $values = [State::NEW , State::INSPECTING, State::ONGOING ];
        return Job::getList($where, $values);
    }

    /**
     * Get an array of all new Jobs.
     *
     *
     * @return array Returns an array of all new Jobs.
     */
    static public function getAllNewJobs(): array {
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
    static public function getAllInspectingJobs(): array {
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
    static public function getAllOngoingJobs(): array {
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
    static public function getAllDoneJobs(): array {
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
