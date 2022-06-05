<?php
include("config.php");
    
if(isset($_GET['action']))
{
    if($_GET['action']=="sendCode")
    {
        $ph = str_replace("(", "", $_GET['phnnum']);
		$ph1 = str_replace(")", "", $ph);
		$ph2 = str_replace("-", "", $ph1);
		$phone = $ph2;
				
		$phone_no = $phone;
		$verify = '0';


		$data = array(
			"phone_no" => $phone_no,
			"verify" => $verify
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

		$data = array(
			"phone_no" => $phone_no,
			"verify" => $verify,
			"code" => $code
		);

        $endpoint = "/verifyPhoneNo";
        $json_data = curl_request($data, $endpoint, $baseurl);
		if($json_data['code'] !== 200)
		{
			echo "Code not correct";
		} 
		else 
		{
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