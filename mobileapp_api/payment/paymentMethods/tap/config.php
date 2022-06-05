<?php
include("../../config.php");

define(PUBLISH_KEY,'pk_test_EtHFV4BuPQokJT6jiROls87Y');
define(SECRET_KEY,'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ');
$apibaseurl = "https://api.tap.company/v2/";
$baseurl = "http://irfan.softlabs.cc/tap/";

function curl_request($data , $endpoint ,  $baseurl,$token){
    $headerArray    =   [
           "Authorization: Bearer $token",
           "Content-Type: application/json"
         ];
    $ch = curl_init( $baseurl.$endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $return = curl_exec($ch);
    $json_data = json_decode($return, true);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $json_data;

}
?>
    
