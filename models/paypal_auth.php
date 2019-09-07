<?php 
require_once('initialization.php');

class PayPalAuth{
    // Bring in the auth key and secret 

    private $app_key;
    private $app_secret;
    
    //connect to db 
    public function __construct($key, $secret)
    {
       $this->app_key = $key;
       $this->app_secret = $secret;
    }
    
    public function auth()
    {
        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->app_key,
                $this->app_secret
            )
        );

        return $paypal;
    }

}