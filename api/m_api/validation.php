<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../models/initialization.php';

$response = '{
				"ResultCode": 0, 
				"ResultDesc": "Confirmation Received Successfully"
            }';
            
// DATA
$mpesaResponse = file_get_contents('php://input');

//decode the json data
$jsonMpesaResponse = json_decode($mpesaResponse, true);

// create new transaction 
// 1.initiate transaction 
$transaction = new Transactions();

$transaction->transaction_id = $jsonMpesaResponse['TransID'];
$transaction->transaction_time = $jsonMpesaResponse['TransTime'];
$transaction->product = '';
$transaction->transaction_amount = $jsonMpesaResponse['TransAmount'];
$transaction->transaction_currency = 'KSH';
$transaction->transaction_method = 'MPESA';
$transaction->transaction_status = 'COMPLETE';

echo $response;

if($transaction->create()){
	echo json_encode(
		array('message'=>'success')
	);
}else{
	echo json_encode(
		array('message'=>'not success')
	);
}
?>