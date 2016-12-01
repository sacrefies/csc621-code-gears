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
namespace gears\models;

require_once __DIR__ . '/Persisted.php';
require_once __DIR__ . '/../database/DBEngine.php';

use gears\database\DBEngine;

/**
 * Class StaticEntity is a base class for the entities which have no states.
 * @package gears\models
 */
abstract class StaticEntity implements Persisted {
    /**
     * @inheritdoc
     */
    public static function getTableName():string {
        return strtolower(basename(str_replace('\\', '/', get_called_class())));
    }


    /**
     * Get a list of instances of this entity
     *
     * @param string $where   The where clause to identify the row(s). This clause must be constructed with
     *                        parameter placeholders: '?', e.g.: 'emp_id = ? AND emp_code = ?'
     * @param array $values   An array of the column values which are involved by $where. The sequence of the items must
     *                        be align with the column sequence in $where.
     * @param string $orderBy columns to order
     *
     * @return array A list of instances of this entity.
     */
    public static function getList(string $where, array $values, string $orderBy = null) : array {
        $cols = implode(',', static::getColumns());
        $table = static::getTableName();
        $sql = "SELECT $cols FROM $table WHERE $where";
        if ($orderBy) {
            $sql = "$sql ORDER BY $orderBy";
        }
        $entities = array();
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return $entities;
        }
        $rows = $db->fetchAll($sql, $values);
        $db->close();
        if ($rows) {
            foreach ($rows as $row) {
                $entities[] = static::createInstanceFromRow($row);
            }
        }
        return $entities;
    }

    /**
     * Get all existing instances from the database.
     *
     * @param string $orderBy columns to order
     *
     * @return array A list of instances of this entity.
     */
    public static function getAll(string $orderBy = null) : array {
        $cols = implode(',', static::getColumns());
        $table = static::getTableName();
        $sql = "SELECT $cols FROM $table";
        if ($orderBy) {
            $sql = "$sql ORDER BY $orderBy";
        }
        $entities = array();
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return $entities;
        }
        $rows = $db->fetchAll($sql);
        $db->close();
        if ($rows) {
            foreach ($rows as $row) {
                $entities[] = static::createInstanceFromRow($row);
            }
        }
        return $entities;
    }

    /**
     * Create an instance of this entity from a database row.
     *
     * @param array $row The data row in the database.
     *
     * @return Persisted An instance of this entity.
     * @throws \LogicException This method in StaticEntity class is not implemented.
     */
    protected static function createInstanceFromRow(array $row) {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Update the data row in the database which links to this object.
     *
     * @param array $values An array of the column values. The sequence of the items must be align with the column
     *                      sequence from getUpdateColumns(). The last item(s) of this array must be the value of the
     *                      parameter $where.
     * @param string $where The where clause to identify the row. This clause must be constructed with
     *                      parameter placeholders: '?', and the value of the columns involved must be included as the
     *                      last item(s) of the parameter $values.
     *
     * @return int|-1 Returns the affected row count if update is successful; otherwise -1.
     */
    protected function updateTable(string $where, array $values): int {
        $cols = '';
        $table = static::getTableName();
        $columns = static::getUpdateColumns();
        foreach ($columns as $col) {
            $cols = "$cols $col = ?,";
        }
        $cols = rtrim($cols, ',');
        $sql = "UPDATE $table SET $cols WHERE $where";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return -1;
        }
        $rc = $db->execute($sql, $values);
        $db->close();
        return $rc;
    }

    /**
     * Insert a new row which represents this instance into the table of this entity.
     *
     * @param array $values An array of the column values. The sequence of the items must be align with the column
     *                      sequence from getUpdateColumns().
     *
     * @return int Returns the affected row count if update is successful; otherwise -1.
     */
    protected function insert(array $values):int {
        // ['phone_number', 'first_name', 'last_name', 'is_manager', 'state', 'emp_code'];
        $c = count(static::getUpdateColumns());
        $vals = '';
        for ($id = 0; $id < $c; $id++) {
            $vals = "$vals ?,";
        }
        $vals = rtrim($vals, ',');
        $cols = implode(',', static::getUpdateColumns());
        $table = static::getTableName();
        $sql = "INSERT INTO $table ($cols) VALUES ($vals)";

        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return -1;
        }
        $rc = $db->execute($sql, $values);
        $db->close();
        return $rc;
    }

    /**
     * Delete the data row which represents this instance from the table.
     *
     * @param string $where The where clause to identify the row to be updated. This clause must be constructed with
     *                      parameter placeholders: '?', e.g.: 'emp_id = ? AND emp_code = ?'
     * @param array $values An array of the column values which are involved by $where. The sequence of the items must
     *                      be align with the column sequence in $where.
     *
     * @return int|-1 Returns the affected row count if update is successful; otherwise -1.
     */
    protected function delete(string $where, array $values): int {
        $table = static::getTableName();
        $sql = "DELETE FROM $table WHERE $where";
        $db = DBEngine::getInstance();
        try {
            $db->open();
        } catch (\Exception $e) {
            return -1;
        }
        $rc = $db->execute($sql, $values);
        $db->close();
        return $rc;
    }
}
