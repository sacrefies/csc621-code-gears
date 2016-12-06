<?php

declare(strict_types = 1);
namespace gears\appointments;


require_once __DIR__ . '/../appointments/AppointmentController.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';
require_once __DIR__ . '/../conf/Settings.php';

use gears\conf\Settings;

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
?>

<div class="panel panel-default">
    <div class="panel-heading">Appointment Details</div>
    <div class="panel-body">
        <?php if ($appt) { ?>
            <form class="form-horizontal">

                <div class="form-group">
                    <label class="control-label col-sm-2" for="appId">Appointment ID:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->appId; ?>" type="text" class="form-control disabled"
                               id="appId" placeholder="Appointment ID" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="customer">Customer:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->customer->customerId; ?>" type="text" class="form-control disabled" id="customer"
                               placeholder="Customer" disabled>
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
                        <label class="control-label col-sm-2" for="empCode">Create Time:</label>
                        <div class="col-sm-10">
                            <input value="<?php echo $appt->createTime->format(Settings::$MYSQL_DATETIME_FORMAT); ?>" type="text" class="form-control disabled"
                                   id="empCode" placeholder="Employee Code" disabled>
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
            </form>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> Appointment Details not found
            </div>
        <?php } ?>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
