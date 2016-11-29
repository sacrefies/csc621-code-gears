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

require_once __DIR__ . '/State.php';
require_once __DIR__ . '/Transitive.php';
require_once __DIR__.'/StaticEntity.php';


/**
 * Class StatefulEntity is a base class for the entities which have states.
 * @package gears\models
 */
abstract class StatefulEntity extends StaticEntity implements Transitive {

    /**
     * @var int The entity's current state.
     */
    protected $state = State::NEW;

    /**
     * Get the current object state
     *
     * @return int The current object state
     */
    public function getState() : int {
        return $this->state;
    }

    /**
     * Set the specified state to the current object.
     *
     * @param int $state The next state of the current object
     *
     * @throws InvalidArgumentException
     */
    public function setState(int $state) {
        // validate first
        if (null === State::getName($state)) {
            throw new InvalidArgumentException('An Invalid entity state is given');
        }
        $this->state = $state;
    }

    /**
     * Check whether this object is at an finishing state.
     * @return bool Returns true if this object is at an finishing state.
     */
    public function isFinished() : bool {
        return ($this->state === State::CANCELLED || $this->state === State::DONE);
    }
}
