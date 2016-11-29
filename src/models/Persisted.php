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


/**
 * Interface Persisted is for all data entities.
 * @package gears\models
 */
interface Persisted {

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int;

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int;

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Persisted Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy();

    /**
     * Make a copy of the given object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @param Persisted $persisted The object to be copied.
     *
     * @return Persisted Returns a new object which is an in-memory copy of $persisted.
     */
    public static function copyFrom(Persisted $persisted);

    /**
     * Create a new instance of this entity.
     *
     * @return Persisted Returns a new in-memory object of this entity.
     */
    public static function createNew();

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Persisted Returns an instance of this entity.
     */
    public static function getInstance(int $id);

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param string $where The where clause to identify the row. This clause must be constructed with
     *                      parameter placeholders: '?', e.g.: 'emp_id = ? AND emp_code = ?'
     * @param array $values An array of the column values which are involved by $where. The sequence of the items must
     *                      be align with the column sequence in $where.
     *
     * @return Persisted Returns an instance of this entity.
     */
    public static function getInstanceFromKeys(string $where, array $values);

    /**
     * Get a list of instances of this entity
     *
     * @param string $where The where clause to identify the row(s). This clause must be constructed with
     *                      parameter placeholders: '?', e.g.: 'emp_id = ? AND emp_code = ?'
     * @param array $values An array of the column values which are involved by $where. The sequence of the items must
     *                      be align with the column sequence in $where.
     * @param string $orderBy  columns to order
     *
     * @return array A list of instances of this entity.
     */
    public static function getList(string $where, array $values, string $orderBy) : array;

    /**
     * Get all existing instances from the database.
     *
     * @param string $orderBy columns to order
     *
     * @return array A list of instances of this entity.
     */
    public static function getAll(string $orderBy = null) : array;

    /**
     * Get this entity's database table name.
     * @return string Returns the database table name.
     */
    public static function getTableName() : string;

    /**
     * Get the column name of the table of this entity.
     * @return array Returns this entity's table column names
     */
    public static function getColumns() : array;

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array;
}
