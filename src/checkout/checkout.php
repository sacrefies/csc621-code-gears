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
require_once __DIR__ . '/CheckoutController.php';
require_once __DIR__ . '/Invoice.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../accounts/Customer.php';
use gears\appointments\Appointment;
use gears\accounts\Customer;
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
include __DIR__ . '/../header.php';
?>
<!-- main content starts here -->
<body>
<script type="text/javascript">
    function popUp(id) {
        var valid = false;
        while (valid === false) {
            var amt = prompt("Enter amount payed:");
            if (isNumeric(amt)) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var data = xhttp.responseText;
                        //document.getElementById("output").innerHTML = data;
                        location.reload();
                    }
                }//end onreadystatechange
                var link = "invUpdate.php?id=" + id + "&amt=" + amt;
                xhttp.open("GET", link, true);
                xhttp.send();
                valid = true;
            }
            else if (amt === null) {
                valid = true;
            }
            else {
                valid = false;
                alert("Please enter a valid amount");
            }
        }
    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
</script>
<div class="panel panel-default">
    <div class="panel-heading">Invoices</div>
    <div class="panel-body">
        <div role="tabpanel">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#pendingTab" role="tab"
                                                          data-toggle="tab">Pending</a></li>
                <li role="presentation"><a href="#payedTab" role="tab"
                                           data-toggle="tab">Payed</a></li>
            </ul>
            <div class="tab-content">
                <div id="pendingTab" role="tabpanel" class="tab-pane active">
                    <?php
                    /*$invoice = invoice::createNew();
                    $invoice->appt = Appointment::getInstance(1);
                    $invoice->amtDue = 20.00;
                    $invoice->updatePay();*/
                    $invoices = CheckoutController::getAllPendingInvoices();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Invoice ID</th>";
                    echo "<th>Customer</th>";
                    echo "<th>Subject</th>";
                    echo "<th>Time Created</th>";
                    echo "<th>Last Updated</th>";
                    echo "<th>Amount Due</th>";
                    echo "<th>Amount Payed</th>";
                    echo "<th>Discount</th>";
                    echo "</tr>";
                    foreach ($invoices as $invoice) {
                        $id = $invoice->invoiceId;
                        $app = $invoice->appt;
                        $subject = $app->subject;
                        $cust = Customer::getInstance($app->customer->customerId);
                        $custFirst = $cust->firstName;
                        $custLast = $cust->lastName;
                        $custName = "" . $custFirst . " " . $custLast;
                        echo "<tr>";
                        echo "<td>" . $id . "</td>";
                        echo "<td>" . $custName . "</td>";
                        echo "<td>" . $subject . "</td>";
                        echo "<td>" . $invoice->createTime . "</td>";
                        echo "<td>" . $invoice->updateTime . "</td>";
                        echo "<td>" . $invoice->amtDue . "</td>";
                        echo "<td>" . $invoice->amtPayed . "</td>";
                        echo "<td>" . $invoice->discRate . "</td>";
                        echo "<td><button class='btn btn-info btn-sm' onclick=popUp($id)>Update</button></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
                <div id="payedTab" role="tabpanel" class="tab-pane">
                    <?php
                    $invoices = CheckoutController::getAllPayedInvoices();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Invoice ID</th>";
                    echo "<th>Customer</th>";
                    echo "<th>Subject</th>";
                    echo "<th>Time Created</th>";
                    echo "<th>Time Payed</th>";
                    echo "<th>Cost</th>";
                    echo "<th>Discount</th>";
                    echo "</tr>";
                    foreach ($invoices as $invoice) {
                        $id = $invoice->invoiceId;
                        $app = $invoice->appt;
                        $subject = $app->subject;
                        $cust = Customer::getInstance($app->customer->customerId);
                        $custFirst = $cust->firstName;
                        $custLast = $cust->lastName;
                        $custName = "" . $custFirst . " " . $custLast;
                        echo "<tr>";
                        echo "<td>" . $id . "</td>";
                        echo "<td>" . $custName . "</td>";
                        echo "<td>" . $subject . "</td>";
                        echo "<td>" . $invoice->createTime . "</td>";
                        echo "<td>" . $invoice->updateTime . "</td>";
                        echo "<td>" . $invoice->amtDue . "</td>";
                        echo "<td>" . $invoice->discRate . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<p id="output"></p>
</body>
<?php include __DIR__ . '/../footer.php'; ?>