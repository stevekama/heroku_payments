<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

$app = new Apps();

$app->app_name = $_POST['name'];
$app->app_method = $_POST['method'];
$app->app_key = $_POST['key'];
$app->app_secret = $_POST['secret'];

$data = array();
if($app->create()){
    $data['message'] = 'success';
    $data['method'] = $app->app_method;
}else{
    $data['message'] = 'failed';
    $data['method'] = 'null';
}
echo json_encode($data);