<?php 
require_once("constant.php");
session_start();
date_default_timezone_set('Asia/Karachi');


$baseurl = "https://www.weydrop.com/";

//dont modify the folders
$image_baseurl = $baseurl."mobileapp_api/";
$baseurl = $baseurl."mobileapp_api/publicSite";

$firebaseBaseURL = "https://weydrop-aa960.firebaseio.com/";
/*fb app id for auth*/
define("FACEBOOK_APP_ID", "711032406348379"); //logo size should be 130x30px
/*Google  map id https://console.developers.google.com/google/maps-apis/apis/ for auth*/
$mapgoogleapi ="AIzaSyB6y3kgq2FGNkoYyzkUcWtURVZMzpmpARw";
/*Google login id for auth*/
$googleappid = "120667586596-93604sn1nqn0cav8q6j0a57lsjml6u7q.apps.googleusercontent.com";

//enable/disable facebook login  
define("FACEBOOK_LOGIN_ENABLE","false");   // value should be true of false
//enable/disable google login
define("GOOGLE_LOGIN_ENABLE","false");   // value should be true of false

if( isset($_GET['log']) ) { //log

	if( $_GET['log'] == "in" ) { //login user

		$email = htmlspecialchars($_POST['eml'], ENT_QUOTES);
	    $password = htmlspecialchars($_POST['pswd'], ENT_QUOTES);
	    $returnlink=htmlspecialchars($_POST['returnlink'], ENT_QUOTES);
	    
	    $returnlink=str_replace("action=error","",$returnlink);
	    
	    
	    if( !empty($email) && !empty($password) ) {
            $endpoint = "/login";
            $data = array(
                "email" => $email,
                "password" => $password,
                "role" => "user"
            );
            $json_data = curl_request($data , $endpoint , $baseurl);

			if($json_data['code'] =="200")
			{
				
                $_SESSION['sessionTokon'] = time();
				$_SESSION['id'] = $json_data['msg']['UserInfo']['user_id'];
				$_SESSION['first_name'] = $json_data['msg']['UserInfo']['first_name'];
				$_SESSION['last_name'] = $json_data['msg']['UserInfo']['last_name'];
				$_SESSION['name'] = $json_data['msg']['UserInfo']['first_name']." ".$json_data['msg']['UserInfo']['last_name'];
				$_SESSION['phone'] = $json_data['msg']['UserInfo']['phone'];
				$_SESSION['device_token'] = $json_data['msg']['UserInfo']['device_token'];
				$_SESSION['online'] = $json_data['msg']['UserInfo']['online'];
				$_SESSION['email'] = $json_data['msg']['User']['email'];
				$_SESSION['active'] = $json_data['msg']['User']['active'];
				$_SESSION['user_type'] = $json_data['msg']['User']['role'];

	   			echo "<script>window.location='".$returnlink."'</script>";
			} 
			else 
			{
                echo "<script>window.location='index.php?action=error'</script>";
			}

		} else {
		    $_SESSION['error_msg'] = $json_data['msg'];
			echo "<script>window.location='index.php?action=error'</script>";
		}

	} //login user = end


	if( $_GET['log'] == "out" ) { //logout user

		session_destroy();
		header("Location: index.php");
   		echo "<script>window.location='index.php'</script>";

	} //logout user = end

} //log = end

if( isset($_GET['reg']) ) { //register user

	$first_name = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['paswd'], ENT_QUOTES);

	$ph = str_replace("(", "", $_POST['phne']);
	$ph1 = str_replace(")", "", $ph);
	$ph2 = str_replace("-", "", $ph1);
	$phone1 = $ph2;

    $phone = $phone1;

	$device_token = "";
	$role = "user";

	if( !empty($first_name) && !empty($last_name) && !empty($email) && !empty($password) && !empty($phone) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email, 
			"password" => $password, 
			"first_name" => $first_name, 
			"last_name" => $last_name, 
			"phone" => $phone, 
			"device_token" => $device_token, 
			"role" => $role
		);
        
        //echo json_encode($data); 
        
		$ch = curl_init( $baseurl.'/registerUser' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    //var_dump($json_data);

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $json_data['code'];
		//die;

		if($json_data['code'] !== 200)
		{
		    $_SESSION['error_msg'] = $json_data['msg'];
	   		echo "<script>window.location='index.php?action=error'</script>";

		} else {
	   		echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
	   	echo "<script>window.location='index.php?action=error'</script>";
	} //

} //register user = end

if( isset($_GET['forget']) ) { //forget password

    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);

	$device_token = "";
	$role = "user";

	if( !empty($email) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email,
			"role" => "user"
		);

		$ch = curl_init( $baseurl.'/forgotPassword' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    //var_dump($json_data);

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $json_data['code'];
		//die;

		if($json_data['code'] !== 200)
		{
			echo "<script>window.location='index.php?action=error'</script>";

		} else 
		{
			echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: index.php?action=error");
   		echo "<script>window.location='index.php?action=error'</script>";
	} //

} //forget password = end

function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function convertintotime($datetime){

 $date = new DateTime($datetime);
 $new_date_format = $date->format('Y-m-d h:i A');
 return $new_date_format;

}
/*
 * $data  contains array of all the parameters
 * $endpoint contains endpoint for the curl request
 * $baseurl contains base url of the request api.
 * */
function curl_request($data , $endpoint ,  $baseurl){
    $headers = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );
   
    $ch = curl_init( $baseurl.$endpoint );
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