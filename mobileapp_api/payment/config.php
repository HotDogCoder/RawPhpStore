<?php
session_start();

$basePath=explode("payment",__DIR__);
include($basePath[0]."app/Lib/Variables.php");
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
require __DIR__  . '/vendor/autoload.php';

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Razorpay\Api\Api;


$baseURL="http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//$baseURL=str_replace("payment/","",$baseURL);
$baseURL=explode("payment",$baseURL);
define("BASE_URL", $baseURL[0]);
/***********************************====Paypal=====**************************************/
// https://developer.paypal.com/webapps/developer/applications/myapps
//paypal return URl configration 
define("SET_RETURN_URL", BASE_URL."payment/");
define("SET_CANCEL_URL", BASE_URL."payment/");

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        PAYPAL_CLIENT_ID,     // ClientID
        PAYPAL_CLIENT_SECRET      // ClientSecret
    )
);


//enable payment methods

define("PAYMENT_METHOD_COD", "true");
define("PAYMENT_METHOD_PAYPAL", "true");
define("PAYMENT_METHOD_STRIPE", "true");
define("PAYMENT_METHOD_omise", "true");




if(!isset($_GET['id']) && !isset($_SESSION['order_session_id']))
{
    echo "access denies";
    die();
}

if(isset($_GET['id']))
{
    $order_session_id=$_SESSION['order_session_id']=$_GET['id'];
}
else
{
    $order_session_id=$_SESSION['order_session_id'];
}

$url=BASE_URL.'api/showOrderSession';
$data =array(
    "id" => $order_session_id  
);

$Order_Data=@curl_request($data,$url);

//dycript serializ json
$Order_Data_json=@json_decode($Order_Data['msg']['OrderSession']['string']);


//get order data json to array
$json_app_date=@json_decode($Order_Data_json,true);


$rider_tip=@$json_app_date['rider_tip'];
$tax=@$json_app_date['tax'];

$price=@$json_app_date['price'];
$sub_total=@$json_app_date['sub_total'];
$delivery_fee=@$json_app_date['delivery_fee'];
$discount=@$json_app_date['discount'];

$user_id=$json_app_date['user_id'];

$url=BASE_URL.'api/showUserDetail';
$data =array(
    "user_id" => $user_id,
    "restaurant_id" => $json_app_date['restaurant_id']    
);

$user_data=@curl_request($data,$url);

$user_cards=$user_data['msg']['UserInfo']['Cards'];
$restaurantName=$user_data['msg']['Restaurant']['Restaurant']['name'];
$restaurantImage=$user_data['msg']['Restaurant']['Restaurant']['image'];
$currency_symbol=$user_data['msg']['Restaurant']['Currency']['symbol'];
$taxPercentage=$user_data['msg']['Restaurant']['Tax']['tax'];


if(isset($json_app_date['lang']))
{
    if($json_app_date['lang']=="english")
    {
        include("language/english.php");
    }
    else
    if($json_app_date['lang']=="arabic")
    {
        include("language/arabic.php");
    }
    else
    {
        include("language/english.php");
    }
    
}
else
{
    include("language/english.php");
}




function curl_request($data,$url)
{
    $headers = [
          "Accept: application/json",
          "Content-Type: application/json",
          "api-key: ".API_KEY." "
      ];

    $data = $data;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $return = curl_exec($ch);
    $json_data = json_decode($return, true);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    return $json_data;
}




    
    
?>