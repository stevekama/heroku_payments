<?php 

class Auth{
    private $consumer_key;
    private $consumer_secret;


    public function __construct($key="", $secret="")
    {
        $this->consumer_key    = $key;
        $this->consumer_secret = $secret;
    }

    public function Access_Token($headers, $url)
    {
        $curl =  curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $this->consumer_key.':'.$this->consumer_secret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $access_token = $result->access_token;
        return $access_token;
        curl_close($curl);
    }
}