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

use gears\accounts\ConventionVehicle;
use gears\accounts\CustomerVehicle;
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
    public static function createWorksheet(Job $job = null) {
        if (!$job || $job->getWorksheet()) {
            return null;
        }
        $sheet = Worksheet::createNew();
        $sheet->job = $job;
        $sheet->startTime = new \DateTime();
        return $sheet;
    }

    public static function jobToInspection(Job $job = null): bool {
        if (!$job || $job->isFinished()) {
            return false;
        }
        if (State::NEW !== $job->getState()) {
            return false;
        }
        // from new to inspection
        // update job's state
        $job->setState(State::INSPECTING);
        return (0 < $job->update());
    }

    public static function jobToOngoing(Job $job = null): bool {
        if (!$job || $job->isFinished()) {
            return false;
        }
        if (State::INSPECTING !== $job->getState()) {
            return false;
        }
        // from inspection to ongoing
        // update job's state
        $job->setState(State::ONGOING);
        return (0 < $job->update());
    }

    /**
     * Cancel a job.
     * <p>Only the jobs in state of 'NEW' or 'INSPECTING' can be cancelled.</p>
     *
     * @param Job|null $job
     *
     * @return bool
     */
    public static function jobToCancel(Job $job = null): bool {
        if (!$job || $job->isFinished()) {
            return false;
        }
        $state = $job->getState();
        if (State::INSPECTING !== $state && State::NEW !== $state) {
            return false;
        }
        // from new or inspection to cancelled
        // this cancellation is driven by appointment, so leave the appointment part
        // but the following object states must be considered:
        //  - Worksheet
        //  - Tasks
        //  - Employee
        // customer's vehicle mileage will be changed if the current state is INSPECTING
        // update job's state
        $job->setState(State::CANCELLED);
        // update worksheet if exists
        $sheet = $job->getWorksheet();
        $tasks = $sheet->getTasks();
        $mechanic = $job->mechanic;
        $cv = $job->customerVehicle;

        if ($mechanic) {
            $mechanic->setState(State::AVAILABLE);
        }
        if ($state === State::INSPECTING && $cv && $sheet) {
            $cv->mileage = $sheet->mileage;
        }
        if ($sheet) {
            $sheet->endTime = new \DateTime();
        }
        // update tasks
        if ($tasks) {
            foreach ($tasks as $task) {
                $task->finishTime = new \DateTime();
                $task->isDone = 1;
                if (0 >= $task->update()) {
                    return false;
                }
            }
        }
        // now, update one by one
        if (!$sheet && 0 >= $sheet->update()) {
            return false;
        }
        if ($cv && $state === State::INSPECTING) {
            if (0 >= $job->customerVehicle->update()) {
                return false;
            }
        }
        if (!$mechanic && 0 >= $mechanic->update()) {
            return false;
        }
        return (0 < $job->update());
    }

    /**
     * Create a new instance of Task for a worksheet
     *
     * @param Worksheet $sheet       The Worksheet to have a new task
     * @param InventoryItem $invItem The InventoryItem with which the new task associates.
     *
     * @return Task|null Returns an instance of Task or null if creation failed.
     */
    public static function createTask(Worksheet $sheet = null, InventoryItem $invItem = null) {
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
    public static function nextStage(Job $job = null): bool {
        // TODO: nextStage
        if (!$job || $job->isFinished()) {
            return false;
        }
        switch ($job->getState()) {
            case State::INSPECTING:
                return self::jobToOngoing($job);
            case State::NEW:
                return self::jobToInspection($job);
            case State::ONGOING:
                return self::jobToDone($job);
        }
        return false;
    }

    /**
     * @param int $itemId
     *
     * @return InventoryItem|null
     */
    public static function getInventoryItem(int $itemId) {
        return (-1 === $itemId) ? null : InventoryItem::getInstance($itemId);
    }

    /**
     * @param CustomerVehicle $vehicle
     *
     * @return InventoryItem[]
     */
    public static function getInventoryItemsForCustomerVehicle(CustomerVehicle $vehicle = null) {
        if (!$vehicle) {
            return [];
        }
        $where = 'convention_vehicle_id = ?';
        $values = [$vehicle->conventionVehicle->vehicleId];
        return InventoryItem::getList($where, $values);
    }

    /**
     * @param Worksheet $sheet
     * @param CustomerVehicle $vehicle
     *
     * @return InventoryItem[]
     */
    public static function getAvailableInventoryItems(Worksheet $sheet = null, CustomerVehicle $vehicle = null): array {
        if (!$vehicle || !$sheet) {
            return [];
        }
        $having = $sheet->getTasks();
        if (!$having) {
            return self::getInventoryItemsForCustomerVehicle($vehicle);
        }
        $where = 'convention_vehicle_id = ? AND item_id NOT IN (' . implode(',', array_fill(0, count($having), '?')) . ')';
        $values = [$vehicle->conventionVehicle->vehicleId];
        foreach ($having as $h) {
            $values[] = $h->invItem->itemId;
        }
        return InventoryItem::getList($where, $values);
    }

    /**
     * Set the given job done. This action will modify states and date time attributes of all entities which associate
     * with this job.
     *
     * @param Job $job
     *
     * @return bool
     */
    public static function jobToDone(Job $job = null): bool {
        if (!$job || $job->isFinished()) {
            error_log(__FUNCTION__.': job is null or job is finished');
            return false;
        }
        $state = $job->getState();
        if ($state !== State::ONGOING) {
            error_log(__FUNCTION__.': job state is incorrect: '. State::getName($state));
            return false;
        }

        // from ongoing to done
        // this action is driven by job, so the appointment must be updated respectively
        // the following object states must be considered:
        //  - Worksheet
        //  - Tasks
        //  - CustomerVehicle
        //  - Employee
        //  - Appointment

        $sheet = $job->getWorksheet();
        if (!$sheet) {
            error_log(__FUNCTION__.': job has no worksheet');
            return false;
        }
        $mechanic = $job->mechanic;
        if (!$mechanic) {
            error_log(__FUNCTION__.': job has no mechanic');
            return false;
        }
        $appt = $job->appointment;
        if (!$appt) {
            error_log(__FUNCTION__.': job has no appointment');
            return false;
        }
        $cv = $job->customerVehicle;
        if (!$cv) {
            error_log(__FUNCTION__.': job has no vehicle');
            return false;
        }
        $tasks = $sheet->getTasks();
        if (!$tasks) {
            error_log(__FUNCTION__.': worksheet has no tasks');
            return false;
        }

        // update job's state
        $job->setState(State::DONE);
        $sheet->endTime = new \DateTime();
        $cv->mileage = $sheet->mileage;
        $mechanic->setState(State::AVAILABLE);
        $appt->endTime = new \DateTime();
        $appt->setState(State::DONE);
        // update the tasks
        foreach ($tasks as $task) {
            $task->finishTime = new \DateTime();
            $task->isDone = 1;
            if (0 >= $task->update()) {
                error_log(__FUNCTION__.': one task failed to update');
                return false;
            }
        }
        if (0 >= $sheet->update()) {
            error_log(__FUNCTION__.': worksheet failed to update');
            return false;
        }
        if (0 >= $cv->update()) {
            error_log(__FUNCTION__.': customer vehicle failed to update');
            return false;
        }
        if (0 >= $mechanic->update()) {
            error_log(__FUNCTION__.': mechanic failed to update');
            return false;
        }
        if (0 >= $appt->update()) {
            error_log(__FUNCTION__.': appointment failed to update');
            return false;
        }
        if (0 >= $job->update()) {
            error_log(__FUNCTION__.': job failed to update');
            return false;
        }
        return true;
    }

    /**
     * Delete a task.
     *
     * @param $sheet Worksheet
     * @param $item  InventoryItem
     *
     * @return bool Returns true if deletion is successful; returns false otherwise.
     */
    public static function deleteTask(Worksheet $sheet = null, InventoryItem $item = null): bool {
        if (!$sheet || !$item) {
            return false;
        }
        $task = Task::getTaskInstance($sheet->job->jobId, $item->itemId);
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
        $values = [State::NEW, State::INSPECTING, State::ONGOING];
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
