<?php
    //all functions
    
    //add address
   
	if( isset($_GET['action']) ) 
    {
        if($_GET['action']=="add_address")
        {
            	$apartment = htmlspecialchars($_POST['aprt'], ENT_QUOTES);
            	$street = htmlspecialchars($_POST['str'], ENT_QUOTES);
        		$city = htmlspecialchars($_POST['cty'], ENT_QUOTES);
        		$state = htmlspecialchars($_POST['stt'], ENT_QUOTES);
        		$zip = htmlspecialchars($_POST['zp'], ENT_QUOTES);
        		$country = htmlspecialchars($_POST['cntry'], ENT_QUOTES);
        		$instruction = htmlspecialchars($_POST['ins'], ENT_QUOTES);
        		$user_id = $_SESSION['id'];
        		$default = "1";
        		
        		$lat = htmlspecialchars($_POST['lat'], ENT_QUOTES);  //lattitude
        		$lng = htmlspecialchars($_POST['lng'], ENT_QUOTES); //longitude
        
        		if( !empty($street) && !empty($city) && !empty($state) && !empty($zip) && !empty($country) && !empty($instruction) && !empty($lat) && !empty($lng) ) {
        
        			$data = array(
        				"street" => $street,
        				"city" => $city,
        				"state" => $state,
        				"zip" => $zip,
        				"country" => $country,
        				"instructions" => $instruction,
        				"user_id" => $user_id,
        				"default" => $default,
        				"lat" => $lat,
        				"long" => $lng,
        				"apartment" => $apartment
        			);
                    $endpoint = "/addDeliveryAddress";
        
                    $json_data = @curl_request($data, $endpoint, $baseurl);
                
        			if($json_data['code'] == 200)
        			{
        			   echo "<script>window.location='dashboard.php?p=address&action=success'</script>";
        			} 
        			else 
        			{
        			   $_SESSION['error_msg'] = $json_data['msg'];
        			   echo "<script>window.location='dashboard.php?p=address&action=error'</script>";
        			}
        
        		} 
        		else 
        		{
                	echo "<script>window.location='dashboard.php?p=address&action=error'</script>";
        		} 
        } 
		else
		if($_GET['action']=="add_address_new")
        {
            	$apartment = htmlspecialchars($_POST['aprt'], ENT_QUOTES);
            	$street = htmlspecialchars($_POST['str'], ENT_QUOTES);
        		$city = htmlspecialchars($_POST['cty'], ENT_QUOTES);
        		$state = htmlspecialchars($_POST['stt'], ENT_QUOTES);
        		$zip = htmlspecialchars($_POST['zp'], ENT_QUOTES);
        		$country = htmlspecialchars($_POST['cntry'], ENT_QUOTES);
        		$instruction = htmlspecialchars($_POST['ins'], ENT_QUOTES);
        		$user_id = $_SESSION['id'];
        		$default = "1";
        		
        		$lat = htmlspecialchars($_POST['lat'], ENT_QUOTES);  //lattitude
        		$lng = htmlspecialchars($_POST['lng'], ENT_QUOTES); //longitude
        
        		if( !empty($street) && !empty($city) && !empty($state) && !empty($zip) && !empty($country) && !empty($instruction) && !empty($lat) && !empty($lng) ) {
        
        			$data = array(
        				"street" => $street,
        				"city" => $city,
        				"state" => $state,
        				"zip" => $zip,
        				"country" => $country,
        				"instructions" => $instruction,
        				"user_id" => $user_id,
        				"default" => $default,
        				"lat" => $lat,
        				"long" => $lng,
        				"apartment" => $apartment
        			);
                    $endpoint = "/addDeliveryAddress";
        
                    $json_data = @curl_request($data, $endpoint, $baseurl);
                
        			if($json_data['code'] == 200)
        			{
						$data = array(
							"user_id" => $user_id
						);

						$endpoint = "/getDeliveryAddresses";
						$json_data = curl_request($data, $endpoint, $baseurl);
						if($json_data['code'] !== 200)
						{
							echo "error";
						} 
						else {
							echo $json_data['msg'][0]['Address']['id'];
						}
        			} 
        			else 
        			{
        			   echo "error: ".$json_data['msg'];
        			}
        
        		} 
        		else 
        		{
                	echo "error";
        		} 
        }
        else
        if($_GET['action']=="add_payment")
        {
            $user_id = $_SESSION['id'];
        	$name = htmlspecialchars($_POST['cardname'], ENT_QUOTES);
        	$card = htmlspecialchars($_POST['cardnum'], ENT_QUOTES);
        	$cvc = htmlspecialchars($_POST['cardcvc'], ENT_QUOTES);
        	$exp_month = htmlspecialchars($_POST['cardmn'], ENT_QUOTES);
        	$exp_year = htmlspecialchars($_POST['cardyr'], ENT_QUOTES);
        	$default = "0";
        
        	if( !empty($name) && !empty($card) && !empty($cvc) && !empty($exp_month) && !empty($exp_year) ) { 
        
        		$data = array(
        			"user_id" => $user_id,
        			"name" => $name,
        			"card" => $card,
        			"cvc" => $cvc,
        			"exp_month" => $exp_month,
        			"exp_year" => $exp_year,
        			"default" => $default
        		);
                $endpoint = "/addPaymentMethod";
        
                $json_data = curl_request($data, $endpoint, $baseurl);
        
        
        		if($json_data['code'] !== 200)
        		{
        			$_SESSION['error_msg'] = $json_data['msg'];
        			echo "<script>window.location='dashboard.php?p=payment&action=error'</script>";
        
        		} else {
        			echo "<script>window.location='dashboard.php?p=payment&action=success'</script>";
        		}
        
        
        
        	} else {
        		echo "<script>window.location='dashboard.php?p=payment&action=error'</script>";
        	}
        }
        else
        if($_GET['action']=="changePassword")
        {
            $user_id = $_SESSION['id'];
			$old_password = htmlspecialchars($_POST['oldpas'], ENT_QUOTES);
			$new_password = htmlspecialchars($_POST['newpas'], ENT_QUOTES);
			$renewpas = htmlspecialchars($_POST['renewpas'], ENT_QUOTES);

			if( !empty($old_password) && !empty($new_password) ) {

				if( $new_password == $renewpas ) 
				{ //validate new pass with re new pass


					$data = array(
						"user_id" => $user_id,
						"old_password" => $old_password,
						"new_password" => $new_password
					);


                    $endpoint = "/changePassword";


                    $json_data = curl_request($data, $endpoint, $baseurl);


					if($json_data['code'] !== 200){
						echo "<script>window.location='dashboard.php?p=changepassword&action=error'</script>";

					} else {
						echo "<script>window.location='dashboard.php?p=changepassword&action=success'</script>";
					}


				} //validate new pass with re new pass = end

			} else {
				echo "<script>window.location='dashboard.php?p=address&edit=info&id=".$id."&action=error'</script>";
			}
        }
        else
        if($_GET['action']=="updateProfile")
        {
            $user_id = $_SESSION['id'];
        	$first_name = htmlspecialchars($_POST['fname'], ENT_QUOTES);
        	$last_name = htmlspecialchars($_POST['lname'], ENT_QUOTES);
        
        	if( !empty($first_name) && !empty($last_name) ) {
        
                $endpoint = "/editUserProfile";
        		$data = array(
        			"user_id" => $user_id,
        			"first_name" => $first_name,
        			"last_name" => $last_name
        		);
        
                $json_data = curl_request($data, $endpoint, $baseurl);
        
        
        
        		if($json_data['code'] !== 200)
        		{
        			echo "<script>window.location='dashboard.php?p=account&action=error'</script>";
                } else {
        			$_SESSION['first_name'] = $first_name;
        			$_SESSION['last_name'] = $last_name;
        			$_SESSION['name'] = $first_name." ".$last_name;
        			echo "<script>window.location='dashboard.php?p=account&action=success'</script>";
        		}
        
        
        	} else {
        		echo "<script>window.location='dashboard.php?p=account&action=error'</script>";
        	}
        }
    	 
    
    }

?>