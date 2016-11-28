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

require "{$_SERVER['SITEROOT']}/accounts/AccountController.php";

use gears\accounts\AccountController;

$error = '';
if (isset($_POST['empCode'])) {
    if (AccountController::login($_POST['empCode'])) {
        AccountController::redirectTo('dashboard.php');
    }
    $error = 'Login failed. Please check your employee code.';
}

$title = 'Gears: Login';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Bootstrap core JS -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
<div class="page-header" style="text-align:center">
    <h1>Welcome to Gears!</h1>
</div>
<?php
if (!empty($error)) {
    echo "<div class=\"alert alert-warning alert-dismissible\">\n";
    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\n";
    echo "<strong>Failed!</strong> $error\n";
    echo "</div>\n";
}
?>
<div class="container">
    <form action="<?php echo AccountController::getSelfScript(); ?>" method="POST">
        <div class="form-group">
            <label for="empCode">Enter Employee Code:</label>
            <input name="empCode" type="text" id="empCode" class="form-control" placeholder="Your Employee Code"
                   required autofocus/>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" name="submit" id="inputSubmit" value="Login"/>
        </div>
    </form>
</div>
</body>
</html>
