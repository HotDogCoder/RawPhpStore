<?php

include("config.php");


$userid = @$_SESSION[PRE_FIX.'restaurant_id'];

    if(isset($_GET['p']) && isset($_GET['add'])) 
	{
		if($_GET['p']=="manage_menu" && $_GET['add']=="menu") {
			
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$image = file_get_contents($_FILES['menu_image']['tmp_name']);
            $image = base64_encode($image);
         
			if( !empty($name) && !empty($description) ) { 
			

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"user_id" => $userid,
					"name" => $name,
					"description" => $description,
					"image" => array("file_data" => $image)
				);
                
				$ch = curl_init( $baseurl.'/addMenu' );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$return = curl_exec($ch);

				$json_data = json_decode($return, true);
			    
				$curl_error = curl_error($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                
				if($json_data['code'] !== 200){
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
	                 
				} else {
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);
			} 
			else 
			{
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu = end


		if($_GET['add']=="menuitem") {
	        $user_id = $userid;
	        //$user_id = htmlspecialchars($resturentid, ENT_QUOTES);
			$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$image = file_get_contents($_FILES['menu_image']['tmp_name']);
            $image = base64_encode($image);

			if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"restaurant_menu_id" => $restaurant_menu_id,
					"name" => $name,
					"description" => $description,
					"price" => $price,
					"out_of_order" => "0",
					"image" => array("file_data" => $image)
				);

				$ch = curl_init( $baseurl.'/addMenuItem' );

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
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					//?userid=51&resid=3&action=success
					echo "<script>window.location='dashboard.php?p=manage_menu&resid=".$resturentid."&userid=".$userid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu item = end


		if($_GET['add'] == "menuextrasection") {
			$user_id = $userid;

			//$restaurant_id = htmlspecialchars($_POST['restoid'], ENT_QUOTES);
		    $user_id = $userid;
			$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
			$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
			
			if(isset($_POST['require_items'])) {
				$required = "1";
			} else {
				$required = "0";
			}

			if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"user_id" => $user_id,
					"name" => $name,
					"restaurant_menu_item_id" => $restaurant_menu_item_id,
					"required" => $required
				);

				$ch = curl_init( $baseurl.'/addMenuExtraSection' );

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
					//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra section = end


		if($_GET['add'] == "menuextraitem") {
			//$user_id = $userid;

			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

			if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"name" => $name,
					"price" => $price,
					"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
				);

				$ch = curl_init( $baseurl.'/addMenuExtraItem' );

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
					//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} 
			else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra item = end
	}
	
	
	if(isset($_GET['edit'])) {
		if($_GET['edit']=="menu") {
			//$user_id = $userid;
			
			$id = $_POST['rid'];
			$user_id = $userid;
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
            
            
            
            if(!empty($_FILES['menu_image']['tmp_name']))
            {
                $image = file_get_contents($_FILES['menu_image']['tmp_name']);
                $image = base64_encode($image); 
                
                $data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description,
					"image" => array("file_data" => $image)
				);
            }
            else
            {
                $data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description
				);
            }
            
			if( !empty($name) && !empty($description) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				

				$ch = curl_init( $baseurl.'/addMenu' );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$return = curl_exec($ch);

				$json_data = json_decode($return, true);
			    //var_dump($data);

				$curl_error = curl_error($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				//echo $json_data['code'];
				//die;

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu = end


		if($_GET['edit']=="menuitem") {
			$user_id = $userid;
	        
	        $id = $_POST['rid'];
			$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$outofstock = htmlspecialchars(@$_POST['outofstock'], ENT_QUOTES);
			
			if($outofstock=="")
			{
				$outofstock = "0";
			}
		
			if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) 
			{ 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);
                
                if(!empty($_FILES['menu_image']['tmp_name']))
                {
                    $image = @file_get_contents($_FILES['menu_image']['tmp_name']);
                    $image = base64_encode($image);
                    
                    $data = array(
    					"id" => $id,
    					"restaurant_menu_id" => $restaurant_menu_id,
    					"name" => $name,
    					"description" => $description,
    					"price" => $price,
    					"out_of_order" => $outofstock,
    					"image" => array("file_data" => $image)
    				);
                }
                else
                {
                    $data = array(
    					"id" => $id,
    					"restaurant_menu_id" => $restaurant_menu_id,
    					"name" => $name,
    					"description" => $description,
    					"price" => $price,
    					"out_of_order" => $outofstock
    				);
                }
                
                
				
				$ch = curl_init( $baseurl.'/addMenuItem' );

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
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					///@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu item = end


		if($_GET['edit'] == "menuextrasection") {
			$user_id = $userid;

			$id = $_POST['rid'];
		    $user_id = $userid;
			$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
			$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
			
			if(isset($_POST['require_items'])) {
				$required = "1";
			} else {
				$required = "0";
			}
			
			if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"restaurant_menu_item_id" => $restaurant_menu_item_id,
					"required" => $required
				);

				$ch = curl_init( $baseurl.'/addMenuExtraSection' );

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
					//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=erroraction=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra section = end


		if($_GET['edit'] == "menuextraitem") {
			$user_id = $userid;
			
			$id = $_POST['rid'];
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

			if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);

				$data = array(
					"id" => $id,
					"name" => $name,
					"price" => $price,
					"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
				);

				$ch = curl_init( $baseurl.'/addMenuExtraItem' );

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
					//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
					//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra item = end
	}
	
	
	
	
	
	if(@$_GET['removeMenu']=="ok")
	{
				
				$user_id = $userid;
			
				$menu_id = $_GET['menuID'];
				$restaurant_id = $_GET['resid'];
				$active = $_GET['active'];
	
				
	
					$headers = array(
					"Accept: application/json",
					"Content-Type: application/json",
					"api-key: ".API_KEY." "
				);
	
					$data = array(
						"menu_id" => $menu_id,
						"restaurant_id" => $restaurant_id,
						"active" => $active
					);
					
					$ch = curl_init( $baseurl.'/deleteMainMenu' );
	
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
					$return = curl_exec($ch);
	
					$json_data = json_decode($return, true);
					$curl_error = curl_error($ch);
					$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
					if($json_data['code'] == "200")
					{
						echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
	
					} else 
					{
						echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";
					}
	
					curl_close($ch);
		}
	else
	if(@$_GET['removeMenuItem']=="ok")
	{
			
			$user_id = $userid;
		
			$menu_id = $_GET['menuItemID'];
			$restaurant_id = $_GET['resid'];
			$active = $_GET['active'];

			

				$headers = array(
				"Accept: application/json",
				"Content-Type: application/json",
				"api-key: ".API_KEY." "
			);

				$data = array(
					"menu_item_id" => $menu_id,
					"restaurant_id" => $restaurant_id,
					"active" => $active
				);

				$ch = curl_init( $baseurl.'/deleteMenuItem' );
				
				//print_r(json_encode($data));
				//die();
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
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";

				} else 
				{
				    echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
				}

				curl_close($ch);
	}
	else
	if(@$_GET['removeMenuSection']=="ok")
	{
			
			$user_id = $userid;
		
			$menu_extra_section_id = $_GET['sectionID'];
			$restaurant_id = $_GET['resid'];
			$active = $_GET['active'];

			

				$headers = array(
				"Accept: application/json",
				"Content-Type: application/json",
				"api-key: ".API_KEY." "
			);

				$data = array(
					"menu_extra_section_id" => $menu_extra_section_id,
					"restaurant_id" => $restaurant_id,
					"active" => $active
				);

				$ch = curl_init( $baseurl.'/deleteMenuExtraSection' );
				
				//print_r(json_encode($data));
				//die();
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
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";

				} else 
				{
				    echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
				}

				curl_close($ch);
	}
	else
	if(@$_GET['removeMenuSectionItem']=="ok")
	{
			
			$user_id = $userid;
			$menu_extra_item_id = $_GET['menu_extra_item_id'];
			$active = $_GET['active'];
        	$restaurant_id = $_GET['resid'];
			

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json",
				"api-key: ".API_KEY." "
		    );

			$data = array(
				"menu_extra_item_id" => $menu_extra_item_id,
				"active" => $active
			);

			$ch = curl_init( $baseurl.'/deleteMenuExtraItem' );
			
// 			print_r(json_encode($data));
// 			die();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
			//var_dump($json_data);
            // print_r($json_data);
            // die();
            
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200)
				{
					echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";

				} else 
				{
				    echo "<script>window.location='dashboard.php?p=manage_menu&userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
				}

			curl_close($ch);
	}
	
//edit section  = end
	
	
?>