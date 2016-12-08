<?php

declare(strict_types = 1);
namespace gears\appointments;


require_once __DIR__ . '/AppointmentController.php';
require_once __DIR__ . '/Appointment.php';
require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/../accounts/Employee.php';
require_once __DIR__ . '/../services/Task.php';
require_once __DIR__ . '/../services/Job.php';

use gears\conf\Settings;
use gears\accounts\AccountController;
use gears\accounts\Customer;
use gears\accounts\Employee;
use gears\models\State;
use gears\services\Task;
use gears\services\Job;

/**
 * @var string A string variable to set the page title.
 */
$title = 'New Appointment';

/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'Appointment Details';

/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 1;

include __DIR__ . '/../header.php';

$apptId = (isset($_GET['apptId']) && !empty($_GET['apptId'])) ? (int)$_GET['apptId'] : -1;

$appt = AppointmentController::getAppointmentById($apptId);
$state = $appt->getState();
$cust = $appt->customer;
$job = $appt ? $appt->getJob() : null;
$invoice = $appt ? $appt->getInvoice() : null;
?>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<!-- main content -->
<div class="row">
    <!-- left column for the appointment -->
    <div class="col-lg-9">
        <div class="panel-group">
            <!-- appointment -->
            <div class="panel panel-default">
                <div class="panel-heading"><span
                            class="label label-primary"><?php echo State::getName($state); ?></span>
                    <div class="pull-right">
                        <?php if ($state === State::NEW) { ?>
                            <button form="editForm" type="submit" name="submit" class="btn btn-primary btn-sm">Edit
                                <span class="glyphicon glyphicon-pencil">
                            </button>
                            <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteMsg'
                                    data-yourParameter=<?php echo $apptId; ?>>Cancel <span
                                        class="glyphicon glyphicon-remove">
                            </button>
                            <button form="addJobForm" class="btn btn-success btn-sm" type="submit" name="addJob"
                                    value="addJob">
                                Add Job <span class="glyphicon glyphicon-plus">
                            </button>
                        <?php } else if ($state === State::INVOICING && $appt->getInvoice() === null) { ?>
                            <button class='btn btn-success btn-sm' data-toggle='modal' data-target='#invCreate'
                                    data-yourParameter=<?php echo $apptId ?>>Checkout <span
                                        class="glyphicon glyphicon-usd"></span>
                            </button>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <form id="addJobForm" class="form-horizontal" method="POST" action="/services/job_edit_view.php">
                    <input type="hidden" value="<?php echo $apptId; ?>" name="apptId"/>
                </form>
                <div class="panel-body">
                    <?php if ($appt) { ?>
                        <form id="editForm" class="form-horizontal" action="appointment_new.php" method="POST">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="title">Title:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $appt->subject; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="desc">Description:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $appt->desc; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="event">Event Time:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $appt->eventTime->format('m/d/Y h:i A'); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="key">Time Created:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $appt->createTime->format('m/d/Y h:i A'); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="key">Last Updated:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $appt->updateTime->format('m/d/Y h:i A'); ?></p>
                                </div>
                            </div>
                            <?php if ($state !== State::NEW) { ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="key">Time Started:</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo $appt->startTime->format('m/d/Y h:i A'); ?></p>
                                    </div>
                                </div>
                            <?php }
                            if ($appt->isFinished()) { ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="key">Time Ended:</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo $appt->endTime->format('m/d/Y h:i A'); ?></p>
                                    </div>
                                </div>
                            <?php }
                            if ($state === State::NEW): ?>
                                <input type="hidden" name="appId" id="appId" value="<?php echo $apptId; ?>"/>
                            <?php endif; ?>
                        </form>
                    <?php } else { ?>
                        <div class="alert alert-warning alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Failed!</strong> Appointment Details not found
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- end appointment -->
            <?php if ($invoice):
                $chargedItems = $job->getWorksheet()->getTasks(); ?>
                <!-- invoice -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="label label-primary"><?php echo State::getName($invoice->getState()); ?></span>
                        <div class="pull-right">Invoice</div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="invPayed">Payed:</label>
                                <div class="col-sm-9 form-control-static">
                                    <p><strong><?php echo '$' . number_format($invoice->amtPayed, 2); ?></strong></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="invActualDue">Due (taxed, w/
                                    discount):</label>
                                <div class="col-sm-9 form-control-static">
                                    <p>
                                        <strong><?php echo '$' . number_format($invoice->amtDue * (1 + $invoice->taxRate) * (1 - $invoice->discRate), 2); ?></strong>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="invDue">Due (w/o tax):</label>
                                <div class="col-sm-9 form-control-static">
                                    <p><strong><?php echo '$' . number_format($invoice->amtDue, 2); ?></strong></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="invTaxRate">Tax Rate:</label>
                                <div class="col-sm-9 form-control-static">
                                    <p><?php echo number_format($invoice->taxRate, 2); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3" for="invDiscount">Discount Rate:</label>
                                <div class="col-sm-9 form-control-static">
                                    <p><?php echo number_format(100 * $invoice->discRate); ?>% off</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end invoice -->
                <!-- invoiced tasks -->
                <div class="panel panel-default">
                    <div class="panel-heading">Items Charged:
                        <span class="badge"><?php echo count($chargedItems); ?></span>
                        <div class="pull-right">
                            Total:
                            <strong>
                                <?php
                                $total = 0.0;
                                foreach ($chargedItems as $task) {
                                    $total += $task->cost;
                                }
                                echo '$' . number_format($total, 2);
                                ?>
                            </strong>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Part Name</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($chargedItems as $task):
                                /** @var Task $task */ ?>
                                <tr>
                                    <td><?php echo $task->invItem->code; ?></td>
                                    <td><?php echo $task->invItem->part; ?></td>
                                    <td><?php echo $task->quantity; ?></td>
                                    <td><?php echo '$' . number_format($task->cost, 2); ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end invoiced tasks -->
            <?php endif; ?>
        </div>
    </div>
    <!-- left column end -->
    <!-- right column for objects that reference to appointment -->
    <div class="col-lg-3">
        <div class="panel-group">
            <?php if ($job): ?>
                <!-- the job -->
                <div class="panel panel-default">
                    <div class="panel-heading">Service Job
                        <div class="pull-right">
                            <span class="label label-primary"><?php echo State::getName($job->getState()); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <form>
                            <div class="form-group">
                                <label class="control-label" for="jobKey">Key:</label>
                                <div class="form-control-static">
                                    <p>
                                        <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->key; ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="jobSummary">Summary:</label>
                                <div class="form-control-static">
                                    <p>
                                        <a href="/services/job_individual_view.php?jobId=<?php echo $job->jobId; ?>"><?php echo $job->summary; ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="jobCreateTime">Created:</label>
                                <div class="form-control-static">
                                    <p><?php echo $job->createTime->format('m/d/Y h:i A'); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="jobTasks">Tasks:</label>
                                <div class="form-control-static">
                                    <p>
                                        <?php $taskCount = ($job->getWorksheet() && $job->getWorksheet()->getTasks()) ? count($job->getWorksheet()->getTasks()) : 0; ?>
                                        <span class="badge"><?php echo $taskCount; ?></span>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end job -->
                <!-- the job's mechanic -->
                <div class="panel panel-default">
                    <?php $mech = $job->mechanic;
                    $mState = State::getName($mech->getState()); ?>
                    <div class="panel-heading">Mechanic
                        <div class="pull-right">
                            <?php if ($mech->getState() === State::AVAILABLE): ?>
                                <span class="label label-success"><?php echo $mState; ?></span>
                            <?php else: ?>
                                <span class="label label-warning"><?php echo $mState; ?></span>
                            <?php endif; ?></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <form>
                            <div class="form-group">
                                <label class="control-label" for="mechName">Name:</label>
                                <div class="form-control-static">
                                    <p>
                                        <a href="/accounts/mechanic_individual_view.php?empId=<?php echo $mech->empId; ?>">
                                            <?php echo $mech->fname . ' ' . $mech->lname; ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="mechPhone">Tel:</label>
                                <div class="form-control-static">
                                    <p><?php echo $mech->phone; ?></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end mechanic -->
            <?php endif; ?>
            <!-- customer -->
            <div class="panel panel-default">
                <div class="panel-heading">Customer</div>
                <div class="panel-body">
                    <form>
                        <div class="form-group">
                            <label class="control-label" for="name">Name:</label>
                            <p class="form-control-static">
                                <a href="/accounts/customer_individual_view.php?customerId=<?php echo $cust->customerId; ?>">
                                    <?php echo AccountController::getCustomerFullName($cust); ?>
                                </a>
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="phone">Tel:</label>
                            <p class="form-control-static"><?php echo $cust->phoneNumber; ?></p>
                        </div>
                        <?php if ($job && $job->customerVehicle): ?>
                            <div class="form-group">
                                <label class="control-label" for="vehicle">Vehicle:</label>
                                <p class="form-control-static">
                                    <a href="/accounts/customer_vehicle_individual_view.php?customer_vehicle_Id=<?php echo $job->customerVehicle->customer_vehicle_id; ?>">
                                        <?php $conVec = $job->customerVehicle->conventionVehicle;
                                        echo $conVec->year . ' ' . $conVec->make . ' ' . $conVec->model . ' ' . $conVec->trim . ' - ' . $job->customerVehicle->mileage . ' miles'; ?>
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- end customer -->
        </div>
    </div>
    <!-- right column end -->
</div>
<!-- main content -->

<!-- modals -->
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
                                      onclick="enableDisable(this.checked, 'discAmt')"> No Discount</label>
                        <br>
                        <label id="label" for="payAmt">Enter Payment:</label>
                        <input name="payAmt" type="text" id="payAmt"
                               pattern="^\d+(\.\d{1,2})?$" class="form-control" placeholder="Amount Payed"/>
                        <label><input type="checkbox" value=""
                                      onclick="enableDisable(this.checked, 'payAmt')"> Pay Later</label>
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
<div id="deleteMsg" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title edit-content">Cancel Appointment</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:deleteAppointment()" role="form">
                    <div class="form-group">
                        <p> Are you sure you want to cancel this appointment? </p>
                        <input name="appId_dlt" id="appId_dlt" type="number" class="form-control"
                               style="visibility:hidden;"/>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-danger" type="submit" name="submit" value="Yes, Cancel"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No, Close</button>
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
        $('#deleteMsg').on('shown.bs.modal', function (e) {
            var id = e.relatedTarget.dataset.yourparameter;
            document.getElementById("appId_dlt").value = id;
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
        var link = "/checkout/amtUpdate.php?id=" + id + "&disc=" + disc;
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
        var link = "/checkout/createInv.php?id=" + id + "&disc=" + disc + "&amt=" + amt;
        xhttp.open("GET", link, true);
        xhttp.send();
    }

    function deleteAppointment(id) {
        var id = document.getElementById("appId_dlt").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
                if (data === '0') {
                    document.getElementById("output").click();
                }
                else {
                    location.reload();
                }
            }
        }//end onreadystatechange
        var link = "deleteAppointment.php?id=" + id;
        xhttp.open("GET", link, true);
        xhttp.send();
    }
</script>
<!-- end modals -->
<?php include __DIR__ . '/../footer.php'; ?>
