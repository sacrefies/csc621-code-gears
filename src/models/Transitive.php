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

use gears\models\Persisted;


/**
 * Interface Transitive is for the entities which have states.
 * @package gears\models
 */
interface Transitive extends Persisted {

    /**
     * Get the current object state
     *
     * @return int The current object state
     */
    public function getState() : int;

    /**
     * Set the specified state to the current object.
     *
     * @param int $state The next state of the current object
     */
    public function setState(int $state);

    /**
     * Check whether this object is at an finishing state.
     * @return bool Returns true if this object is at an finishing state.
     */
    public function isFinished() : bool;
}
