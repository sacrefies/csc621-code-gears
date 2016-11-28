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

require "{$_SERVER['SITEROOT']}/Controller.php";
require "{$_SERVER['SITEROOT']}/conf/Settings.php";

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
    static public function checkLogin() {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION[Settings::$CURR_USER_SESS_KEY]) && !empty($_SESSION[Settings::$CURR_USER_SESS_KEY]);
    }

    /**
     * Logout the current user.
     */
    static public function logout() {
        session_unset();
        session_destroy();
        session_start();
        self::redirectTo('/login.php');
    }

    /**
     * Perform a user login process. The current login user will be stored in $_SESSION.
     *
     * @param string $empCode The employee's code
     *
     * @return bool Returns true if login is successful; otherwise false.
     */
    static public function login(string $empCode) {
        if (isset($empCode) && !empty($empCode)) {
            // get employee
            $emp = self::getEmployeeByCode($empCode);
            if (isset($emp)) {
                $_SESSION[Settings::$CURR_USER_SESS_KEY] = $emp;
                return true;
            }
        }
        return false;
    }

    /**
     * Get an instance of Employee by its employee code.
     *
     * @param string $empCode The employee's code to login with.
     *
     * @return Employee|null Returns an instance of Employee if the code exists in the database.
     */
    static public function getEmployeeByCode(string $empCode) {
        return null;
    }
}
