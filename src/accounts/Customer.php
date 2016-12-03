<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/1/2016
 * Time: 00:58
 */


declare(strict_types = 1);
namespace gears\accounts;

require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../models/Persisted.php';
require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/../database/DBEngine.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\appointments\Appointment;
use gears\models\Persisted;
use gears\models\State;
use gears\models\StaticEntity;
use gears\database\DBEngine;


/**
 * Class Customer for the Customer entity
 * @package gears\accounts
 */
class Customer extends StaticEntity {

    /**
     * @var int The unique id of this customer.
     */
    public $customerId;
    /**
     * @var string This customer's first name.
     */
    public $firstName;
    /**
     * @var string This customer's last name.
     */
    public $lastName;
    /**
     * @var string this customer's phone number.
     */
    public $phoneNumber;
    /**
     * @var string this customer's zip code.
     */
    public $zip;

    /**
     * Customer constructor.
     */
    protected function __construct() {
        $this->customerId = -1;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        $values = [$this->firstName, $this->lastName, $this->phoneNumber, $this->zip];
        if ($this->customerId === -1) {
            return $this->insert($values);
        } else {
            $values[] = $this->customerId;
            $where = 'customer_id = ?';
            return $this->updateTable($where, $values);
        }
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'customer_id = ?';
        $values = [$this->customerId];
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
        $ctmr = new Customer();
        $ctmr->firstName = $this->firstName;
        $ctmr->lastName = $this->lastName;
        $ctmr->phoneNumber = $this->phoneNumber;
        $ctmr->zip = $this->zip;
        return $ctmr;
    }

    /**
     * Get all vehicles that this customer owns.
     * @return array
     */
    public function getVehicles() {
        $where = 'customer_id = ?';
        $values = [$this->customerId];
        $order = 'customer_vehicle_id';
        return CustomerVehicle::getList($where, $values, $order);
    }

    /**
     * Get active appointment for this customer
     * @return array A list of appointments
     */
    public function getActiveAppointment() : array {
        $where = 'customer_id = ? AND state NOT IN (?, ?)';
        $values = [$this->customerId, State::DONE, State::CANCELLED];
        return Appointment::getList($where, $values);
    }

    /**
     * Get history appointments for this customer
     * @return array A list appointments
     */
    public function getHistoryAppointment() {
        $where = 'customer_id = ? AND state IN (?, ?)';
        $values = [$this->customerId, State::DONE, State::CANCELLED];
        return Appointment::getList($where, $values);
    }

    /**
     * Get all appointments requested by this customer.
     * @return array A list of appointments.
     */
    public function getAppointments() {
        $where = 'customer_id = ?';
        $values = [$this->customerId];
        return Appointment::getList($where, $values);
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
     * @return Customer Returns a new in-memory object of this entity.
     */
    public static function createNew() : Customer {
        return new Customer();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Customer|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE customer_id = :id";
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
        return ['customer_id', 'first_name', 'last_name', 'phone_number', 'customer_zip'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['first_name', 'last_name', 'phone_number', 'customer_zip'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row) : Customer {
        // ['customer_id', 'first_name', 'last_name', 'phone_number', 'customer_zip'];
        $emp = new Customer();
        $emp->customerId = (int)$row['customer_id'];
        $emp->firstName = $row['first_name'];
        $emp->lastName = $row['last_name'];
        $emp->phoneNumber = $row['phone_number'];
        $emp->zip = $row['customer_zip'];
        return $emp;
    }
}
