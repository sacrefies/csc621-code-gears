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

require_once __DIR__ . '/checkout/CheckoutController.php';
require_once __DIR__ . '/checkout/invoice.php';

/**
 * @var string A string variable to set the page title.
 */
$title = 'Checkout';
/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Checkout';
/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout
 */
$activeMenu = 3;

//include __DIR__ . '/header.php';
?>
<!-- main content starts here -->
<?php

$invoice = invoice::createNew();

$invoice->apptId = 1;
$invoice->update();

$invoices = CheckoutController::getAllInvoices();

echo "<table align='center'>";
echo "<tr>";
echo "<th>Invoice ID</th>";
echo "<th>Appointment ID</th>";
echo "</tr>";
echo "<tr>";
echo "<td>" . $invoices[0]->invoiceId . "</td>";
echo "<td>" . $invoices[0]->apptId . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . $invoices[1]->invoiceId . "</td>";
echo "<td>" . $invoices[1]->apptId . "</td>";
echo "</tr>";
echo "</table>";




?>












<?php include __DIR__.'/footer.php'; ?>
