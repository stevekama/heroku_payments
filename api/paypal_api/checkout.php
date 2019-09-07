<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

use \PayPal\Api\Payer;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Details;
use \PayPal\Api\Amount;
use \PayPal\Api\Transaction;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Payment;

include_once '../../models/initialization.php';

// authenticate user 
// get key and secret 
$app = new Apps();

// get app details 
$current_app = $app->find_by_token($_POST['token']);

// Paypal Auth 
$paypal_auth = new PayPalAuth($current_app['key'], $current_app['secret']);

// get paypal details 
$paypal = $paypal_auth->auth();

// process payments
