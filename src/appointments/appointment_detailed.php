<?php

declare(strict_types = 1);
namespace gears\appointments;


require_once __DIR__ . '/AppointmentController.php';
require_once __DIR__ . '/Appointment.php';
require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../conf/Settings.php';
require_once __DIR__ . '/../accounts/Employee.php';

use gears\conf\Settings;
use gears\accounts\AccountController;
use gears\accounts\Customer;
use gears\accounts\Employee;
use gears\models\State;

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
?>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">Appointment Details
        <div class="pull-right">
            <?php if ($state === State::NEW) { ?>
                <button form="editForm" type="submit" name="submit" class="btn btn-primary btn-sm">Edit 
                        <span class="glyphicon glyphicon-pencil">
                </button>
                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteMsg' 
                        data-yourParameter=<?php echo $apptId; ?>>Cancel <span class="glyphicon glyphicon-remove">
                </button>
                <button form="addJobForm" class="btn btn-success btn-sm" type="submit" name="addJob" value="addJob">
                        Add Job <span class="glyphicon glyphicon-plus">
                </button>
            <?php }else if($state === State::INVOICING && $appt->getInvoice() === null){ ?>
                <button class='btn btn-success btn-sm' data-toggle='modal' data-target='#invCreate' 
                    data-yourParameter=<?php echo $apptId ?>>Checkout <span class="glyphicon glyphicon-usd"></span>
                </button>
            <?php } else {?>
                <span class="label label-primary"><?php echo State::getName($state); ?></span>
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
                    <label class="control-label col-sm-2" for="customerfname">Customer First Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->customer->firstName ?>" type="text" class="form-control disabled" id="customerfname"
                               placeholder="First Name" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="customerlname">Customer Last Name:</label>
                    <div class="col-sm-10">
                    <input value="<?php echo $appt->customer->lastName ?>" type="text" class="form-control disabled" id="customerlname"
                           placeholder="Last Name" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="subject">Subject:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->subject; ?>" type="text" class="form-control disabled" id="subject"
                               placeholder="subject" disabled>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-sm-2" for="description">Description:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->desc; ?>" type="text" class="form-control disabled" id="description"
                               placeholder="description" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="upTime">Update Time:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->updateTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="tel" class="form-control disabled" id="upTime"
                               placeholder="upTime" disabled>
                    </div>
                </div>

                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="cTime">Create Time:</label>
                        <div class="col-sm-10">
                            <input value="<?php echo $appt->createTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="text" class="form-control disabled"
                                   id="cTime" placeholder="Create Time" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="evTime">Event Time:</label>
                        <div class="col-sm-10">
                            <input value="<?php echo $appt->eventTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="text" class="form-control disabled" id="evTime"
                                   placeholder="Event Time" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="sTime">Start Time:</label>
                        <div class="col-sm-10">
                            <input value="<?php echo $appt->startTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="text" class="form-control disabled" id="sTime"
                                   placeholder="Start Time" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="endTime">End Time:</label>
                        <div class="col-sm-10">
                            <input value="<?php echo $appt->endTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="tel" class="form-control disabled" id="endTime"
                                   placeholder="End Time" disabled>
                        </div>
                    </div>
                    <?php if ($state === 1): ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="hidden" name="appId" id="appId" value="<?php echo $apptId; ?>"/>
                        </div>
                    </div>
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
                        <input name="appId_dlt" id="appId_dlt" type="number" class="form-control" style="visibility:hidden;"/>
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
    $(document).ready(function(){
        $('#invCreate').on('shown.bs.modal', function(e) {
            $('#discAmt').focus();
            var id = e.relatedTarget.dataset.yourparameter;
            document.getElementById("appId").value = id;
            amtUpdate(id, 0);
        });
        $('#deleteMsg').on('shown.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.yourparameter;
            document.getElementById("appId_dlt").value = id;
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
        var link = "/checkout/amtUpdate.php?id=" + id + "&disc=" + disc;
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
                if(data === '0'){
                    document.getElementById("output").click();
                }
                else{
                    window.location.replace('/appointments/weekly_view.php');
                }
            }
        }//end onreadystatechange
        var link = "deleteAppointment.php?id=" + id;
        xhttp.open("GET", link, true);
        xhttp.send();
    }
</script>
<?php include __DIR__ . '/../footer.php'; ?>
