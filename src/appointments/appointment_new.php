<?php

declare(strict_types = 1);
namespace gears\appointments;


require_once __DIR__ . '/AppointmentController.php';
require_once __DIR__ . '/Appointment.php';
require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';

use gears\accounts\AccountController;
use gears\accounts\Customer;

/**
 * @var string A string variable to set the page title.
 */
$title = 'New Appointment';

/**
 * @var string A string variable to set the nav bar header.
 */
$pageHeader = 'New Appointment';

/**
 * @var int An integer which indicates the current active nav menu tab.
 *          0: dashboard, 1: appointment, 2: in-service, 3: checkout, 4: mechanics
 */
$activeMenu = 1;

include __DIR__ . '/../header.php';

$error = '';

$apptId = (isset($_POST['appId']) && !empty($_POST['appId'])) ? (int)$_POST['appId'] : -1;

$appt = AppointmentController::getAppointmentById($apptId);
if($appt){
    $cust = Customer::getInstance($appt->customer->customerId);
    $custName = AccountController::getCustomerFullName($cust);
}

// do update or create new
if (isset($_POST['updateType']) && !empty($_POST['updateType'])) {
    if ($_POST['updateType'] === 'new') {

        if (AppointmentController::createNewAppointment($_POST['cust'], $_POST['subj'], $_POST['desc'], $_POST['date'])) {
                AppointmentController::redirectTo('weekly_view.php');
        } else {
            $error = 'Saving appointment information failed.';
        }
    } else if($_POST['updateType'] === 'update') {
        if (AppointmentController::updateAppointment($appt, $_POST['subj'], $_POST['desc'], $_POST['date'])) {
            AppointmentController::redirectTo('weekly_view.php');
        } else {
            $error = 'Saving appointment information failed.';
        }
    }
}
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $appt ? "Edit Appointment for " . $custName : 'New Appointment'; ?></div>
    <div class="panel-body">
        <?php if ($error): ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form class="form-horizontal" method="POST" action="<?php echo AppointmentController::getSelfScript(); ?>">
            <?php if (!$appt): ?>
                <input type="hidden" name="updateType" id="updateType" value="new"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="cust">Cusomer:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="cust" id="cust">
                            <?php
                                $customers = Customer::getAll();
                                foreach($customers as $cust){
                                    $custName = ''.$cust->firstName.' '.$cust->lastName;
                                    echo "<option value='$cust->customerId'>$custName</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Subject:</label>
                    <div class="col-sm-10">
                        <input value="" type="text" class="form-control" id="subj"
                               name="subj" placeholder="Subject" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="desc">Description:</label>
                    <div class="col-sm-10">
                        <input value="" type="text"
                               name="desc" class="form-control" id="desc" placeholder="Short Description" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="date">Date:</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" min="<?php $date = new \DateTime(); $ymd = $date->format('Y-m-d'); $time = $date->format('H:m'); echo ''.$ymd.'T'.$time; ?>"
                                value="<?php $date = new \DateTime(); $ymd = $date->format('Y-m-d'); $time = $date->format('H:i'); echo ''.$ymd.'T'.$time; ?>"
                               name="date" class="form-control" id="date" placeholder="00-00-00" required/>
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="updateType" id="updateType" value="update"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Subject:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->subject; ?>" type="text" class="form-control" id="subj"
                               name="subj" placeholder="Subject" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="desc">Description:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->desc; ?>" type="text"
                               name="desc" class="form-control" id="desc" placeholder="Short Description" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="date">Date:</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" min="<?php $date = new \DateTime(); $ymd = $date->format('Y-m-d'); $time = $date->format('H:m'); echo ''.$ymd.'T'.$time; ?>"
                                value="<?php $date = $appt->eventTime; $ymd = $date->format('Y-m-d'); $time = $date->format('H:i'); echo ''.$ymd.'T'.$time; ?>"
                               name="date" class="form-control" id="date" placeholder="00-00-00" required/>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">save</button>
                    <button type="reset" name="reset" class="btn btn-default">reset</button>
                    <input type="hidden" name="appId" id="appId" value="<?php echo $apptId; ?>"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
