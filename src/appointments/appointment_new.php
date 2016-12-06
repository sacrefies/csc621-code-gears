<?php

declare(strict_types = 1);
namespace gears\appointments;


require_once __DIR__ . '/../appointments/AppointmentController.php';
require_once __DIR__ . '/../appointments/Appointment.php';
require_once __DIR__ . '/../accounts/AccountController.php';
require_once __DIR__ . '/../accounts/Customer.php';

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

$appts = Appointment::getAll();
$appts = count($appts)+1;
// do update or create new
if (isset($_POST['updateType']) && !empty($_POST['updateType'])) {
    if ($_POST['updateType'] === 'new') {
        if (AppointmentController::createNewAppointment($_POST['subj'], $_POST['desc'], $_POST['updateTime'], $_POST['createTime'], $_POST['eventTime'], $_POST['startTime'], $_POST['endTime'], $_POST['customer'])) {
            if(AppointmentController::createNewAppointment($_POST['subj'], $_POST['desc'], $_POST['updateTime'], $_POST['createTime'], $_POST['eventTime'], $_POST['startTime'], $_POST['endTime'], $_POST['customer'])) {
                AppointmentController::redirectTo('customers_view.php');
            }
        } else {
            $error = 'Saving appointment information failed.';
        }
    } else if ($_POST['updateType'] === 'update') {
        if (AppointmentController::updateAppointment($appt, $_POST['subj'], $_POST['desc'], $_POST['updateTime'], $_POST['createTime'], $_POST['eventTime'], $_POST['startTime'], $_POST['endTime'], $_POST['customer'])) {
            AppointmentController::redirectTo('weekly_view.php');
        } else {
            $error = 'Saving appointment information failed.';
        }
    }
}
?>
<div class="panel panel-default">
    <div class="panel-heading">
        Appointment <?php echo $appt ? AppointmentController::getAppointmentById($apptId) : 'Unknown'; ?></div>
    <div class="panel-body">
        <?php if ($error): ?>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Failed!</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form class="form-horizontal" method="POST" action="<?php echo AppointmentController::getSelfScript(); ?>">
            <?php if ($appt): ?>
                <input type="hidden" name="updateType" id="updateType" value="update"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="firstName">First Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->subject; ?>" type="text" class="form-control"
                               id="firstName" name="firstName" placeholder="First Name" required/> <!-- subject just to test page -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->subject; ?>" type="text" class="form-control" id="lastName"
                               name="lastName" placeholder="Last Name" required/> <!-- subject just to test page -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Summary:</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $appt->subject; ?>" type="text" class="form-control" id="subj"
                               name="subj" placeholder="Summary" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="desc">Description:</label>
                    <div class="col-sm-10">
                        <input value="<?php $appt->desc; ?>" type="text"
                               name="desc" class="form-control" id="desc" placeholder="Short Description" required/>
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="updateType" id="updateType" value="new"/>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="firstName">First Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastName">Last Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="subj">Summary:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="subj" id="subj"
                               placeholder="Summary" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="desc">Description:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="desc" id="desc"
                               placeholder="Short Description" required/>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">save</button>
                    <button type="reset" name="reset" class="btn btn-default">reset</button>
                    <input type="hidden" name="customerId" id="customerId" value="<?php echo $apptId; ?>"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
