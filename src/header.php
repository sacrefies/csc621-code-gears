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
        case 5:
            return 'customers';
    }
    return 'none';
}

/**
 * Get the logon user's full name
 * @return string Returns the logon user's full name
 */
function getUserName() {
    $emp = $_SESSION[Settings::$CURR_USER_SESS_KEY];
    return $emp->fname . ' ' . $emp->lname;
}

/**
 * Get the logon user's unique identifier's value
 * @return int The id of the logon user.
 */
function getUserId() {
    $emp = $_SESSION[Settings::$CURR_USER_SESS_KEY];
    return $emp->empId;
}

/**
 * Encode the specified string value to UTF-8.
 *
 * @param string $val The string to be encoded.
 *
 * @return string A string which is encoded with UTF-8 from the given string.
 */
function toUTF8(string $val) {
// From http://w3.org/International/questions/qa-forms-utf-8.html
    if (preg_match('%^(?:[\x09\x0A\x0D\x20-\x7E]
    | [\xC2-\xDF][\x80-\xBF]
    | \xE0[\xA0-\xBF][\x80-\xBF]
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
    | \xED[\x80-\x9F][\x80-\xBF]
    | \xF0[\x90-\xBF][\x80-\xBF]{2}
    | [\xF1-\xF3][\x80-\xBF]{3}
    | \xF4[\x80-\x8F][\x80-\xBF]{2})*$%xs', $val)) {
        return $val;
    } else {
        return iconv('CP1252', 'UTF-8', $val);
    }
}

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
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?php echo $pageHeader ?></a>
        </div>
        <ul class="nav navbar-nav">
            <li <?php echo ('dashboard' === getActivatedMenuTabName($activeMenu)) ? 'class="active"' : ''; ?>>
                <a href="/dashboard.php">Dashboard</a></li>
            <li class="dropdown <?php echo ('appointment' === getActivatedMenuTabName($activeMenu)) ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/appointments/appointment_new.php">New Appointment</a></li>
                    <li><a href="/appointments/weekly_view.php">This Week</a></li>
                </ul>
            </li>
            <li class="dropdown<?php echo ('in-service' === getActivatedMenuTabName($activeMenu)) ? ' active' : ''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">In-Service<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/services/jobs_view.php">Jobs</a></li>
                    <li><a href="/services/jobs_vehicle_view.php">Vehicles In Service</a></li>
                </ul>
            </li>
            <li class="dropdown<?php echo ('checkout' === getActivatedMenuTabName($activeMenu)) ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Checkout<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/checkout/invAppointments.php">Pending Appointments</a></li>
                    <li><a href="/checkout/checkout.php">Invoices</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown<?php echo ('customers' === getActivatedMenuTabName($activeMenu)) ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Customers<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/accounts/customer_edit.php">New Customer</a></li>
                    <li><a href="/accounts/customers_view.php">Customer List</a></li>
                </ul>
            </li>
            <li <?php echo ('mechanics' === getActivatedMenuTabName($activeMenu)) ? 'class="active"' : ''; ?>>
                <a href="/accounts/mechanics_view.php">Mechanics</a>
            </li>
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
