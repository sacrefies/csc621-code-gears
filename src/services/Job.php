<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 11/30/2016
 * Time: 23:55
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
        $this->key = null;
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
        // TODO: Implement update() method.
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        // TODO: Implement remove() method.
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Persisted Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() {
        // TODO: Implement copy() method.
    }

    /**
     * Get the worksheet associates with this job. If this job has not started yet, A null point is returned.
     * @return Worksheet|null Returns an instance of Worksheet which associate with this job; returns null if no
     *                        worksheet available (in which case the Job has not started yet).
     */
    public function getWorksheet() {
        return null;
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Persisted Returns a new object which is an in-memory copy of $persisted.
     */
    public static function copyFrom(Persisted $persisted) {
        // TODO: Implement copyFrom() method.
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Persisted Returns a new in-memory object of this entity.
     */
    public static function createNew() {
        // TODO: Implement createNew() method.
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Persisted Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        // TODO: Implement getInstance() method.
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
        // TODO: Implement getUpdateColumns() method.
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
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
        $job->jobId = $row['job_id'];
    }


}
