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
                    /*$invoice = invoice::createNew();
                    $invoice->appt = Appointment::getInstance(2);
                    $invoice->amtDue = 20.00;
                    $invoice->update();*/
                    $appts = CheckoutController::getInvAppointments();
                    echo "<table class='table table-striped'>";
                    echo "<tr>";
                    echo "<th>Customer</th>";
                    echo "<th>Subject</th>";
                    echo "<th>Description</th>";
                    echo "<th>Time Created</th>";
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
                        echo "<td><button class='btn btn-info btn-sm' data-toggle='modal' data-target='#amtEnter' id='$id'>Checkout</button></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
            ?>

    	</div>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>