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
namespace gears;

use gears\accounts\Employee;
use gears\conf\Settings;


/**
 * Trait Controller holds a set of shared methods for every controller class.
 * @package gears
 */
trait Controller {

    /**
     * Redirect user to the destination URL.
     *
     * @param string $url The destination.
     */
    public static function redirectTo(string $url) {
        if (isset($url) && !empty($url)) {
            $url = htmlentities($url);
            header("Location: $url");
        }
        exit;
    }

    /**
     * Get the PHP script name which is currently being used.
     * @return string PHP script name
     */
    public static function getSelfScript() {
        return htmlentities($_SERVER['PHP_SELF']);
    }

    /**
     * Get an html encoded value by its key from the posted html form item set.
     *
     * @param string $key The key of the value to be encoded.
     *
     * @return string Returns an html encoded value.
     */
    public static function getHtmlFriendlyPosted(string $key) {
        if (empty($_POST[$key])) {
            return '';
        }
        return htmlentities($_POST[$key]);
    }

    /**
     * Kill current session
     */
    public static function destroySession() {
        // Initialize the session.
        // If you are using session_name("something"), don't forget it now!
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Unset all of the session variables.
        $_SESSION = array();
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // Finally, destroy the session.
        session_destroy();
    }

    /**
     * Check whether the current user login session is expired.
     * @return bool Returns true if user session is expired; otherwise false.
     */
    public static function isSessionExpired() : bool {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > Settings::$SESSION_TIMEOUT)) {
            self::destroySession();
            return true;
        }
        // update last activity time stamp
        $_SESSION['LAST_ACTIVITY'] = time();
        return false;
    }

    /**
     * Get the current user object stored in SESSION
     * @return Employee|null Returns the current logon user.
     */
    public static function getLoginUser() : Employee {
        if (null === $_SESSION || !isset($_SESSION[Settings::$CURR_USER_SESS_KEY]) || empty($_SESSION[Settings::$CURR_USER_SESS_KEY])) {
            return null;
        }
        return $_SESSION[Settings::$CURR_USER_SESS_KEY];
    }
}
