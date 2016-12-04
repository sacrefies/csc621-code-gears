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
     * Get an array of all invoicing Appointments that do not have an associated invoice.
     *
     *
     * @return array Returns an array of all invoicing Appointments.
     */
    static public function getInvAppointments():array {
        $appts = Appointment::getAll();
        $invoicing = array();
        foreach($appts as $appt) {
            if($appt->getState() === STATE::INVOICING) {
                if($appt->getInvoice() === null){
                    $invoicing[] = $appt;
                }
            }
        }
        return $invoicing;
    }

    /**
     * Create a new invoice
     *
     * @param int $id The appointment's id.
     *
     * @param float $disc The discount to be applied.
     *
     * @param float $amt The amount to add to amount payed.
     *
     * @return int Returns 1 if update is successful; otherwise 0.
     */
    static public function createInvoice(int $apptId, float $disc, float $amt) {
        $inv = Invoice::createNew();
        $inv->appt = Appointment::getInstance($apptId);
        $inv->discRate = $disc;
        $amtDue = self::getAmtDue($inv->appt, $disc);
        if($disc == 1){
            $inv->amtPayed = $amtDue;
        }
        else{
            $inv->amtPayed = $amt;
        }
        $inv->amtDue = $amtDue;
        return $inv->updatePay();
    }

    /**
     * Calculate the amount due for the invoice
     *
     * @return float Returns the total amt due.
     */
    static public function getAmtDue(Appointment $appt, float $disc) {
        $job = $appt->getJob();
        $worksheet = $job->getWorksheet();
        $tasks = $worksheet->getTasks();
        $amtDue = 0;

        foreach($tasks as $task){
            $amtDue += $task->cost;
        }
        //$amtDue = 20;
        $taxRate = 0.06;

        if($disc == 0) {
            $amtOff = 0;
        }
        else {
            $amtOff = $amtDue * $disc;
        }
        $amtDue = $amtDue - $amtOff;
        $tax = $amtDue * $taxRate;
        $due = round(($amtDue + $tax), 2);
        $amtDue = floatval($due);

        return $amtDue;

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
