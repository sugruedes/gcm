<?php

include 'ChatProtoPushMessage.php';

$api_key = "APIKEY000";
$url = "http://test.des/gcm/registered.php";
$devices = array( "DEVICE_ID3");

$message = "Using php file";


$an = new ChatProtoPushMessage($api_key, $url);
$an->setDevices($devices);

$response = $an->send($message);

return $response;

?>
