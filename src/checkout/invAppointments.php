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
    	<div class="panel-heading">Pending Appointments</div>
    	<div class="panel-body">
    		<?php
                $appts = CheckoutController::getInvAppointments();
                echo "<table class='table table-striped'>";
                echo "<tr>";
                echo "<th>Customer</th>";
                echo "<th>Subject</th>";
                echo "<th>Description</th>";
                echo "<th>Time Created</th>";
                echo "<th></th>";
                echo "</tr>";
                foreach ($appts as $appt) {
                    $id = $appt->appId;
                    $subject = $appt->subject;
                    $cust = Customer::getInstance($appt->customer->customerId);
                    $custFirst = $cust->firstName;
                    $custLast = $cust->lastName;
                    $custName = "" . $custFirst . " " . $custLast;
                    echo "<tr>";
                    echo "<td>" . $id . "</td>";
                    echo "<td>" . $custName . "</td>";
                    echo "<td>" . $subject . "</td>";
                    echo "<td>" . $appt->createTime->format('Y-m-d H:i:s') . "</td>";
                    echo "<td><button class='btn btn-success btn-sm' data-toggle='modal' data-target='#invCreate' data-yourParameter='$id'>Checkout</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>

    	</div>
    </div>
    <div id="invCreate" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title edit-content">Create Invoice</h4>
            </div>
            <div class="modal-body">
            	<form action="javascript:create()" data-toggle="validator" role="form">
            		<div class="form-group">
		            	<label for="discAmt">Enter Discount:</label>
				        <input name="discAmt" type="text" id="discAmt" pattern="^1?\.\d{1,2}$" 
				        	class="form-control" placeholder="Discount"/>
				        <label><input type="checkbox" value=""
				        	onclick="enableDisable(this.checked, 'discAmt')">  No Discount</label>
				        <br>
		                <label id="label" for="payAmt">Enter Payment:</label>
				        <input name="payAmt" type="text" id="payAmt"
				        	pattern="^\d+(\.\d{1,2})?$" class="form-control" placeholder="Amount Payed"/>
				        <label><input type="checkbox" value=""
				        	onclick="enableDisable(this.checked, 'payAmt')">  Pay Later</label>
				        <div class="help-block with-errors"></div>
				        <input name="appId" id="appId" type="number" class="form-control" style="visibility:hidden;"/>
			    	</div>
			    	<div class="form-group">
            			<input class="btn btn-primary" type="submit" name="submit" value="Submit"/>
            		</div>
		    	</form>
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
		$('#invCreate').on('show.bs.modal', function(e) {
			var $modal = $(this);
	  		var id = e.relatedTarget.dataset.yourparameter;
	  		document.getElementById("appId").value = id;

	  		amtUpdate(id, 0);
		});

		$('#discAmt').on('blur', function() {
			var $modal = $(this);
	  		var id = document.getElementById("appId").value;
	  		var disc = document.getElementById("discAmt").value;
	  		
	  		amtUpdate(id, disc);
		});
	});

	function amtUpdate(id, disc){
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
        	if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
                document.getElementById('label').innerHTML = 
                	"Enter Payment ($" + data + " due):";
            }
        }//end onreadystatechange
        var link = "amtUpdate.php?id=" + id + "&disc=" + disc;
        xhttp.open("GET", link, true);
        xhttp.send();
	}

	function enableDisable(enable, textBoxID)
    {
         document.getElementById(textBoxID).disabled = enable;
    }

	function create() {
		console.log("hello");
		$('#invCreate').modal('hide');
        var id = document.getElementById("appId").value;
        var disc = document.getElementById("discAmt").value;
        var amt = document.getElementById("payAmt").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
        	if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
                //document.getElementById("output").innerHTML = data;
                location.reload();
            }
        }//end onreadystatechange
        var link = "createInv.php?id=" + id + "&disc=" + disc + "&amt=" + amt;
        xhttp.open("GET", link, true);
        xhttp.send();
    }
</script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>