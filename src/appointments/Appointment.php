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

require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../models/Persisted.php';
require_once __DIR__ . '/../models/StatefulEntity.php';
require_once __DIR__.'/../checkout/invoice.php';

use gears\database\DBEngine;
use gears\accounts\Customer;
use gears\models\Persisted;
use gears\models\StatefulEntity;
use gears\checkout\Invoice;


/**
 * Class Appointment
 * @package gears\appointments
 */
class Appointment extends StatefulEntity {

    /**
     * @var int appointment id
     */
    public $appId;
    /**
     * @var string appointment summary/subject
     */
    public $subject;
    /**
     * @var \DateTime time that this appointment is updated.
     */
    public $updateTime;
    /**
     * @var \DateTime time that this appointment is created
     */
    public $createTime;
    /**
     * @var string|null description
     */
    public $desc;
    /**
     * @var \DateTime time that this appointment is booked for
     */
    public $eventTime;
    /**
     * @var \DateTime time that this appointment started
     */
    public $startTime;
    /**
     * @var \DateTime time that this appointment ended.
     */
    public $endTime;
    /**
     * @var Customer The customer who booked this appointment.
     */
    public $customer;

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
        $this->appId = -1;
        $this->customer = null;
        $this->updateTime = \DateTime::createFromFormat(DATE_ISO8601, '1970-01-01 00:00:000');
        $this->createTime = \DateTime::createFromFormat(DATE_ISO8601, '1970-01-01 00:00:000');
        $this->endTime = \DateTime::createFromFormat(DATE_ISO8601, '1970-01-01 00:00:000');
        $this->startTime = \DateTime::createFromFormat(DATE_ISO8601, '1970-01-01 00:00:000');
        $this->eventTime = \DateTime::createFromFormat(DATE_ISO8601, '1970-01-01 00:00:000');
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        // ['subject', 'update_time', 'create_time', 'description', 'event_time', 'start_time', 'end_time',
        // 'customer_id', 'state'];
        $values = [$this->subject, $this->updateTime, $this->createTime, $this->desc, $this->eventTime,
            $this->startTime, $this->endTime, $this->customer->customerId, $this->state];
        if ($this->appId === -1) {
            return $this->insert($values);
        } else {
            $values[] = $this->appId;
            $where = 'appointment_id = ?';
            return $this->updateTable($where, $values);
        }
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'appointment_id = ?';
        $values = [$this->appId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Appointment Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() : Appointment {
        $app = new Appointment();
        $app->createTime = $this->createTime;
        $app->updateTime = $this->updateTime;
        $app->startTime = $this->startTime;
        $app->endTime = $this->endTime;
        $app->eventTime = $this->eventTime;
        $app->desc = $this->desc;
        $app->subject = $this->subject;
        $app->customer = $this->customer;
        $app->state = $this->state;
        return $app;
    }

    public function getJob() {
        // TODO getJob()
        return null;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice() {
        $where = 'appointment_id = ?';
        $values = [$this->appId];
        return Invoice::getInstanceFromKeys($where, $values);
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
        return null;
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Appointment Returns a new in-memory object of this entity.
     */
    public static function createNew() : Appointment {
        return new Appointment();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Appointment|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE appointment_id = :id";
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
        return ['appointment_id', 'subject', 'update_time', 'create_time', 'description', 'event_time', 'start_time',
            'end_time', 'customer_id', 'state'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['subject', 'update_time', 'create_time', 'description', 'event_time', 'start_time', 'end_time',
            'customer_id', 'state'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row) : Appointment {
        // ['appointment_id', 'subject', 'update_time', 'create_time', 'description', 'event_time', 'start_time',
        // 'end_time', 'customer_id', 'state'];
        $app = new Appointment();
        $app->appId = (int)$row['appointment_id'];
        $app->subject = $row['subject'];
        $app->updateTime = \DateTime::createFromFormat(DATE_ISO8601, $row['update_time']);
        $app->createTime = \DateTime::createFromFormat(DATE_ISO8601, $row['create_time']);
        $app->desc = $row['description'];
        $app->eventTime = \DateTime::createFromFormat(DATE_ISO8601, $row['event_time']);
        $app->startTime = \DateTime::createFromFormat(DATE_ISO8601, $row['start_time']);
        $app->endTime = \DateTime::createFromFormat(DATE_ISO8601, $row['end_time']);
        $app->customer = Customer::getInstance((int)$row['customer_id']);
        $app->state = (int)$row['state'];
        return $app;
    }
}
