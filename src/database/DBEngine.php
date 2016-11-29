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
namespace gears\database;

require "{$_SERVER['SITEROOT']}/conf/Settings.php";

use gears\conf\Settings;
use RuntimeException;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class DBEngine for all database directives. The query/exec methods all build PDOStatment internally.
 * @package gears\database
 */
class DBEngine {
    /**
     * @var string The database connection string.
     */
    private $connStr;
    /**
     * @var string The database valid user name
     */
    private $user;
    /**
     * @var string The password of $user.
     */
    private $pass;
    /**
     * @var PDO The current database connection object.
     */
    private $conn;
    /**
     * @var PDOStatement The statement most recently run.
     */
    private $stmt;
    /**
     * @var DBEngine The internal instance of DBEngine
     */
    private static $instance;

    /**
     * DBEngine constructor.
     */
    private function __construct() {
        $this->connStr = Settings::getDBConnString();
        $this->user = Settings::$DB_USER;
        $this->pass = Settings::$DB_PASSWORD;
    }

    /**
     * Nobody clones this instance.
     */
    private function __clone() {
    }

    /**
     * Singleton initialization method to get an instance of class DBEngine.
     * @return DBEngine
     */
    public static function getInstance() {
        if (null !== self::$instance) {
            return self::$instance;
        }
        self::$instance = new DBEngine();
        return self::$instance;
    }

    /**
     * Connect to the database.
     * @throws RuntimeException When the connection cannot be established.
     */
    public function open() {
        if (null !== $this->conn) {
            return;
        }

        try {
            $this->conn = new PDO($this->connStr, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException('Bad database connection', $e);
        }
    }

    /**
     * Close the current database connection.
     */
    public function close() {
        try {
            if (null !== $this->stmt) {
                $this->stmt->closeCursor();
                $this->stmt = null;
            }
        } catch (PDOException $e) {
            // pass
        }
        try {
            if (null !== $this->conn) {
                $this->conn = null;
            }
        } catch (PDOException $e) {
            // pass
        }
    }

    /**
     * Run a query and return the whole result set.
     *
     * @param string $sql        The sql string with parameter placeholders.
     * @param array $params An array of parameters. If omitted, $sql will be directly executed.
     *
     * @return array|null The result set of the query or null.
     */
    public function fetchAll(string $sql, array $params = array()) {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->closeCursor();
            if ($params) {
                $this->stmt->execute();
            } else {
                $this->stmt->execute($params);
            }
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->stmt = null;
            return null;
        }
    }

    /**
     * Run the sql query but return the prepared statement (already run) instead of the result set.
     *
     * @param string $sql   The sql string with parameter placeholders.
     * @param array $params An array of parameters. If omitted, $sql will be directly executed.
     *
     * @return null|PDOStatement
     */
    public function query(string $sql, array $params = array()) {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->closeCursor();
            if ($params) {
                $this->stmt->execute();
            } else {
                $this->stmt->execute($params);
            }
        } catch (PDOException $e) {
            $this->stmt = null;
        }
        return $this->stmt;
    }

    /**
     * Execute a sql command for UPDATE/INSERT or other commands without a result set.
     *
     * @param string $sql        The sql string with parameter placeholders.
     * @param array $params An array of parameters. If omitted, $sql will be directly executed.
     *
     * @return int|-1 The affected row count.
     */
    public function execute(string $sql, array $params = array()) {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->closeCursor();
            if ($params) {
                $this->stmt->execute();
            } else {
                $this->stmt->execute($params);
            }
            return $this->stmt->rowCount();
        } catch (PDOException $e) {
            return -1;
        }
    }
}
