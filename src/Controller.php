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
}
