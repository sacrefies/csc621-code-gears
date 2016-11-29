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

require __DIR__ . '/Persisted.php';
require __DIR__ . '/../database/DBEngine.php';

use gears\database\DBEngine;

/**
 * Class StaticEntity is a base class for the entities which have no states.
 * @package gears\models
 */
abstract class StaticEntity implements Persisted {
    /**
     * @inheritdoc
     */
    public static function getTableName() {
        return strtolower(basename(str_replace('\\', '/', get_called_class())));
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
     *                      sequence from getUpdateColumns(). The last item of this array must be the value of the
     *                      parameter $colId.
     * @param string $colId The identifier column name. The value of this column must be included as the last item of
     *                      the parameter $values.
     *
     * @return int|-1 Returns the affected row count if update is successful; otherwise -1.
     */
    protected static function updateTable(string $colId, array $values): int {
        $cols = '';
        $table = static::getTableName();
        $columns = static::getUpdateColumns();
        foreach ($columns as $col) {
            $cols = "$cols $col = ?,";
        }
        $cols = rtrim($cols);
        $sql = "UPDATE $table SET $cols WHERE $colId = ?";
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
        $c = count(self::getUpdateColumns());
        $vals = '';
        for ($id = 0; $id < $c; $id++) {
            $vals = "$vals ?,";
        }
        $vals = rtrim($vals, ',');
        $cols = implode(',', self::getUpdateColumns());
        $table = self::getTableName();
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
}
