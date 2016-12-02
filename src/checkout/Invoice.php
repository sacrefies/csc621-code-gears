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

require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/../models/StatefulEntity.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\database\DBEngine;
use gears\models\Persisted;
use gears\models\State;
use gears\models\StatefulEntity;
use \DateTime;
use gears\appointments\Appointment;

date_default_timezone_set('America/New_York');


/**
 * Entity Class invoice
 * @package gears\checkout
 */
class Invoice extends StatefulEntity {

    /**
     * @var int
     */
    public $invoiceId;
    /**
     * @var Appointment the appointment this invoice is for
     */
    public $appt;
    /**
     * @var DateTime
     */
    public $createTime;
    /**
     * @var DateTime
     */
    public $updateTime;
    /**
     * @var float
     */
    public $taxRate = 0.00;
    /**
     * @var float
     */
    public $amtDue = 0.00;
    /**
     * @var float
     */
    public $amtPayed = 0.00;
    /**
     * @var float
     */
    public $discRate = 0.00;
    /**
    * @var int
    */
    public $state;


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
        $this->state = State::PENDING;
        $date = date('Y-m-d H:i:s');
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $this->createTime = $date->format('Y-m-d H:i:s');
        $this->updateTime = $date->format('Y-m-d H:i:s');
        $this->appt = null;
        $this->invoiceId = -1;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update():int {
        if ($this->invoiceId === -1) {
            $vals = [$this->appt->appId, $this->createTime, $this->updateTime, $this->state, $this->taxRate, 
                        $this->amtDue, $this->amtPayed, $this->discRate];
            return $this->insert($vals);
        }
        $vals = [$this->appt->appId, $this->createTime, $this->updateTime, $this->state,
                     $this->taxRate, $this->amtDue, $this->amtPayed, $this->discRate, $this->invoiceId];
        $where = 'invoice_id = ?';
        return $this->updateTable($where, $vals);
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove():int {
        $where = 'invoice_id = ?';
        $values = [$this->invoiceId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return  Invoice Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     * @throws \LogicException
     */
    public function copy():Invoice {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Invoice Returns a new object which is an in-memory copy of $persisted.
     * @throws \LogicException
     */
    public static function copyFrom(Persisted $persisted):Invoice {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Invoice Returns a new in-memory object of this entity.
     */
    public static function createNew():Invoice {
        return new Invoice();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Invoice Returns an instance of this entity.
     */
    public static function getInstance(int $id):Invoice {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE invoice_id = :id";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return null;
        }
        $row = $db->query($sql, array(':id' => $id))->fetch(\PDO::FETCH_ASSOC);
        $emp = self::createInstanceFromRow($row);
        $db->close();
        return $emp;
    }

     /**
     * Get a list of instances of this entity by appointment id
     *
     * @param int $appId   The appointment id to search the table for
     *
     * @return array A list of instances of this entity.
     */
    public static function getInvoicesByAppointment(int $appId):array {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE appointment_id = :id";
        $invs = array();
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return $invs;
        }
        $rows = $db->fetchAll($sql, array(':id' => $appId));
        $db->close();
        if ($rows) {
            foreach ($rows as $row) {
                $invs[] = self::createInstanceFromRow($row);
            }
        }
        return $invs;
    }

    /**
     * Get the column name of the table of this entity.
     * @return array Returns this entity's table column names
     */
    public static function getColumns():array {
        return ['invoice_id', 'appointment_id', 'create_time', 'update_time', 'state', 'tax_rate', 
                    'amount_due', 'amount_payed', 'discount_rate'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['appointment_id', 'create_time', 'update_time', 'state', 'tax_rate', 
                    'amount_due', 'amount_payed', 'discount_rate'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row):Invoice {
        // ['emp_id', 'phone_number', 'first_name', 'last_name', 'emp_code', 'is_manager', 'state']
        $appId = $row['appointment_id'];
        $inv = new Invoice($appId);
        $inv->invoiceId = (int)$row['invoice_id'];
        $inv->appt = Appointment::getInstance((int)$row['appointment_id']);
        $inv->createTime = $row['create_time'];
        $inv->updateTime = $row['update_time'];
        $inv->state = (int)$row['state'];
        $inv->taxRate = (float)$row['tax_rate'];
        $inv->amtDue = (float)$row['amount_due'];
        $inv->amtPayed= (float)$row['amount_payed'];
        $inv->discRate = (float)$row['discount_rate'];
        return $inv;
    }

    /**
     * Update the amount payed and update the state to Payed if the invoice
     *      is fully payed.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function updatePay():int {
        if($this->amtDue === $this->amtPayed){
            $this->state = State::PAYED;
        }
        if($this->amtDue < $this->amtPayed){
            $this->amtPayed = $this->amtDue;
            $this->state = State::PAYED;
        }
        $date = date('Y-m-d H:i:s');
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $this->updateTime = $date->format('Y-m-d H:i:s');
        return self::update();
    }
}
