<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../models/initialization.php';

$transactions = new Transactions();

// Read Transactions 
$results = $transactions->find_all();

// num 
$num = $results->rowCount();

if($num > 0){
     // Transactions array
    $transactions_arr = array();
    $transactions_arr['data'] = array();
    while($result = $results->fetch(PDO::FETCH_ASSOC)){
        extract($result);

        $transactions_item = array(
            'id'                   => $id,
            'transaction_id'       => $transaction_id,
            'product'              => $product,
            'transaction_amount'   => $transaction_amount,
            'transaction_currency' => $transaction_currency,
            'transaction_method'   => $transaction_method,
            'transaction_status'   => $transaction_status
        );

        // Push to "data"
        array_push($transactions_arr, $transactions_item);

    }
    // Turn to JSON & output
    echo json_encode($transactions_arr);
}else{
    // No Posts
    echo json_encode(array('message' => 'No Transactions Found'));
}