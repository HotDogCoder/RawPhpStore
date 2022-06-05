<?php
include('config.php');

if(isset($_REQUEST['omiseToken']) && $_REQUEST['omiseToken'] != ""){

    $cardToken  =   $_REQUEST['omiseToken'];
    $endpoint   =   "charges";
    $data       =   [
        'amount'    => $price,
        'currency'  =>  'usd',
        'card'      =>  $cardToken
    ];
    $result =   curl_request_omise($data , $endpoint ,  $Omisebaseurl);
    // echo "<pre>";
    // print_r($result);
    // die();
    
    if($result['object']=="charge")
    {
        echo "<script>window.location='./?status=paymentSuccess'</script>";
    }
    else
    {
        echo "<script>window.location='./?status=paymentFaild'</script>";
    }

}








