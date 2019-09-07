<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

$app = new Apps();

$current_app = $app->find_by_token($_POST['token']);

if(!$current_app){
    echo json_encode(array('message'=>'errorToken'));
    die();
}

$key = $current_app['key'];
$secret = $current_app['secret'];

$auth = new Auth($key, $secret);

$headers = ['Content-Type: application/json; charset=utf8'];

$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$access_token = $auth->Access_Token($headers, $access_token_url);

$lipa_url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lipa_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$access_token));

//intiialize mpesa details
$details = new MPESA_APPS_Details();

$mpesa_details = $details->find_by_token($current_app['app_token']);

if(!$mpesa_details){
    echo json_encode(array('message'=>'errorToken'));
    die();
}

//send data 
$BusinessShortCode = $mpesa_details['lipanampesacode'];
$Timestamp = date('YmdHis');
$amount = $_POST['amount'];  
$PartyA = $_POST['phone']; // phone number 
$callBackURL = $_POST['callback_url'];
$AccountReference = 'Cart094'; //check out id or invoice id- Reference a scallback_url.phppecific transaction or client 
$transaction_desc = 'Cart Number';
$passsKey = $mpesa_details['passkey'];
$password = base64_encode($BusinessShortCode.$passsKey.$Timestamp);

//data to post
$curl_post_data = array(
    "BusinessShortCode"=> $BusinessShortCode,
    "Password"=> $password,
    "Timestamp"=> $Timestamp,
    "TransactionType"=>"CustomerPayBillOnline",
    "Amount"=> $amount,
    "PartyA"=> $PartyA,
    "PartyB"=> $BusinessShortCode,
    "PhoneNumber"=> $PartyA,
    "CallBackURL"=> $callBackURL,
    "AccountReference"=> $AccountReference,
    "TransactionDesc"=> $transaction_desc
);

//transform array into json obj
$data_string = json_encode($curl_post_data);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
//print_r($curl_response);

echo $curl_response;