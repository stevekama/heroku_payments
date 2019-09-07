<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

$user = new Users();

if($_POST['password'] === $_POST['confirm']){
    $user->fullnames = $_POST['fullnames'];
    $user->phone     = $_POST['phone'];
    $user->email     = $_POST['email'];
    $user->username  = $_POST['username'];
    $user->password  = $_POST['password'];

    //find user by email 
    $usermail = $user->find_user_by_email($user->email);

    if($usermail){
        echo json_encode(array('message'=>'emailError'));
        die();
    }

    ///create user 
    if($user->create()){
        echo json_encode(array('message'=>'success'));
    }else{
        echo json_encode(array('message'=>'failed'));
    }
}else{
    echo json_encode(array('message'=>'errorPass'));
}