<?php 
class Access_Token{
    private $key; 
    private $secret; 
    
    public function Token($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;

        $headers = ['Content-Type: application/json; charset=utf8'];
        
    } 
}
?>