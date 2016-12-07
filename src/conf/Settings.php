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
namespace gears\conf;


/**
 * Class Settings is for global static configuration key-value pairs.
 * @package gears\conf
 */
final class Settings {
    /**
     * Settings constructor. Seals this class.
     */
    private function __construct() {
    }

    public static $DB_NAME = 'gears';
    public static $DB_DRIVER = 'mysql';
    public static $DB_HOST = 'localhost';
    public static $DB_PORT = '3306';
    public static $DB_USER = '';
    public static $DB_PASSWORD = '';

    /**
     * Construct a database connection string with the static setting values.
     * @return string The database connection string.
     */
    public static function getDBConnString() {
        return self::$DB_DRIVER . ':host=' . self::$DB_HOST . ':' . self::$DB_PORT . ';dbname=' . self::$DB_NAME;
    }

    public static $CURR_USER_SESS_KEY = 'current_user_object';
    /**
     * @var int Site session time out duration. 1800 === 30 minutes
     */
    public static $SESSION_TIMEOUT = 1800;

    public static $MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Get the default time zone for all DateTime creation/calculation
     * @return \DateTimeZone The default time zone
     */
    public static function timeZone() : \DateTimeZone {
        return new \DateTimeZone('America/New_York');
    }
}
