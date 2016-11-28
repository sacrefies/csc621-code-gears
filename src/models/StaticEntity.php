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

require 'Persisted.php';
require "{$_SERVER['SITEROOT']}/database/DBEngine.php";

use gears\database\DBEngine;


/**
 * Class StaticEntity is a base class for the entities which have no states.
 * @package gears\models
 */
abstract class StaticEntity implements Persisted {

    /**
     * @var DBEngine|null The database engine.
     */
    protected $dbEngine;
    /**
     * @var string|null The database table name of this entity.
     */
    protected $table;
    /**
     * @var array|null The column names of a data row of this entity.
     */
    protected static $COLUMNS;
    /**
     * @var array|null The column names for update of this entity.
     */
    protected static $COLUMNS_UPDATE;

    /**
     * Get the table name of this entity.
     * @return null|string The table's name.
     */
    public function getTable() {
        return $this->table;
    }
}
