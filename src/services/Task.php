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
require_once __DIR__ . '/InventoryItem.php';
require_once __DIR__ . '/Worksheet.php';
require_once __DIR__ . '/../database/DBEngine.php';

use gears\models\Persisted;
use gears\models\StaticEntity;
use gears\database\DBEngine;


/**
 * Class Task
 * @package gears\services
 */
class Task extends StaticEntity {

    /**
     * @var Worksheet the worksheet
     */
    public $worksheet;
    /**
     * @var InventoryItem The inventory item used by this task
     */
    public $invItem;
    /**
     * @var int The quantity of the inventory item used
     */
    public $quantity;
    /**
     * @var int 0: false, 1: true
     */
    public $isDone;
    /**
     * @var \DateTime The finish time of this task.
     */
    public $finishTime;
    /**
     * @var double The sub-total cost of this task (quantity * InventoryItem->unit_price)
     */
    public $cost;

    /**
     * Task constructor.
     */
    protected function __construct() {
        $this->worksheet = null;
        $this->invItem = null;
        $this->quantity = 0;
        $this->finishTime = new \DateTime('1970-01-01 00:00:00');
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        if (!$this->worksheet || !$this->invItem) {
            error_log('Either worksheet or inventory item of this task is null');
            return -1;
        }
        // ['inventory_item_id', 'worksheet_job_id', 'quantity', 'is_done', 'finish_time', 'amount_cost'];
        $values = [$this->invItem->itemId, $this->worksheet->job->jobId, $this->quantity, $this->isDone,
            $this->finishTime->format(DATE_ISO8601), $this->cost];
        // how do we check the existence of this task?
        if (!self::getTaskInstance($this->worksheet->job->jobId, $this->invItem->itemId)) {
            return $this->insert($values);
        }
        // this entity requires different way to update other than the general updateTable()
        // ['quantity', 'is_done', 'finish_time', 'amount_cost' | where-> 'inventory_item_id', 'worksheet_job_id', ]
        $values = array_slice($values, 2);
        $values[] = $this->invItem->itemId;
        $values[] = $this->worksheet->job->jobId;
        $where = 'inventory_item_id = ? AND worksheet_job_id = ?';
        $columns = array_slice(self::getUpdateColumns(), 2);
        $table = self::getTableName();
        $cols = '';
        foreach ($columns as $col) {
            $cols = "$cols $col = ?,";
        }
        $cols = rtrim($cols, ',');
        $sql = "UPDATE $table SET $cols WHERE $where";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            $msg = "{$e->getFile()}: Line {$e->getLine()}: {$e->getMessage()}\n{$e->getTraceAsString()}\n";
            error_log($msg);
            return -1;
        }
        $rc = $db->execute($sql, $values);
        $db->close();
        return $rc;
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        $where = 'worksheet_job_id = ? AND inventory_item_id = ?';
        $values = [$this->worksheet->job->jobId, $this->invItem->invItemId];
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
        $task = new Task();
        $task->isDone = $this->isDone;
        $task->cost = $this->cost;
        $task->finishTime = $this->finishTime;
        $task->quantity = $this->quantity;
        $task->worksheet = $this->worksheet;
        $task->invItem = $this->invItem;
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return mixed Returns a new object which is an in-memory copy of $persisted.
     * @throws \LogicException Not implemented yet
     */
    public static function copyFrom(Persisted $persisted) {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Persisted Returns a new in-memory object of this entity.
     */
    public static function createNew() {
        return new Task;
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Persisted Returns an instance of this entity.
     * @throws \BadFunctionCallException Use getTaskInstance(int, int) instead. This function is not suitable for this
     *                                   entity.
     * @see getTaskInstance(int, int)
     */
    public static function getInstance(int $id) {
        throw new \BadFunctionCallException('Use getTaskInstance(int, int) instead. This function is not suitable for this entity.');
    }

    /**
     * Create and initialize a an instance of Task entity from the database.
     *
     * @param int $sheetId         The unique id of a work sheet.
     * @param int $inventoryItemId The unique id of an inventory item.
     *
     * @return Task|null An instance of Task or null if no task is found.
     */
    public static function getTaskInstance(int $sheetId, int $inventoryItemId) {
        // 'inventory_item_id', 'worksheet_job_id',
        $where = 'worksheet_job_id = ? AND inventory_item_id = ?';
        $values = [$sheetId, $inventoryItemId];
        return self::getInstanceFromKeys($where, $values);
    }

    /**
     * Get the column name of the table of this entity.
     * @return array Returns this entity's table column names
     */
    public static function getColumns() : array {
        return ['inventory_item_id', 'worksheet_job_id', 'quantity', 'is_done', 'finish_time', 'amount_cost'];
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        return self::getColumns();
    }

    /**
     * Create an instance of this entity from a database row.
     *
     * @param array $row The data row in the database.
     *
     * @return Task An instance of this entity.
     * @throws \LogicException This method in StaticEntity class is not implemented.
     */
    protected static function createInstanceFromRow(array $row) : Task {
        // ['inventory_item_id', 'worksheet_job_id', 'quantity', 'is_done', 'finish_time', 'amount_cost'];
        $task = new Task();
        $task->worksheet = Worksheet::getInstance((int)$row['worksheet_job_id']);
        $task->invItem = InventoryItem::getInstance((int)$row['inventory_item_id']);
        $task->finishTime = new \DateTime($row['finish_time']);
        $task->cost = (double)$row['amount_cost'];
        $task->isDone = (int)$row['is_done'];
        $task->quantity = (int)$row['quantity'];
        return $task;
    }
}
