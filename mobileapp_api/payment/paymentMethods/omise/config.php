<?php
include('../../config.php');

define("OMISE_SECRET_KEY", "skey_test_5jv0g0ym0kocoqdktd3");
define("OMISE_PUBLIC_KEY", "pkey_test_5jv0g0ylqgqob51eu96");

$Omisebaseurl="https://api.omise.co/";


function curl_request_omise($data , $endpoint ,  $baseurl){

    $ch = curl_init( $baseurl.$endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERPWD, OMISE_SECRET_KEY);
    $return = curl_exec($ch);
    $json_data = json_decode($return, true);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $json_data;

}