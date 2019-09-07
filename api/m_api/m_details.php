<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/initialization.php';

$app = new Apps();

$current_app = $app->find_by_token($_POST['token']);

if($current_app){
    $key    = $current_app['app_key'];
    $secret = $current_app['app_secret'];

    //initiate auth 
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

    $callBackUrl = base_url().'api/mp_api/callback_url.php';

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

        //initiate mpesa details 
        $details = new MPESA_APPS_Details();
        $details->app_token = $current_app['app_token'];
        $details->shortcode = $shortCode;
        $details->confirmation = $confirmationUrl;
        $details->validation = $validationUrl;
        $details->lipanampesacode = $_POST['mpesa_code'];
        $details->passkey = $_POST['pass_key'];
        $details->callback_url = $callBackUrl;
        $details->timestamp =  date('YmdHis');

        $return_data = array();
        if($details->create()){
            $return_data['message'] = 'success';
        }else{
            $return_data['message'] = 'failed';
        }
        echo json_encode($return_data);
    }else{
        echo $curl_response;
    }
}else{
    echo json_encode(
        array('message'=>'failed')
    );
}