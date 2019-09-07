<?php 

function base_url(){
    $url = "http://localhost/payments/";
    return $url;
}

function redirect_to($new_location){
    header("Location: ".$new_location);
    exit;
}