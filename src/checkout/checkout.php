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
require_once __DIR__ . '/invoice.php';

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
		//document.getElementById("defaultOpen").click();

		function popUp(id){
			var valid = false;
			while(valid === false)
   			{
     			var amt = prompt("Enter amount payed:");
     			if(isNumeric(amt)){
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
     				valid = true;
     			}
     			else if(amt === null){
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

		/*function openTab(evt, tab) {
    		// Declare all variables
    		var i, tabcontent, tablinks;

    		// Get all elements with class="tabcontent" and hide them
    		tabcontent = document.getElementsByClassName("tabcontent");
    		for (i = 0; i < tabcontent.length; i++) {
        		tabcontent[i].style.display = "none";
    		}

    		// Get all elements with class="tablinks" and remove the class "active"
    		tablinks = document.getElementsByClassName("tablinks");
    		for (i = 0; i < tablinks.length; i++) {
        		tablinks[i].className = tablinks[i].className.replace("active", "");
    		}

    		// Show the current tab, and add an "active" class to the link that opened the tab
    		document.getElementById(tab).style.display = "block";
    		evt.currentTarget.className += "active";
		}*/
	</script>
	<h1 class="page-header">Invoices</h1>
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

				$invoice->apptId = 1;
				$invoice->amtDue = 20.00;
				$invoice->calcAmtDue();*/

				$invoices = CheckoutController::getAllPendingInvoices();

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
			</div>
			<div id="payedTab" role="tabpanel" class="tab-pane">
				<?php

				/*$invoice = invoice::createNew();

				$invoice->apptId = 1;
				$invoice->amtDue = 20.00;
				$invoice->calcAmtDue();*/

				$invoices = CheckoutController::getAllPayedInvoices();

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
					echo "</tr>";
				}
					
				echo "</table>";
				?>
			</div>
		</div>
	</div>
	<p id="output"></p>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
