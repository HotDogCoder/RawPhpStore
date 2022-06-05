<?php
include("config.php");
    
if(isset($_GET['action']))
{
    if($_GET['action']=="orderCancel")
    {
        $order_id = $_GET['order_id'];
        $description = $_GET['description'];
        $user_id = $_GET['user_id'];
				
		$data = array(
			"order_id" => $order_id,
			"description" => $description,
			"user_id" => $user_id
			
		);

        $endpoint = "/orderCancel";
        $json_data = curl_request($data, $endpoint, $baseurl);
      
		if($json_data['code'] !== 200)
		{
		    
		    echo $json_data['code'];
		}
		else
		{
		    	echo $json_data['code'];
		}
		
    	
		
    }
    
    if($_GET['action']=="sendCode")
    {
        $ph = str_replace("(", "", $_GET['phnnum']);
		$ph1 = str_replace(")", "", $ph);
		$ph2 = str_replace("-", "", $ph1);
		$phone = $ph2;
				
		$phone_no = $phone;
		$verify = '0';

		if(isset($_GET['login']) && $_GET['login'] == 1)
			$login = 1;
		else
			$login = 0;

		$data = array(
			"phone_no" => $phone_no,
			"verify" => $verify,
			'login' => $login
		);

        $endpoint = "/verifyPhoneNo";
        $json_data = curl_request($data, $endpoint, $baseurl);
        
    	if($json_data['code'] == "200")
		{
			echo $json_data['code'];
		} 
	    else 
	    {
			echo $json_data['msg'];
		}
		
    }
    else
    if($_GET['action']=="verifyCode")
    {
        $ph = str_replace("(", "", $_GET['phnnum']);
		$ph1 = str_replace(")", "", $ph);
		$ph2 = str_replace("-", "", $ph1);
		$phone = $ph2;

		$phone_no = $phone;
		$code = $_GET['codetoverify'];
		$verify = '1';

		if(isset($_GET['login']) && $_GET['login'] == 1)
			$login = $_GET['login'];
		else
			$login = 0;
		
		$data = array(
			"phone_no" => $phone_no,
			"verify" => $verify,
			"code" => $code
		);

        $endpoint = "/verifyPhoneNo";
        $json_data = curl_request($data, $endpoint, $baseurl);
        //echo "<pre>";print_r($json_data);die;
		if($json_data['code'] !== 200)
		{
			echo "Code not correct";
		} 
		else 
		{
			if($login == 1)
			{
				$name = explode(" ", $json_data['data']['UserInfo']['full_name']);

				$_SESSION['sessionTokon'] = time();
				$_SESSION['id'] = $json_data['data']['UserInfo']['user_id'];
				$_SESSION['first_name'] = $name[0];
				$_SESSION['last_name'] = $name[1];
				$_SESSION['name'] = $json_data['data']['UserInfo']['full_name'];
				$_SESSION['phone'] = $json_data['data']['UserInfo']['phone'];
				$_SESSION['device_token'] = $json_data['data']['UserInfo']['device_token'];
				$_SESSION['online'] = $json_data['data']['UserInfo']['online'];
				$_SESSION['email'] = $json_data['data']['User']['email'];
				$_SESSION['active'] = $json_data['data']['User']['active'];
				$_SESSION['user_type'] = $json_data['data']['User']['role'];
			}
			
			echo $json_data['code'];
		}
	
    }
    else
    if($_GET['action']=="forgetPassword")
    {
        $email = $_GET['email'];
	
		$data = array(
			"email" => $email
		);

        $endpoint = "/forgotPassword";
        $json_data = curl_request($data, $endpoint, $baseurl);
        
    	if($json_data['code'] == "200")
		{
			echo $json_data['code'];
		} 
	    else 
	    {
			echo $json_data['msg'];
		}
		
    }
    else
    if($_GET['action']=="forgetPasswordVerify")
    {
        $email = $_GET['email'];
        $code = $_GET['code'];
	
		$data = array(
			"email" => $email,
			"code" => $code
		);

        $endpoint = "/verifyforgotPasswordCode";
        $json_data = curl_request($data, $endpoint, $baseurl);
        
    	if($json_data['code'] == "200")
		{
			echo $json_data['code'];
		} 
	    else 
	    {
			echo $json_data['msg'];
		}
		
    }
    else
    if($_GET['action']=="changePassword")
    {
        $newPassword = $_GET['newPassword'];
        $email = $_GET['email'];
	
		$data = array(
			"email" => $email,
			"password" => $newPassword
		);

        $endpoint = "/changePasswordForgot";
        $json_data = curl_request($data, $endpoint, $baseurl);
        
        
    	if($json_data['code'] == "200")
		{
			echo $json_data['code'];
		} 
	    else 
	    {
	         print_r($json_data);
			echo $json_data['msg'];
		}
		
    }
}

?>