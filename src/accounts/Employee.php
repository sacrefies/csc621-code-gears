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

require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/../models/StatefulEntity.php';

use gears\database\DBEngine;
use gears\models\Persisted;
use gears\models\State;
use gears\models\StatefulEntity;
use gears\services\Job;


/**
 * Entity Class Employee
 * @package gears\accounts
 */
class Employee extends StatefulEntity {

    /**
     * @var int
     */
    public $empId;
    /**
     * @var string
     */
    public $empCode;
    /**
     * @var string
     */
    public $fname;
    /**
     * @var string
     */
    public $lname;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var bool
     */
    public $isMan;


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
        $this->state = State::AVAILABLE;
        $this->empId = -1;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update():int {
        if ($this->empId === -1) {
            if (null === $this->empCode || empty($this->empCode)) {
                return -1;
            }
            $vals = [$this->phone, $this->fname, $this->lname, $this->isMan, $this->state, $this->empCode];
            return $this->insert($vals);
        }
        $vals = [$this->phone, $this->fname, $this->lname, $this->isMan, $this->state, $this->empCode, $this->empId];
        $where = 'emp_id = ?';
        return $this->updateTable($where, $vals);
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove():int {
        $where = 'emp_id = ?';
        $values = [$this->empId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Employee Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     * @throws \LogicException
     */
    public function copy():Employee {
        $emp = new Employee();
        $emp->fname = $this->empCode;
        $emp->lname = $this->empCode;
        $emp->isMan = $this->empCode;
        $emp->phone = $this->empCode;
        return $emp;
    }

    /**
     * Get the Job object that this employee is working on.
     * @return Job|null Returns the Job object that this employee is working on.
     */
    public function getWorkingJob() {
        if ($this->empId === -1) {
            return null;
        }
        $values = [$this->empId, State::NEW, State::INSPECTING, State::ONGOING];
        return Job::getInstanceFromKeys('mechanic_id=? AND state IN (?,?,?)', $values);
    }

    /**
     * @param Job $job
     *
     * @return bool
     */
    public function assignJob(Job $job):bool {
        // TODO: assign job
        $this->setState(State::BUSY);
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Employee Returns a new object which is an in-memory copy of $persisted.
     * @throws \LogicException
     */
    public static function copyFrom(Persisted $persisted):Employee {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Employee Returns a new in-memory object of this entity.
     */
    public static function createNew():Employee {
        return new Employee();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Employee|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE emp_id = :id";
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
    public static function getColumns():array {
        return ['emp_id', 'phone_number', 'first_name', 'last_name', 'emp_code', 'is_manager', 'state'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['phone_number', 'first_name', 'last_name', 'is_manager', 'state', 'emp_code'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row):Employee {
        // ['emp_id', 'phone_number', 'first_name', 'last_name', 'emp_code', 'is_manager', 'state']
        $emp = new Employee();
        $emp->empId = $row['emp_id'];
        $emp->empCode = $row['emp_code'];
        $emp->fname = $row['first_name'];
        $emp->lname = $row['last_name'];
        $emp->isMan = (int)$row['is_manager'];
        $emp->state = (int)$row['state'];
        $emp->phone = $row['phone_number'];
        return $emp;
    }
}
