<?php
    include("config.php");

    if(@$_GET['action']=="orderNotification") 
    {
        
        $url=$baseurl . '/showRestaurantOrders';
        $user_id = $_SESSION[PRE_FIX.'restaurant_id'];
		$status = "1";
		$data = array(
			"user_id" => $user_id,
			"status" => $status
		);
		
        $headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);
		
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$return = curl_exec($ch);

		$json_return = json_decode($return, true);
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        
        if($json_return['code'] !== 200) 
        {
        	//echo "Something went wrong1";
        } 
        else 
        {
            $lastOrder=$json_return['msg'][0]['Order']['id'];
            
            $oldOrder=$_SESSION[PRE_FIX.'currentOrder'];
            
            
            if($oldOrder != $lastOrder)
            {
                $_SESSION[PRE_FIX.'currentOrder']=$lastOrder;
                ?>
                    
                    <a href="dashboard.php?p=hotel_order&page=liveOrders" style="font-size:12px; text-decoration:none;">
                        <span style="background: #BE2C2C;border-radius: 20px;padding: 5px 10px;color: white;">
                            <span class="fa fa-refresh"></span>
                            Refresh For New Orders
                        </span>
                    </a>
                    
                    <iframe src="mediaplayer.html"
                        allow="autoplay" style="display: none;">
                    </iframe>

                    
                <?php
            }
            
        }
    
    
    }
    else if(@$_GET['action']=="updateRestaurantStatus") 
    {
        
        $url=$baseurl . '/updateRestaurantStatus';
        $user_id = $_SESSION[PRE_FIX.'restaurant_id'];
        $status = $_GET['status'];
        
        $data = array(
            "user_id" => $user_id,
            "status" => $status
        );
        
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json"
        );
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $return = curl_exec($ch);

        $json_return = json_decode($return, true);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        echo "<pre>";print_r($json_return);die;
        if($json_return['code'] !== 200) 
        {
            echo "Something went wrong".$baseurl;
        } 
        else 
        {
            echo "status updated successfully".$baseurl;
            
        }
    
    
    }

?>