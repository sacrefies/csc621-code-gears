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
require_once __DIR__ . '/accounts/Employee.php';

use gears\accounts\AccountController;
use gears\conf\Settings;

// if user session is not valid, redirect to the login page.
if ((!AccountController::isLogin() || AccountController::isSessionExpired())
    && strtolower(AccountController::getSelfScript()) !== '/login.php'
) {
    AccountController::redirectTo('/login.php');
}

/**
 * Get the menu name which should be activated at this page.
 *
 * @param int $activeMenuId An integer value which indicates the active menu
 *
 * @return string The menu tab's name which should be activated.
 */
function getActivatedMenuTabName(int $activeMenuId) : string {
    switch ($activeMenuId) {
        case 0:
            return 'dashboard';
        case 1:
            return 'appointment';
        case 2:
            return 'in-service';
        case 3:
            return 'checkout';
        case 4:
            return 'mechanics';
    }
    return 'none';
}

function getUserName() {
    $emp = $_SESSION[Settings::$CURR_USER_SESS_KEY];
    return $emp->fname . ' ' . $emp->lname;
}

function getUserId() {
    $emp = $_SESSION[Settings::$CURR_USER_SESS_KEY];
    return $emp->empId;
}
// only for debugging
echo '<pre>';
echo session_status().PHP_EOL;
print_r($_SESSION);
echo '</pre>';

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
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<!-- Page header: navigation bar-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?php echo $pageHeader ?></a>
        </div>
        <ul class="nav navbar-nav">
            <li <?php if ('dashboard' === getActivatedMenuTabName($activeMenu)) {
                echo 'class="active"';
            } ?>><a href="/dashboard.php">Dashboard</a></li>
            <li class="dropdown" <?php if ('appointment' === getActivatedMenuTabName($activeMenu)) {
                echo 'class="active"';
            } ?>>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">New Appointment</a></li>
                    <li><a href="/appointments/weekly_view.php">This Week</a></li>
                </ul>
            </li>
            <li><a href="#">In-Service</a></li>
            <li><a href="#">Checkout</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li <?php if ('mechanics' === getActivatedMenuTabName($activeMenu)) {
                echo 'class="active"';
            } ?>><a href="accounts/mechanics_view.php">Mechanics</a></li>
            <?php
            if (strtolower(AccountController::getSelfScript()) !== '/login.php') {
                echo '<li><a href="/accounts/single_employee_view.php?empId=' . getUserId() . '"><span class="glyphicon glyphicon-user"></span> ' . getUserName() . '</a></li>' . PHP_EOL;
                echo '<li><a href="/logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>' . PHP_EOL;
            }
            ?>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 1em">
