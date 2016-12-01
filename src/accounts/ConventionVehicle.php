<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/1/2016
 * Time: 01:16
 */

declare(strict_types = 1);
namespace gears\accounts;

require_once __DIR__ . '/../database/DBEngine.php';

use gears\database\DBEngine;
use gears\models\Persisted;
use gears\models\StaticEntity;


/**
 * Class ConventionVehicle is for standard vehicles
 * @package gears\accounts
 */
class ConventionVehicle extends StaticEntity {

    /**
     * @var int The id of this instance
     */
    public $vehicleId;
    /**
     * @var int Does this vehicle have auto-transmission
     */
    public $isAutoTrans;
    /**
     * @var int Does this vehicle has all-wheel-drive
     */
    public $isAllWheelDrive;
    /**
     * @var string The manufacturer's name
     */
    public $make;
    /**
     * @var int The year when this vehicle is on market
     */
    public $year;
    /**
     * @var string The trim of this vehicle
     */
    public $trim;
    /**
     * @var string The model of this vehicle
     */
    public $model;

    /**
     * ConventionVehicle constructor.
     */
    protected function __construct() {
        $this->vehicleId = -1;
        $this->isAutoTrans = 1;
        $this->isAllWheelDrive = 0;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        $values = [$this->isAllWheelDrive, $this->isAutoTrans, $this->year, $this->make, $this->trim, $this->model];
        if ($this->vehicleId === -1) {
            return $this->insert($values);
        } else {
            $values[] = $this->vehicleId;
            $where = 'vehicle_id = ?';
            return $this->updateTable($where, $values);
        }
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'vehicle_id = ?';
        $values = [$this->vehicleId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return ConventionVehicle Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() : ConventionVehicle {
        $cv = new ConventionVehicle();
        $cv->isAutoTrans = $this->isAutoTrans;
        $cv->isAllWheelDrive = $this->isAllWheelDrive;
        $cv->make = $this->make;
        $cv->year = $this->year;
        $cv->model = $this->model;
        $cv->trim = $this->trim;
        return $cv;
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
        return new ConventionVehicle();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Persisted Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE vehicle_id = :id";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
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
        return ['vehicle_id', 'is_all_wheel', 'is_auto_trans', 'year', 'make', 'trim', 'model'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['is_all_wheel', 'is_auto_trans', 'year', 'make', 'trim', 'model'];
    }

    /**
     * @inheritdoc
     */
    protected static function createInstanceFromRow(array $row) : ConventionVehicle {
        $emp = new ConventionVehicle();
        $emp->vehicleId = (int)$row['vehicle_id'];
        $emp->isAutoTrans = (int)$row['is_auto_trans'];
        $emp->isAllWheelDrive = (int)$row['is_all_wheel'];
        $emp->make = $row['make'];
        $emp->year = (int)$row['year'];
        $emp->trim = $row['trim'];
        $emp->model = $row['model'];
        return $emp;
    }
}
