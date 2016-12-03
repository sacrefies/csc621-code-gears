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
namespace gears\accounts;

require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../models/Persisted.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/ConventionVehicle.php';
require_once __DIR__ . '/Customer.php';
require_once __DIR__ . '/../services/Job.php';


use gears\models\Persisted;
use gears\models\State;
use gears\models\StaticEntity;
use gears\database\DBEngine;
use gears\services\Job;


/**
 * Class CustomerVehicle for the customer owned vehicles
 * @package gears\accounts
 */
class CustomerVehicle extends StaticEntity {
    /**
     * @var int The id of this instance
     */
    public $customer_vehicle_id;
    /**
     * @var Customer The customer who owns this vehicle.
     */
    public $customer;
    /**
     * @var ConventionVehicle The conventional vehicle to which is this instance linked
     */
    public $conventionVehicle;
    /**
     * @var int The mileage of this vehicle.
     */
    public $mileage;
    /**
     * @var string the vin number of this vehicle.
     */
    public $vin;

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
    protected function __construct() {
        $this->customer_vehicle_id = -1;
        $this->customer = null;
        $this->conventionVehicle = null;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        // ['customer_id', 'customer_vehicle_id', 'mileage', 'vin'];
        $values = [$this->customer->customerId, $this->conventionVehicle->vehicleId, $this->mileage, $this->vin];
        if ($this->customer_vehicle_id === -1) {
            return $this->insert($values);
        } else {
            $values[] = $this->customer_vehicle_id;
            $where = 'customer_vehicle_id = ?';
            return $this->updateTable($where, $values);
        }
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'customer_vehicle_id = ? AND customer_id= ? AND convention_vehicle_id = ?';
        $values = [$this->customer_vehicle_id, $this->customer->customerId, $this->conventionVehicle->vehicleId];
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
        $cv = new CustomerVehicle();
        $cv->customer = $this->customer;
        $cv->conventionVehicle = $this->conventionVehicle;
        $cv->mileage = $this->mileage;
        $cv->vin = $this->vin;
        return $cv;
    }

    /**
     * Check whether this vehicle is currently being serviced.
     * @return bool Returns true if this vehicle is being serviced; otherwise false.
     */
    public function isInService() : bool {
        return $this->getServicingJob() ? true : false;
    }

    /**
     * Get the Job object which is servicing this vehicle.
     * @return Job|null Returns the Job object or null if no.
     */
    public function getServicingJob() {
        $where = 'customer_vehicle_id = ? AND state NOT IN (?, ?)';
        $values = [$this->customer_vehicle_id, State::CANCELLED, State::DONE];
        return Job::getInstanceFromKeys($where, $values);
    }

    /**
     * Get the jobs that serviced for this vehicle.
     * @return array Returns an array of Job objects
     */
    public function getServicedJobs() : array {
        $where = 'customer_vehicle_id = ? AND state IN (?, ?)';
        $values = [$this->customer_vehicle_id, State::CANCELLED, State::DONE];
        return Job::getInstanceFromKeys($where, $values);
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
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Persisted Returns a new in-memory object of this entity.
     */
    public static function createNew() {
        return new CustomerVehicle();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return CustomerVehicle|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE convention_vehicle_id = :id";
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
        return ['customer_vehicle_id', 'customer_id', 'convention_vehicle_id', 'mileage', 'vin'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['customer_id', 'convention_vehicle_id', 'mileage', 'vin'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row) : CustomerVehicle {
        // ['customer_vehicle_id', 'customer_id', 'convention_vehicle_id', 'mileage', 'vin'];
        $emp = new CustomerVehicle();
        $emp->customer_vehicle_id = (int)$row['customer_vehicle_id'];
        $emp->mileage = (int)$row['mileage'];
        $emp->vin = $row['vin'];
        $emp->customer = Customer::getInstance((int)$row['customer_id']);
        $emp->conventionVehicle = ConventionVehicle::getInstance((int)$row['convention_vehicle_id']);
        return $emp;
    }
}
