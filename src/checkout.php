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

include __DIR__ . '/header.php';
?>
<!-- main content starts here -->
<body>
	<script type="text/javascript">
		function popUp(id){
				var amt = prompt("Enter amount payed:");
				while(!isNumeric(amt))
   				{
     				var amt = prompt("Enter amount payed (number only):");
   				}
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange=function(){
					if (xhttp.readyState==4 && xhttp.status==200){
						var data = xhttp.responseText;
						//document.getElementById("output").innerHTML = data;
						location.reload();
					}
				}//end onreadystatechange
				
				var link = "invUpdate.php?id="+id+"&amt="+amt;

				xhttp.open("GET", link, true);
				xhttp.send();
		}
		function isNumeric(n) {
  			return !isNaN(parseFloat(n)) && isFinite(n);
		}
	</script>
	<h1 class="page-header">Pending Invoices</h1>
<?php

/*$invoice = invoice::createNew();

$invoice->apptId = 1;
$invoice->update();*/

$invoices = CheckoutController::getAllInvoices();

echo "<table class='table'>";
echo "<tr>";
echo "<th>Invoice ID</th>";
echo "<th>Appointment ID</th>";
echo "<th>Time Created</th>";
echo "<th>Last Updated</th>";
echo "<th>Amount Due</th>";
echo "<th>Amount Payed</th>";
echo "<th>Discount</th>";
echo "</tr>";
foreach($invoices as $invoice) {
	$id = $invoice->invoiceId;
	echo "<tr>";
	echo "<td>" . $id . "</td>";
	echo "<td>" . $invoice->apptId . "</td>";
	echo "<td>" . $invoice->createTime . "</td>";
	echo "<td>" . $invoice->updateTime . "</td>";
	echo "<td>" . $invoice->amtDue . "</td>";
	echo "<td>" . $invoice->amtPayed . "</td>";
	echo "<td>" . $invoice->discRate . "</td>";
	echo "<td><button class='button' onclick=popUp($id)>Update</button></td>";
	echo "</tr>";
}
	
echo "</table>";
?>
<p id="output"></p>
</body>












<?php include __DIR__.'/footer.php'; ?>
