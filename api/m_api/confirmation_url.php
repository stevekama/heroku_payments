<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../models/initialization.php';

$response = '{
    "ResultCode": 0, 
    "ResultDesc": "Confirmation Received Successfully"
}';

// DATA
$m_response = file_get_contents('php://input');

$jsonMpesaResponse = json_decode($m_response, true); // We will then use this to save to database

//save transaction into the mpesa transaction 
// 1. initiate mpesa transaction
$transaction = new MPESATransactions();

$transaction->transaction_type = $jsonMpesaResponse['TransactionType'];
$transaction->transaction_id = $jsonMpesaResponse['TransID'];
$transaction->transaction_time = $jsonMpesaResponse['TransTime'];
$transaction->transaction_amount = $jsonMpesaResponse['TransAmount'];
$transaction->business_shortcode = $jsonMpesaResponse['BusinessShortCode'];
$transaction->bill_refnumber = $jsonMpesaResponse['BillRefNumber'];
$transaction->invoice_number = $jsonMpesaResponse['InvoiceNumber'];
$transaction->original_balance = $jsonMpesaResponse['OrgAccountBalance'];
$transaction->third_party_transaction_id = $jsonMpesaResponse['ThirdPartyTransID'];
$transaction->msisdn = $jsonMpesaResponse['MSISDN'];
$transaction->first_name = $jsonMpesaResponse['FirstName'];
$transaction->middle_name = $jsonMpesaResponse['MiddleName'];
$transaction->last_name = $jsonMpesaResponse['LastName'];

echo $response;

if($transaction->create()){
	echo json_encode(array('message'=>'success'));
}else{
	echo json_encode(
		array('message'=>'not success')
	);
}