<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

///1. obtain the access token

/// initiate app 
$app = new Apps();

$current_app = $app->find_by_token($_POST['token']);

// chaeck if you've got the app 
if(!$current_app){
    echo json_encode(array('message'=>'errorApp'));
    die();
}

// get key and secret stored
$key    = $current_app['app_key'];
$secret = $current_app['app_secret'];

//initiate auth 
$auth = new Auth($key, $secret);

$headers = ['Content-Type: application/json; charset=utf8'];

$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$access_token = $auth->Access_Token($headers, $access_token_url);// generate access token.  

//Simulate Payments
$simulate_url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

// initialize mpesa details 
$details = new MPESA_APPS_Details();

// get mpesa details 
$mpesa_details = $details->find_by_token($current_app['app_token']);

//check if details are found 
if(!$mpesa_details){
    echo json_encode(array('message'=>'errorToken'));
    die();
}

$ShortCode  = $mpesa_details['shortcode']; // Shortcode. Same as the one on register_url.php
$amount     = $_POST['amount']; // amount the client/we are paying to the paybill
$msisdn     = $_POST['number']; // phone number paying 
$billRef    = $_POST['invoice']; // This is anything that helps identify the specific transaction. Can be a clients ID, Account Number, Invoice amount, cart no.. etc

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $simulate_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token));
$curl_post_data = array(
    'ShortCode' => $ShortCode,
    'CommandID' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'Msisdn' => $msisdn,
    'BillRefNumber' => $billRef
);

$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
$curl_response = curl_exec($curl);
$data = json_decode($curl_response);
if($data->{'ResponseDescription'} == 'Accept the service request successfully.'){
    echo json_encode(array('message'=>'success'));
}else{
    echo $curl_response;
}
?>