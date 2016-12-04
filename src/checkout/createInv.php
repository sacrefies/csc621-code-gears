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
namespace gears\checkout;

require_once __DIR__ . '/CheckoutController.php';
require_once __DIR__ . '/Invoice.php';
require_once __DIR__ . '/../accounts/AccountController.php';

use gears\accounts\AccountController;

if(isset($_GET["disc"])){
	$disc = floatval($_GET["disc"]);
}

if(isset($_GET["amt"])){
	$amt = floatval($_GET["amt"]);
}

$id = (int)$_GET["id"];

echo CheckoutController::createInvoice($id, $disc, $amt);

?>