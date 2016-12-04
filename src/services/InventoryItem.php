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

require_once __DIR__ . '/../models/StaticEntity.php';
require_once __DIR__ . '/../models/Persisted.php';
require_once __DIR__ . '/../accounts/ConventionVehicle.php';
require_once __DIR__ . '/../database/DBEngine.php';

use gears\accounts\ConventionVehicle;
use gears\database\DBEngine;
use gears\models\Persisted;
use gears\models\StaticEntity;


/**
 * Class InventoryItem
 * @package gears\services
 */
class InventoryItem extends StaticEntity {
    /**
     * @var int The unique id of an inventory item.
     */
    public $itemId;
    /**
     * @var ConventionVehicle The conventional vehicle for which this item is.
     */
    public $vehicle;
    /**
     * @var string item code
     */
    public $code;
    /**
     * @var string part name
     */
    public $part;
    /**
     * @var string unit
     */
    public $unit;
    /**
     * @var double unit price
     */
    public $unitPrice;
    /**
     * @var string category: PARTS|LABOR
     */
    public $category;

    /**
     * InventoryItem constructor.
     */
    protected function __construct() {
        $this->vehicle = null;
        $this->itemId = -1;
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        if (!$this->vehicle) {
            error_log('Conventional Vehicle for this inventory item is not set.');
            return -1;
        }
        // ['convention_vehicle_id', 'item_code', 'part_name', 'category', 'unit', 'unit_price'];
        $values = [$this->vehicle->vehicleId, $this->code, $this->part, $this->category, $this->unit, $this->unitPrice];
        if ($this->itemId === -1) {
            return $this->insert($values);
        }
        $values[] = $this->itemId;
        $where = 'item_id = ?';
        return $this->updateTable($where, $values);
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'item_id = ?';
        $values = [$this->itemId];
        return $this->delete($where, $values);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return InventoryItem Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() : InventoryItem {
        $item = new InventoryItem();
        $item->vehicle = $this->vehicle;
        $item->code = $this->code;
        $item->part = $this->part;
        $item->unitPrice = $this->unitPrice;
        $item->unit = $this->unit;
        $item->category = $this->category;
    }

    /**
     * Get all tasks which used or are using this inventory item.
     * @return array An array of Task objects.
     */
    public function getTasks() : array {
        $where = 'inventory_item_id = ?';
        $values = [$this->itemId];
        $order = 'worksheet_job_id DESC, finish_time DESC';
        return Task::getList($where, $values, $order);
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return InventoryItem Returns a new object which is an in-memory copy of $persisted.
     * @throws \BadFunctionCallException Not implmented yet
     */
    public static function copyFrom(Persisted $persisted) : InventoryItem {
        throw new \BadFunctionCallException('Not implemented yet');
    }

    /**
     * Create a new instance of this entity.
     *
     * @return InventoryItem Returns a new in-memory object of this entity.
     */
    public static function createNew() : InventoryItem {
        return new InventoryItem();
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return InventoryItem|null Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        $cols = implode(',', self::getColumns());
        $table = self::getTableName();
        $sql = "SELECT $cols FROM $table WHERE item_id = :id";
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
        return ['item_id', 'convention_vehicle_id', 'item_code', 'part_name', 'category', 'unit', 'unit_price'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return ['convention_vehicle_id', 'item_code', 'part_name', 'category', 'unit', 'unit_price'];
    }

    /**
     * Create an instance of this entity from a database row.
     *
     * @param array $row The data row in the database.
     *
     * @return Persisted An instance of this entity.
     * @throws \LogicException This method in StaticEntity class is not implemented.
     */
    protected static function createInstanceFromRow(array $row) : InventoryItem {
        $item = new InventoryItem();
        $item->itemId = (int)$row['item_id'];
        $item->vehicle = ConventionVehicle::getInstance((int)$row['convention_vehicle_id']);
        $item->code = $row['item_code'];
        $item->part = $row['part_name'];
        $item->category = strtoupper($row['category']);
        $item->unit = $row['unit'];
        $item->unitPrice = (double)$row['unit_price'];
        return $item;
    }


}
