<?php

@session_start();
@ini_set('session.gc_maxlifetime',12*60*60);
@ini_set('session.cookie_lifetime',12*60*60);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set("display_errors", "Off");
error_reporting(0);
date_default_timezone_set('Asia/Karachi');
define('PRE_FIX' , "foodiesAdmin_");

//API host link api url should be  https://domain.com/your folder path  OR http://domain.com/your folder path other wise it will be your configration error 
//$baseurl = "dev.weydrop.com/weydrop/";
$baseurl = "http://dev.weydrop.com/";

//api key to secure your API,web portals & mobile app  WARNING:if you are changing it here you have to change same string in mobileapp_api too
define('API_KEY', '156c4675-9608-4591-b2ec-427503464aac');


//firebase real tiem database access  NOTE:you can get these access from firebase > "project setting" > https://prnt.sc/qde810
define('apiKey', 'AIzaSyB6y3kgq2FGNkoYyzkUcWtURVZMzpmpARw');
define('authDomain', 'weydrop-aa960.firebaseapp.com');
define('databaseURL', 'https://weydrop-aa960.firebaseio.com');
define('projectId', 'weydrop-aa960');
define('storageBucket', 'weydrop-aa960.appspot.com');
define('messagingSenderId', '120667586596');
define('appId', '1:120667586596:web:303a26cbd4d5a5f6d498b1');

//google map javascript api key
//define('Google_Map_Key', 'AIzaSyABoSe4cQyl0x1PB6r5l10xkzAN-t0IGM4');
define('Google_Map_Key', 'AIzaSyB6y3kgq2FGNkoYyzkUcWtURVZMzpmpARw');

//dont change any thing here
$imagebaseurl=$baseurl."mobileapp_api/";
$baseurl = $baseurl."mobileapp_api/superAdmin/";



//print_r($_SESSION);

if (@$_GET['p'] == "login") {
      
      $email = $_POST['email'];
      $password = $_POST['password'];
      
      
      $headers = [
          "Accept: application/json",
          "Content-Type: application/json",
          "api-key: ".API_KEY." "
      ];
      
      $data = [
          "email" => $email,
          "password" => $password,
      ];
      
      $ch = curl_init($baseurl . 'login');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $return = curl_exec($ch);
      $json_data = json_decode($return, true);
        
      //print_r($return);
      //die();
      
      $curl_error = curl_error($ch);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $data = $json_data['msg'];
   
      if ($json_data['code'] !== 200) 
      {
            echo "<script>window.location='index.php?action=error'</script>";
      }
      else 
      {
            
            $_SESSION[PRE_FIX.'sessionPortal'] = "foodAdmin";
            $_SESSION[PRE_FIX.'sessionTokon'] = time();
            $_SESSION[PRE_FIX.'id'] = $json_data['msg']['UserAdmin']['id'];
            //$_SESSION[PRE_FIX.'name'] = $json_data['msg']['UserAdmin']['first_name']." ".$json_data['msg']['UserAdmin']['last_name'];
            $_SESSION[PRE_FIX.'role'] = $json_data['msg']['UserAdmin']['role'];
            $_SESSION[PRE_FIX.'UserAdmin'] = $json_data['msg']['UserAdmin']['role_name'];
            
            //die();
            echo "<script>window.location='dashboard.php?p=users'</script>";
            
            
      }
      
}

if(@$_GET['p'] == "logout" ) 
{ 
	unset($_SESSION[PRE_FIX.'sessionPortal']);
	unset($_SESSION[PRE_FIX.'sessionTokon']);
	unset($_SESSION[PRE_FIX.'id']);
	unset($_SESSION[PRE_FIX.'role']);
	unset($_SESSION[PRE_FIX.'UserAdmin']);
	
	echo "<script>window.location='index.php'</script>";
}




function RemoveSpecialChar($value)
{
    $result  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value);
    return $result;
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


function checkImageExist($external_link)
{
    if (@getimagesize($external_link)) 
    {
        return 200;
    } 
    else 
    {
        return 201;
    }
}


?>