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
namespace gears\checkout;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/invoice.php';

use gears\Controller;
use gears\conf\Settings;


/**
 * Class CheckoutController is a controller for checkout relevant businesses.
 * @package gears\accounts
 */
final class CheckoutController {
    use Controller;

    /**
     * Get an instance of Invoice by its invoice id.
     *
     * @param string $id The invoice's id.
     *
     * @return Invoice|null Returns an instance of Invoice if the id exists in the database.
     */
    static public function getAllInvoices():array {
        return invoice::getAll();
    }

    static public function updateInvoice(int $id, float $amt) {
        $inv = self::getInvoiceByID($id);
        if($inv->amtDue <= 0){
            return 0;
        }
        else {
            $inv->amtPayed = $inv->amtPayed + $amt;
            $return = $inv->calcAmtDue();
            return $return;
        }
    }

    /**
     * Get an instance of Invoice by its invoice id.
     *
     * @param string $id The invoice's id.
     *
     * @return Invoice|null Returns an instance of Invoice if the id exists in the database.
     */
    static public function getInvoiceById(int $id) : Invoice {
        if (0 > $id) {
            return null;
        }
        return invoice::getInstance($id);
    }
}
