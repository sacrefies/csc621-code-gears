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
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
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
                    $invoice->appt = Appointment::getInstance(2);
                    $invoice->amtDue = 20.00;
                    $invoice->update();*/
                    $invoices = CheckoutController::getAllPendingInvoices();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Invoice ID</th>";
                    echo "<th>Customer</th>";
                    echo "<th>Subject</th>";
                    echo "<th>Time Created</th>";
                    echo "<th>Last Updated</th>";
                    echo "<th>Total Cost</th>";
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
                        echo "<td>" . $invoice->createTime->format('Y-m-d H:i:s') . "</td>";
                        echo "<td>" . $invoice->updateTime->format('Y-m-d H:i:s') . "</td>";
                        echo "<td>" . $invoice->amtDue . "</td>";
                        echo "<td>" . $invoice->amtPayed . "</td>";
                        echo "<td>" . $invoice->discRate . "</td>";
                        echo "<td><button class='btn btn-info btn-sm' data-toggle='modal' data-target='#amtEnter' id='$id'>Update</button></td>";
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
                    echo "<th>Total Cost</th>";
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
                        echo "<td>" . $invoice->createTime->format('Y-m-d H:i:s') . "</td>";
                        echo "<td>" . $invoice->updateTime->format('Y-m-d H:i:s') . "</td>";
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
<div id="amtEnter" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title edit-content">Enter Amount</h4>
            </div>
            <div class="modal-body">
                <label for="payAmt">Enter Amount Payed:</label>
		        <input name="payAmt" type="number" id="payAmt" class="form-control" placeholder="Amount Payed"
		                       required autofocus pattern="^[0-9]$"/>
		        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="update()">Update</button>
		        <input name="invId" id="invId" type="number" lass="form-control" style="visibility:hidden;"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<p id="output"></p>
<script type="text/javascript">
	$(document).ready(function(){
		$('#amtEnter').on('show.bs.modal', function(e) {
			var $modal = $(this);
	  		var id = e.relatedTarget.id;
	  		$modal.find('.edit-content').html("Update Payment for Invoice " + id);
	  		document.getElementById("invId").value = id;
		});
	});

	function update() {
        var id = document.getElementById("invId").value;
        var amt = document.getElementById("payAmt").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
        	if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
                //document.getElementById("output").innerHTML = data;
                location.reload();
            }
        }//end onreadystatechange
        var link = "invUpdate.php?id=" + id + "&amt=" + amt;
        console.log(link);
        xhttp.open("GET", link, true);
        xhttp.send();
    }
</script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>