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

require_once __DIR__ . '/../models/StatefulEntity.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/../models/Persisted.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';
require_once __DIR__ . '/../accounts/CustomerVehicle.php';


use gears\models\Persisted;
use gears\models\StatefulEntity;
use gears\database\DBEngine;
use gears\appointments\Appointment;
use gears\models\State;
use gears\accounts\Employee;
use gears\accounts\CustomerVehicle;


/**
 * Class Job for a service job for an appointment.
 * @package gears\services
 */
class Job extends StatefulEntity {

    /**
     * @var int this job's id
     */
    public $jobId;
    /**
     * @var string this job's unique key
     */
    public $key;
    /**
     * @var \DateTime the time this job is created
     */
    public $createTime;
    /**
     * @var string summary
     */
    public $summary;
    /**
     * @var string description
     */
    public $desc;
    /**
     * @var Appointment The appointment to which this job references
     */
    public $appointment;
    /**
     * @var Employee The mechanic to which this job is assigned
     */
    public $mechanic;
    /**
     * @var CustomerVehicle The vehicle this job is working on.
     */
    public $customerVehicle;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct() {
        $this->jobId = -1;
        $this->key = '';
        $this->appointment = null;
        $this->customerVehicle = null;
        $this->mechanic = null;
        $this->createTime = new \DateTime();
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        if (!$this->key) {
            error_log('The unique key string of this job is not set.');
            return -1;
        }
        if (!$this->appointment || !$this->customerVehicle) {
            error_log('Either appointment or customer vehicle of this job is not set.');
            return -1;
        }
        // ['job_key', 'create_time', 'summary', 'description', 'state', 'appointment_id',
        // 'mechanic_id', 'customer_vehicle_id']
        $values = [$this->key, $this->createTime, $this->summary, $this->desc, $this->state, $this->appointment->appId,
            $this->mechanic->empId, $this->customerVehicle->customer_vehicle_id];
        if ($this->jobId === -1) {
            return $this->insert($values);
        } else {
            $values[] = $this->jobId;
            $where = 'job_id = ?';
            return $this->updateTable($where, $values);
        }
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'job_id = ? AND appointment_id = ?';
        $values = [$this->jobId, $this->appointment->appId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Persisted Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() {
        $job = new Job();
        $job->appointment = $this->appointment;
        $job->customerVehicle = $this->customerVehicle;
        $job->createTime = new \DateTime();
        $job->desc = $this->desc;
        $job->summary = $this->summary;
        $job->setState($this->getState());
        $job->key = $this->key;
    }

    /**
     * Compute and format a job key according to this job's creation time and customer full name.
     * <p>This method is for the newly created fresh Job object to obtain its unique job key before being saved to the
     * database.</p>
     * <p>NOTE: This method does not set value to $key attribute.</p>
     * @return string A formatted serial key.
     */
    public function getComputedKey() : string {
        $parts[] = 'gears';
        $parts[] = strtolower(substr($this->appointment->customer->firstName . $this->appointment->customer->lastName, 0, 4));
        $parts[] = $this->createTime->format('YmdHisO');
        return implode('-', $parts);
    }

    /**
     * Get the worksheet associates with this job. If this job has not started yet, A null point is returned.
     * @return Worksheet|null Returns an instance of Worksheet which associate with this job; returns null if no
     *                        worksheet available (in which case the Job has not started yet).
     */
    public function getWorksheet() {
        return Worksheet::getInstance($this->jobId);
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Persisted Returns a new object which is an in-memory copy of $persisted.
     * @throws \LogicException Not implemented
     */
    public static function copyFrom(Persisted $persisted) {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Job Returns a new in-memory object of this entity.
     */
    public static function createNew() : Job {
        return new Job();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Job|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE job_id = :id";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            $msg = "{$e->getFile()}: Line {$e->getLine()}: {$e->getMessage()}\n{$e->getTraceAsString()}\n";
            error_log($msg);
            return null;
        }
        $row = $db->query($sql, array(':id' => $id))->fetch(\PDO::FETCH_ASSOC);
        $db->close();
        return ($row) ? self::createInstanceFromRow($row) : null;
    }

    /**
     * Get the column name of the table of this entity.
     * @return array Returns this entity's table column names
     */
    public static function getColumns() : array {
        return ['job_id', 'job_key', 'create_time', 'summary', 'description', 'state', 'appointment_id',
            'mechanic_id', 'customer_vehicle_id'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['job_key', 'create_time', 'summary', 'description', 'state', 'appointment_id',
            'mechanic_id', 'customer_vehicle_id'];
    }

    /**
     * Create an instance of this entity from a database row.
     *
     * @param array $row The data row in the database.
     *
     * @return Job An instance of this entity.
     */
    protected static function createInstanceFromRow(array $row) : Job {
        // ['job_id', 'job_key', 'create_time', 'summary', 'description', 'state', 'appointment_id',
        // 'mechanic_id', 'customer_vehicle_id'];
        $job = new Job();
        $job->jobId = (int)$row['job_id'];
        $job->key = $row['job_key'];
        $job->createTime = new \DateTime($row['create_time']);
        $job->summary = $row['summary'];
        $job->desc = $row['description'];
        $job->state = (int)$row['state'];
        $job->appointment = Appointment::getInstance((int)$row['appointment_id']);
        $job->mechanic = Employee::getInstance((int)$row['mechanic_id']);
        $job->customerVehicle = CustomerVehicle::getInstance((int)$row['customer_vehicle_id']);
    }
}
