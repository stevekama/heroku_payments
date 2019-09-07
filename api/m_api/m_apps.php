<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

/// generate access token 

$app_name = $_POST['app_name'];
$key = $_POST['key'];
$secret = $_POST['secret'];

$auth = new Auth($key, $secret);

$headers = ['Content-Type: application/json; charset=utf8'];

$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$access_token = $auth->Access_Token($headers, $access_token_url);

/// register url 

$register_url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

$shortCode = $_POST['code']; // provide the short code obtained from your test credentials

/* This two files are provided in the project. */
$confirmationUrl = base_url().'api/m_api/confirmation_url.php'; // path to your confirmation url. can be IP address that is publicly accessible or a url
$validationUrl = base_url().'api/mp_api/validation.php'; // path to your validation url. can be IP address that is publicly accessible or a url

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $register_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

$curl_post_data = array(
  //Fill in the request parameters with valid values
  'ShortCode' => $shortCode,
  'ResponseType' => 'Confirmed',
  'ConfirmationURL' => $confirmationUrl,
  'ValidationURL' => $validationUrl
);

$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
$curl_response = curl_exec($curl);
$data = json_decode($curl_response);
if($data->{'ResponseDescription'} == 'success'){
    /// save to db 

    // initiate mpesa app
    $app = new MPESA_Apps();

    // enter values 
    $app->app_name = $app_name;
    $app->app_key = $key;
    $app->app_secret = $secret;
    $app->app_token = $access_token;
    $app->shortcode = $shortCode;
    $app->confirmation = $confirmationUrl;
    $app->validation = $validationUrl;

    // create app 
    if($app->create()){
        echo json_encode(
            array('message'=>'success')
        );
    }
}else{
    echo $curl_response;
}