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
 * Class State is a sealed value object which provides a set of constants which are entity's status.
 * The states are:
 * <pre><code>
 *  1    NEW
 *  2    INSERVICE
 *  3    DONE
 *  4    CANCELLED
 *  5    INSPECTING
 *  6    ONGOING
 *  7    AVAILABLE
 *  8    BUSY
 *  9    PENDING
 * 10    PAYED
 * 11    INVOICING
 * </code></pre>
 * @package gears\models
 */
abstract class State {
    /**
     * Newly created (Appointment, Job)
     */
    const NEW = 1;
    /**
     * Currently being serviced (Appointment)
     */
    const INSERVICE = 2;
    /**
     * Process is done (Appointment, Job)
     */
    const DONE = 3;
    /**
     * Process is cancelled (Appointment, Job)
     */
    const CANCELLED = 4;
    /**
     * The vehicle is under inspection (Job)
     */
    const INSPECTING = 5;
    /**
     * The service is ongoing (Job)
     */
    const ONGOING = 6;
    /**
     * The mechanic is available (Employee)
     */
    const AVAILABLE = 7;
    /**
     * The mechanic is busy (Employee)
     */
    const BUSY = 8;
    /**
     * Newly created invoice (Invoice)
     */
    const PENDING = 9;
    /**
     * The invoice is payed (Invoice)
     */
    const PAYED = 10;
    /**
     * the payment is ongoing (Appointment)
     */
    const INVOICING = 11;

    private static $DICT = ['NEW', 'INSERVICE', 'DONE', 'CANCELLED', 'INSPECTING',
        'ONGOING', 'AVAILABLE', 'BUSY', 'PENDING', 'PAYED', 'INVOICING'];

    /**
     * Get the name of a state value.
     *
     * @param int $state An integer which represents a state.
     *
     * @return string|null Returns the name of the given state value.
     */
    public static function getName(int $state) {
        if ($state < self::NEW || $state > self::INVOICING) {
            return null;
        }
        return self::$DICT[$state - 1];
    }

    /**
     * Get the state's value from it's name.
     *
     * @param string $name The name of the state.
     *
     * @return int|-1 Returns the value of the name; returns -1 if the given name is invalid.
     */
    public static function getState(string $name) {
        $index = array_search(strtoupper($name), self::$DICT, true);
        return ($index ? $index - 1: -1);
    }
}
