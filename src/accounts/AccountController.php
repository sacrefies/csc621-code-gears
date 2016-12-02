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
namespace gears\accounts;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/Employee.php';
require_once __DIR__ . '/CustomerVehicle.php';
require_once __DIR__ . '/Customer.php';

use gears\Controller;
use gears\conf\Settings;


/**
 * Class AccountController is a controller for account relevant businesses.
 * @package gears\accounts
 */
final class AccountController {
    use Controller;

    /**
     * Check whether the current accessor is a login user.
     * @return bool Returns true if the user has login; otherwise false.
     */
    static public function isLogin():bool {
        if (!isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return (!self::isSessionExpired() && isset($_SESSION[Settings::$CURR_USER_SESS_KEY])
            && !empty($_SESSION[Settings::$CURR_USER_SESS_KEY]));
    }

    /**
     * Logout the current user.
     */
    static public function logout() {
        self::destroySession();
        self::redirectTo('/login.php');
    }

    /**
     * Perform a user login process. The current login user will be stored in $_SESSION.
     *
     * @param string $empCode The employee's code
     *
     * @return bool Returns true if login is successful; otherwise false.
     */
    static public function login(string $empCode):bool {
        if ($empCode) {
            session_start();
            // get employee
            $emp = self::getEmployeeByCode($empCode);
            if ($emp) {
                $_SESSION[Settings::$CURR_USER_SESS_KEY] = $emp;
                return true;
            }
        }
        return false;
    }

    /**
     * Assign a job to a mechanic
     *
     * @param int $empId
     * @param int $jobId
     *
     * @return bool
     */
    static public function assignJob(int $empId, int $jobId):bool {

    }

    /**
     * Get an instance of Employee by its employee code.
     *
     * @param string $empCode The employee's code to login with.
     *
     * @return Employee|null Returns an instance of Employee if the code exists in the database.
     */
    static public function getEmployeeByCode(string $empCode) {
        if (!$empCode) {
            return null;
        }
        return Employee::getInstanceFromKeys('emp_code=?', array($empCode));
    }

    /**
     * Get an instance of Employee by its employee code.
     *
     * @param int $id The employee's unique id;
     *
     * @return Employee|null Returns an instance of Employee if the id exists in the database.
     */
    static public function getEmployeeById(int $id) {
        if (0 > $id) {
            return null;
        }
        return Employee::getInstance($id);
    }

    /**
     * Get all employees
     * @return array An array of Employee objects
     */
    static public function getAllEmployees() : array {
        return Employee::getAll('state');
    }

    /**
     * Get all vehicles which are owned by the specified customer.
     *
     * @param int $customerId The unique id of the customer.
     *
     * @return array An array of CustomerVehicle objects.
     */
    static public function getCustomerVehiclesByCustomer(int $customerId) : array {
        if ($customerId < 0) {
            return [];
        }
        $where = 'customer_id = ?';
        $values = [$customerId];
        return CustomerVehicle::getList($where, $values);
    }


    public static function getAllCustomerVehicles() : array {
        $order = 'customer_id, customer_vehicle_id';
        return CustomerVehicle::getAll();
    }

    /**
     * Get all customers
     * @return array An array of Customer objects
     */
    static public function getAllCustomers(): array {
        return Customer::getAll();
    }

    /**
     * @param string|null $firstName
     * @param string|null $lastName
     *
     * @return array
     */
    static public function getCustomersByName(string $firstName = null, string $lastName = null) : array {
        $where = '';
        $values = [];
        if ($firstName) {
            $where = 'first_name = ?';
            $values[] = $firstName;
        }
        if ($lastName) {
            if ($where) {
                $where .= ' AND last_name = ?';
            } else {
                $where = 'last_name = ?';
            }
            $values[] = $firstName;
        }
        if (!$firstName && !$lastName) {
            return [];
        }
        return Customer::getList($where, $values);
    }

    /**
     * @param int $id
     *
     * @return Customer|null
     */
    public static function getCustomerById(int $id) {
        if (0 > $id) {
            return null;
        }
        return Customer::getInstance($id);
    }
}
