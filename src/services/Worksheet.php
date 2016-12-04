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
require_once __DIR__ . '/Job.php';
require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/../database/DBEngine.php';

use gears\models\Persisted;
use gears\models\StaticEntity;
use gears\database\DBEngine;


/**
 * Class Worksheet
 * @package gears\services
 */
class Worksheet extends StaticEntity {
    /**
     * @var Job The job object with which this Worksheet associates.
     */
    public $job;
    /**
     * @var int Mileage
     */
    public $mileage;
    /**
     * @var \DateTime
     */
    public $startTime;
    /**
     * @var \DateTime
     */
    public $endTime;

    /**
     * Worksheet constructor.
     */
    protected function __construct() {
        $this->job = null;
        $this->startTime = new \DateTime('1970-01-01 00:00:00');
        $this->endTime = new \DateTime('1970-01-01 00:00:00');
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        if (!$this->job) {
            error_log('This worksheet has no associated Job object.');
            return -1;
        }
        // ['job_id', 'vehicle_mileage', 'start_time', 'end_time'];
        $values = [$this->job->jobId, $this->mileage, $this->startTime->format(DATE_ISO8601),
            $this->endTime->format(DATE_ISO8601)];
        // check if there is one worksheet existing already
        if (!$this->job->getWorksheet()) {
            return $this->insert($values);
        }

        // this entity requires different way to update other than the general updateTable()
        $cols = self::getUpdateColumns();
        // remove job_id from update columns
        unset($cols[0]);
        // move job_id values to the end of $values
        unset($values[0]);
        $values[] = $this->job->jobId;
        $where = 'job_id = ?';
        $table = self::getTableName();

        foreach ($cols as $col) {
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
        $where = 'job_id = ?';
        $value = [$this->job->jobId];
        return $this->delete($where, $value);
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Worksheet Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() : Worksheet {
        $sh = new Worksheet();
        $sh->job = $this->job;
        $sh->mileage = $this->mileage;
        $sh->startTime = $this->startTime;
        $sh->endTime = $this->endTime;
        return $sh;
    }

    /**
     * Get the tasks generated by this worksheet.
     * @return array Returns an array of Task objects; Returns an empty array if there is not task.
     */
    public function getTasks() : array {
        $where = 'worksheet_job_id = ?';
        $values = [$this->job->jobId];
        return Task::getList($where, $values);
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Worksheet Returns a new in-memory object of this entity.
     */
    public static function createNew() : Worksheet {
        return new Worksheet();
    }

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Worksheet Returns a new object which is an in-memory copy of $persisted.
     * @throws \LogicException Not implemented yet.
     */
    public static function copyFrom(Persisted $persisted) : Worksheet {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Worksheet|null Returns an instance of this entity.
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
        return ['job_id', 'vehicle_mileage', 'start_time', 'end_time'];
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
     * @return Worksheet An instance of this entity.
     */
    protected static function createInstanceFromRow(array $row) : Worksheet {
        // ['job_id', 'vehicle_mileage', 'start_time', 'end_time'];
        $sh = new Worksheet();
        $sh->job = Job::getInstance((int)$row['job_id']);
        $sh->endTime = new \DateTime($row['end_time']);
        $sh->startTime = new \DateTime($row['start_time']);
        $sh->mileage = (int)$row['vehicle_mileage'];
        return $sh;
    }
}
