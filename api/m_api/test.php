<?php 

$string2 = bin2hex(openssl_random_pseudo_bytes(10));
echo $string2;

$string = openssl_random_pseudo_bytes(10, $crypto);
//echo $string;

$string1 = substr(md5(rand()), 0, 100);
//echo $string1;
?>