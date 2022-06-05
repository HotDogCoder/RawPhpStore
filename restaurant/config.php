<?php 
ini_set('session.gc_maxlifetime',12*60*60);
ini_set('session.cookie_lifetime',12*60*60);
@session_start();
ini_set("display_errors", "Off");
error_reporting(0);
//API host link api url should be  https://domain.com/your folder path  OR http://domain.com/your folder path other wise it will be your configration error 
$baseurl = "http://dev.weydrop.com/";

//dont change any thing here
$image_baseurl=$baseurl."mobileapp_api/";
$baseurl = $baseurl."mobileapp_api/publicSite";
define('PRE_FIX' , "foodiesRestaurant_");


//google map javascript api key
define('Google_Map_Key', 'AIzaSyB6y3kgq2FGNkoYyzkUcWtURVZMzpmpARw');

//api key to secure your API,web portals & mobile app  WARNING:if you are changing it here you have to change same string in mobileapp_api too
define('API_KEY', '156c4675-9608-4591-b2ec-427503464aac');


if( isset($_GET['log']) ) { //log

	if( $_GET['log'] == "in" ) { //login user

		$email = htmlspecialchars($_POST['eml'], ENT_QUOTES);
	    $password = htmlspecialchars($_POST['pswd'], ENT_QUOTES);

	    if( !empty($email) && !empty($password) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"email" => $email, 
				"password" => $password,
				"role" => "vendor"
			);

			$ch = curl_init( $baseurl.'/login' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
         //   echo $baseurl.'/login';
         //echo "<pre>";
			//echo $json_data['code'];
		 
 		    // print_r($json_data);
 		  //   echo "</pre>";
	 	//die;
			curl_close($ch);

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in login user, try again later..</div>";
				@header("Location: index.php?action=error");
	   			echo "<script>window.location='index.php?action=error'</script>";

			} else {

				if( $json_data['msg']['User']['role'] == "hotel" or $json_data['msg']['User']['role'] == "store" ) { //hotel
					$_SESSION[PRE_FIX.'sessionTokon'] = time();
					$_SESSION[PRE_FIX.'restaurant_id'] = $json_data['msg']['UserInfo']['user_id'];
					$_SESSION[PRE_FIX.'restaurant'] = "restaurant";
					
					$_SESSION[PRE_FIX.'first_name'] = $json_data['msg']['UserInfo']['first_name'];
					$_SESSION[PRE_FIX.'last_name'] = $json_data['msg']['UserInfo']['last_name'];
					$_SESSION[PRE_FIX.'name'] = $json_data['msg']['UserInfo']['first_name']." ".$json_data['msg']['UserInfo']['last_name'];
					$_SESSION[PRE_FIX.'phone'] = $json_data['msg']['UserInfo']['phone'];
					$_SESSION[PRE_FIX.'device_token'] = $json_data['msg']['UserInfo']['device_token'];
					$_SESSION[PRE_FIX.'online'] = $json_data['msg']['UserInfo']['online'];
					$_SESSION[PRE_FIX.'email'] = $json_data['msg']['User']['email'];
					$_SESSION[PRE_FIX.'active'] = $json_data['msg']['User']['active'];
					$_SESSION[PRE_FIX.'user_type'] = $json_data['msg']['User']['role'];

					@header("Location: dashboard.php?p=hotel_order&page=liveOrders");
		   			echo "<script>window.location='dashboard.php?p=hotel_order&page=liveOrders'</script>";

				} //hotel = end
				else {
					@header("Location: index.php?action=error");
		   			echo "<script>window.location='index.php?action=error'</script>";
	   			}

			}

		} else {
			@header("Location: index.php?action=error");
   			echo "<script>window.location='index.php?action=error'</script>";
		} //

	} //login user = end


	if( $_GET['log'] == "out" ) { //logout user

		unset($_SESSION[PRE_FIX.'sessionTokon']);
		unset($_SESSION[PRE_FIX.'restaurant_id']);
		unset($_SESSION[PRE_FIX.'restaurant']);
		unset($_SESSION[PRE_FIX.'first_name']);
		unset($_SESSION[PRE_FIX.'last_name']);
		unset($_SESSION[PRE_FIX.'name']);
		unset($_SESSION[PRE_FIX.'phone']);
		unset($_SESSION[PRE_FIX.'device_token']);
		unset($_SESSION[PRE_FIX.'online']);
		unset($_SESSION[PRE_FIX.'email']);
		unset($_SESSION[PRE_FIX.'active']);
		unset($_SESSION[PRE_FIX.'user_type']);
		
		@header("Location: index.php");
   		echo "<script>window.location='index.php'</script>";

	} //logout user = end

} //log = end




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
			"email" => $email
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

		if($json_data['code'] !== 200){
			//echo "<div class='alert alert-danger'>Some error occured, try again later..</div>";
			@header("Location: index.php?action=error");
	   		echo "<script>window.location='index.php?action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully registered..</div>";
			@header("Location: index.php?action=success");
	   		echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: index.php?action=error");
   		echo "<script>window.location='index.php?action=error'</script>";
	} //

} //forget password = end

function RemoveSpecialChar($value)
{
    $result  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value);
    return $result;
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

function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//echo generateRandomString(6);
?>