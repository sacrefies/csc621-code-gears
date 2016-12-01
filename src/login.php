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
namespace gears;

require_once __DIR__ . '/accounts/AccountController.php';

use gears\accounts\AccountController;

$error = '';
if (isset($_POST['empCode'])) {
    if (AccountController::login($_POST['empCode'])) {
        AccountController::redirectTo('dashboard.php');
    }
    $error = 'Login failed. Please check your employee code.';
}

$title = 'Gears: Login';
$activeMenu = -1;
$pageHeader = 'Gears';

include __DIR__ . '/header.php';
?>

<div class="panel panel-default">
    <div class="panel-heading">Employee Login</div>
    <div class="panel-body">
        <?php
        if (!empty($error)) {
            echo '<div class="alert alert-warning alert-dismissible">'.PHP_EOL;
            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.PHP_EOL;
            echo "<strong>Failed!</strong> $error".PHP_EOL;
            echo '</div>'.PHP_EOL;
        }
        ?>
        <form action="<?php echo AccountController::getSelfScript(); ?>" method="POST">
            <div class="form-group">
                <label for="empCode">Enter Employee Code:</label>
                <input name="empCode" type="text" id="empCode" class="form-control" placeholder="Your Employee Code"
                       required autofocus pattern="^[a-zA-Z]{2}[0-9]{4}$"/>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" id="inputSubmit" value="Login"/>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__.'/footer.php'; ?>
