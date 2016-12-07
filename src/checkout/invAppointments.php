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
        <table class='table table-hover'>
            <thead>
            <tr>
                <th>Customer</th>
                <th>Request</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Serviced By</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $appts = CheckoutController::getInvAppointments();
            /** @var $appts Appointment[] */
            if ($appts):
                foreach ($appts as $appt):
                    $id = $appt->appId;
                    $subject = $appt->subject;
                    $cust = $appt->customer;
                    $custName = $cust->firstName . ' ' . $cust->lastName;
                    $startTime = $appt->startTime->format('m/d/Y h:i A');
                    $endTime = $appt->endTime->format('m/d/Y h:i A');
                    $mech = $appt->getJob()->mechanic;
                    $mechName = $mech->fname . ' ' . $mech->lname;
                    ?>
                    <tr>
                        <td>
                            <a href="/accounts/customer_individual_view.php?customerId=<?php echo $cust->customerId; ?>">
                                <?php echo $custName; ?></a>
                        </td>
                        <td><?php echo $subject; ?></td>
                        <td><?php echo $startTime; ?></td>
                        <td><?php echo $endTime; ?></td>
                        <td>
                            <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $mech->empId ?>"><?php echo $mechName; ?></a>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#invCreate"
                                    data-yourParameter="<?php echo $id; ?>">Checkout
                                <span class="glyphicon glyphicon-usd"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="6"><p>No pending appointments to display</p></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
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
                        <label for="discAmt" class="control-label">Enter Discount:</label>
                        <input name="discAmt" type="text" id="discAmt" pattern="^1?\.\d{1,2}$"
                               class="form-control" placeholder="Discount"/>
                        <div class="checkbox">
                            <label><input type="checkbox" value=""
                                          onclick="enableDisable(this.checked, 'discAmt')"> No Discount</label>
                        </div>
                        <label id="label" for="payAmt" class="control-label">Enter Payment:</label>
                        <input name="payAmt" type="text" id="payAmt"
                               pattern="^\d+(\.\d{1,2})?$" class="form-control" placeholder="Amount Payed"/>
                        <div class="checkbox">
                            <label><input type="checkbox" value=""
                                          onclick="enableDisable(this.checked, 'payAmt')"> Pay Later</label></div>
                        <input name="appId" id="appId" value type="hidden"/>
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
    $(document).ready(function () {
        $('#invCreate').on('shown.bs.modal', function (e) {
            $('#discAmt').focus();
            var id = e.relatedTarget.dataset.yourparameter;
            document.getElementById("appId").value = id;

            amtUpdate(id, 0);
        });

        $('#discAmt').on('blur', function () {
            var $modal = $(this);
            var id = document.getElementById("appId").value;
            var disc = document.getElementById("discAmt").value;

            amtUpdate(id, disc);
        });
    });

    function amtUpdate(id, disc) {
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

    function enableDisable(enable, textBoxID) {
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
