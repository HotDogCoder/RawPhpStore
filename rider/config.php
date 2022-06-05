<?php 
ini_set('session.gc_maxlifetime',12*60*60);
ini_set('session.cookie_lifetime',12*60*60);
@session_start();

//API host link api url should be  https://domain.com/your path  OR http://domain.com/your path other wise it will be your configration error 
$baseurl = "https://www.weydrop.com/";

//dont change any thing here
$image_baseurl=$baseurl."mobileapp_api/";
$baseurl = $baseurl."mobileapp_api/publicSite";
define('PRE_FIX' , "foodiesRider_");



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
				"role" => "rider"
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

			//echo $json_data['code'];
			//die;
			curl_close($ch);

			if($json_data['code'] !== 200){
			
	   			echo "<script>window.location='index.php?action=error'</script>";

			} else {

				if( $json_data['msg']['User']['role'] == "rider" ) 
				{
					
					$_SESSION[PRE_FIX.'sessionTokon'] = time();
					$_SESSION[PRE_FIX.'rider'] = "rider";
					$_SESSION[PRE_FIX.'rider_id'] = $json_data['msg']['UserInfo']['user_id'];
					$_SESSION[PRE_FIX.'first_name'] = $json_data['msg']['UserInfo']['first_name'];
					$_SESSION[PRE_FIX.'last_name'] = $json_data['msg']['UserInfo']['last_name'];
					$_SESSION[PRE_FIX.'name'] = $json_data['msg']['UserInfo']['first_name']." ".$json_data['msg']['UserInfo']['last_name'];
					$_SESSION[PRE_FIX.'user_type'] = $json_data['msg']['User']['role'];
                    
                    $_SESSION[PRE_FIX.'phone'] = $json_data['msg']['UserInfo']['phone'];
				    $_SESSION[PRE_FIX.'email'] = $json_data['msg']['User']['email'];
				
		   			echo "<script>window.location='dashboard.php?p=summary'</script>";

				}
				else 
				{
					
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
		unset($_SESSION[PRE_FIX.'rider']);
		unset($_SESSION[PRE_FIX.'rider_id']);
		unset($_SESSION[PRE_FIX.'first_name']);
		unset($_SESSION[PRE_FIX.'last_name']);
		unset($_SESSION[PRE_FIX.'name']);
		unset($_SESSION[PRE_FIX.'user_type']);
		unset($_SESSION[PRE_FIX.'phone']);
		unset($_SESSION[PRE_FIX.'email']);
		
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

		$ch = curl_init($baseurl.'/forgotPassword');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($json_data['code'] !== 200)
		{
			echo "<script>window.location='index.php?action=error'</script>";

		} 
		else 
		{
			echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} 
	else 
	{
		echo "<script>window.location='index.php?action=error'</script>";
	}
	
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

function convertDateTimetoFullMonthAndDayNameWithYear($datetime){

    $date = new DateTime($datetime);
    $new_date_format = $date->format('F D d,Y');
    return $new_date_format; //February Tue 13,2018

}

//echo generateRandomString(6);
?>