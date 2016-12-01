<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/1/2016
 * Time: 00:58
 */


declare(strict_types=1);
namespace gears\accounts;

require_once __DIR__.'/../models/StaticEntity.php';
require_once __DIR__.'/../database/DBEngine.php';


use gears\models\Persisted;
use gears\models\StaticEntity;

class Customer extends StaticEntity {

    /**
     * Update the data row in the database which links to this object.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    public function update() : int {
        // TODO: Implement update() method.
    }

    /**
     * Delete the data row in the database which links to this object.
     *
     * @return int Returns 1 if removal is successful; otherwise 0.
     */
    public function remove() : int {
        // TODO: Implement remove() method.
    }

    /**
     * Make a copy of this object. The new copy is a brand new entity which does not exist in the database yet.
     * To save the new copy, invoke update() method.
     *
     * @return Persisted Returns a new object which is an in-memory copy of this object.
     * @see Persisted::update()
     */
    public function copy() {
        // TODO: Implement copy() method.
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
        // TODO: Implement copyFrom() method.
    }

    /**
     * Create a new instance of this entity.
     *
     * @return Persisted Returns a new in-memory object of this entity.
     */
    public static function createNew() {
        // TODO: Implement createNew() method.
    }

    /**
     * Create and initialize a new instance of this entity from the database.
     *
     * @param int $id The unique of the data row in the database table.
     *
     * @return Persisted Returns an instance of this entity.
     */
    public static function getInstance(int $id) {
        // TODO: Implement getInstance() method.
    }

    /**
     * Get the column name of the table of this entity.
     * @return array Returns this entity's table column names
     */
    public static function getColumns() : array {
        // TODO: Implement getColumns() method.
    }

    /**
     * Get the column names for UPDATE/INSERT SQL.
     * @return array Returns the column names for update/insertion
     */
    public static function getUpdateColumns() : array {
        // TODO: Implement getUpdateColumns() method.
    }
}
