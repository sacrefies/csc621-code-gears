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
require_once __DIR__ . '/Invoice.php';
require_once __DIR__ . '/../appointments/Appointment.php';

use gears\Controller;
use gears\conf\Settings;
use gears\models\State;
use gears\appointments\Appointment;


/**
 * Class CheckoutController is a controller for checkout relevant businesses.
 * @package gears\accounts
 */
final class CheckoutController {
    use Controller;

    /**
     * Get an array of all pending Invoices.
     *
     *
     * @return array Returns an array of all pending Invoices.
     */
    static public function getAllPendingInvoices():array {
        $invoices = Invoice::getAll();
        $pending = array();
        foreach($invoices as $invoice) {
            if($invoice->getState() === STATE::PENDING) {
                $pending[] = $invoice;
            }
        }
        return $pending;
    }

    /**
     * Get an array of all payed Invoices.
     *
     *
     * @return array Returns an array of all payed Invoices.
     */
    static public function getAllPayedInvoices():array {
        $invoices = Invoice::getAll();
        $payed = array();
        foreach($invoices as $invoice) {
            if($invoice->getState() === STATE::PAYED) {
                $payed[] = $invoice;
            }
        }
        return $payed;
    }

    /**
     * Get an array of all invoicing Appointments.
     *
     *
     * @return array Returns an array of all invoicing Appointments.
     */
    static public function getInvAppointments():array {
        $appts = Appointment::getAll();
        $invoicing = array();
        foreach($appts as $appt) {
            if($appt->getState() === STATE::INVOICING) {
                $invoicing[] = $appt;
            }
        }
        return $invoicing;
    }


    /**
     * Update the amount payed and update time of the invoice
     *
     * @param int $id The invoice's id.
     *
     * @param float $amt The amount to add to amount payed.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    static public function updateInvoice(int $id, float $amt) {
        $inv = self::getInvoiceByID($id);
        $inv->amtPayed = $inv->amtPayed + $amt;
        return $inv->updatePay();    
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
        return Invoice::getInstance($id);
    }
}
