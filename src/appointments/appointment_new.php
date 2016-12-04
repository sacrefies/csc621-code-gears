<?php

declare(strict_types = 1);
namespace gears\appointments;

require_once __DIR__ . '/../appointments/AppointmentController.php';
require_once __DIR__ . '/../appointments/Appointment.php';

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


?>

<html>

    <body>

        <form> </form>

    </body>

</html>

<?php include __DIR__ . '/../footer.php'; ?>
