<?php if( isset($_SESSION[PRE_FIX.'restaurant_id']) && ($_SESSION[PRE_FIX.'user_type'] == "hotel" || $_SESSION[PRE_FIX.'user_type'] == "store") ){ ?>

<script>
$(document).ready(function(){
	$("input#phone").inputmask();
});




function Upload_image() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("image");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 90 && width == 90) {
                        
						//alert('Done image');
						$('input[type=file]#image').parent("label.uploadbtn").css('background-image','url('+image.src+')');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 90x90");
						document.getElementById("image").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}


function Upload_cover_image() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("cover_image");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 220 && width == 320) {
                        
						//alert('Done image');
						$('input[type=file]#cover_image').parent("label.uploadbtn").css('background-image','url('+image.src+')');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 320x220");
						document.getElementById("cover_image").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}
</script>

<h2 class="title">Settings</h2>
<br/>
<div class="left">
    <a href="dashboard.php?p=hotel_edit_profile&page=profileSetting" class="links_sublinks <?php if($_GET['page']=="profileSetting"){echo "links_sublinks_active";}?>">
		<span>Profile Setting</span>
	</a>
    
    <a  href="dashboard.php?p=hotel_edit_profile&page=restaurantProfile" class="links_sublinks  <?php if($_GET['page']=="restaurantProfile"){echo "links_sublinks_active";}?>" style="margin-left: 22px;">
        <span>Restaurant Profile</span>
        
    </a>
	
	<!--<a  href="dashboard.php?p=hotel_edit_profile&page=trackingCode" class="links_sublinks  <?php if($_GET['page']=="trackingCode"){echo "links_sublinks_active";}?>" style="margin-left: 22px;">-->
 <!--       <span>Tracking and Location</span>-->
        
 <!--   </a>-->

</div>
<div class="clear"></div>
<br/>

<div class="form">
	<div class="">
		<?php 
			if( isset($_GET['upd']) ) {

	            $searchReplaceArray = array(
	              '(' => '', 
	              ')' => '',
	              '-' => '',
	              ' ' => ''
	            );

	            for($i=0; $i<7; $i++) {
	                $opening_time[$i] = @$_POST['opening_time'][$i];
	                $closing_time[$i] = @$_POST['closing_time'][$i];
	                $day[$i] = @$_POST['day'][$i];

	                $restaurant_timings_details[] = array( 'opening_time' => $opening_time[$i], 'closing_time' => $closing_time[$i], 'day' => $day[$i] );
	            }

	            //$restaurant_id = "1";
	            $user_id = $_SESSION[PRE_FIX.'restaurant_id'];
	            $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
				$slogan = htmlspecialchars(@$_POST['slogan'], ENT_QUOTES);
				$about = htmlspecialchars(@$_POST['about'], ENT_QUOTES);
				
				$preparation_time = htmlspecialchars(@$_POST['preparation_time'], ENT_QUOTES);
				$min_order_price = htmlspecialchars(@$_POST['min_order_price'], ENT_QUOTES);
				$delivery_free_range = htmlspecialchars(@$_POST['delivery_free_range'], ENT_QUOTES);
				
				$p = htmlspecialchars(@$_POST['phone'], ENT_QUOTES);
				$ph = str_replace("(", "", $p);
				$ph = str_replace(")", "", $ph);
				$ph1 = str_replace("-", "", $ph);
				$phone = $ph1;
				$timezone = htmlspecialchars(@$_POST['timezone'], ENT_QUOTES);
				$state = htmlspecialchars(@$_POST['state'], ENT_QUOTES);
				$speciality = htmlspecialchars($_POST['speciality'], ENT_QUOTES);
				$city = htmlspecialchars(@$_POST['city'], ENT_QUOTES);
				//$currency = "USD";
				$google_analytics = htmlspecialchars(@$_POST['google_analytics'], ENT_QUOTES);
				$country = htmlspecialchars(@$_POST['country'], ENT_QUOTES);
				$zip = htmlspecialchars(@$_POST['zip'], ENT_QUOTES);
				$lat = htmlspecialchars(@$_POST['lat'], ENT_QUOTES);
				$long = htmlspecialchars(@$_POST['long'], ENT_QUOTES);
	            $restaurant_timing = $restaurant_timings_details;

	            if( !empty($user_id)) { 

		            //with image & cover image
		            if( isset($_FILES['image']) && $_FILES["image"]["name"] != "" && isset($_FILES['cover_image']) && $_FILES["cover_image"]["name"] != "" ) {
		            	$image_original = file_get_contents($_FILES['image']['tmp_name']);
	    				$image = base64_encode($image_original);

	    				$cover_image_original = file_get_contents($_FILES['cover_image']['tmp_name']);
	    				$cover_image = base64_encode($cover_image_original);

	    				$data = array(
						   	"user_id" => $user_id,
						   	"name" => $name, 
						   	"slogan" => $slogan, 
						   	"about" => $about, 
						   	"phone" => $phone, 
						   	"timezone" => $timezone, 
						   	"state" => $state, 
						   	"speciality" => $speciality, 
						   	//"promoted" => $promoted, 
						   	"city" => $city, 
						   	"preparation_time" => $preparation_time,
							"min_order_price" => $min_order_price,
							"delivery_free_range" => $delivery_free_range,
						   	"google_analytics" => $google_analytics,
						   	"country" => $country, 
						   	"zip" => $zip, 
						   	"lat" => $lat, 
						   	"long" => $long, 
			                "restaurant_timing" => $restaurant_timing,
			                "image" => array("file_data" => $image),
			                "cover_image" => array("file_data" => $cover_image)
						);
						//
		            }

		            //with image
		            else if( isset($_FILES['image']) && $_FILES["image"]["name"] != "" ) {
		            	$image_original = file_get_contents($_FILES['image']['tmp_name']);
	    				$image = base64_encode($image_original);

	    				$data = array(
						   	"user_id" => $user_id,
						   	"name" => $name, 
						   	"slogan" => $slogan, 
						   	"about" => $about, 
						   	"phone" => $phone, 
						   	"timezone" => $timezone, 
						   	"state" => $state, 
						   	"speciality" => $speciality, 
						   	//"promoted" => $promoted, 
						   	"city" => $city, 
						   	"preparation_time" => $preparation_time,
							"min_order_price" => $min_order_price,
							"delivery_free_range" => $delivery_free_range,
						   	"google_analytics" => $google_analytics,
						   	"country" => $country, 
						   	"zip" => $zip, 
						   	"lat" => $lat, 
						   	"long" => $long, 
			                "restaurant_timing" => $restaurant_timing,
			                "image" => array("file_data" => $image)
						);
						//
		            }
		            
		            

		            //with cover image
		            else if( isset($_FILES['cover_image']) && $_FILES["cover_image"]["name"] != "" ) {
		            	$cover_image_original = file_get_contents($_FILES['cover_image']['tmp_name']);
	    				$cover_image = base64_encode($cover_image_original);

	    				$data = array(
						   	"user_id" => $user_id,
						   	"name" => $name, 
						   	"slogan" => $slogan, 
						   	"about" => $about, 
						   	"phone" => $phone, 
						   	"timezone" => $timezone, 
						   	"state" => $state, 
						   	"speciality" => $speciality, 
						   	//"promoted" => $promoted, 
						   	"city" => $city, 
						   	"preparation_time" => $preparation_time,
							"min_order_price" => $min_order_price,
							"delivery_free_range" => $delivery_free_range,
						   	"google_analytics" => $google_analytics,
						   	"country" => $country, 
						   	"zip" => $zip, 
						   	"lat" => $lat, 
						   	"long" => $long, 
			                "restaurant_timing" => $restaurant_timing,
			                "cover_image" => array("file_data" => $cover_image)
						);
						//
		            }

		            //without image & cover image
		            else {
		            	$data = array(
						   	"user_id" => $user_id,
						   	"name" => $name, 
						   	"slogan" => $slogan, 
						   	"about" => $about, 
						   	"phone" => $phone, 
						   	"timezone" => $timezone, 
						   	"state" => $state, 
						   	"speciality" => $speciality, 
						   	//"promoted" => $promoted, 
						   	"city" => $city, 
						   	"preparation_time" => $preparation_time,
							"min_order_price" => $min_order_price,
							"delivery_free_range" => $delivery_free_range,
						   	"google_analytics" => $google_analytics,
						   	"country" => $country, 
						   	"zip" => $zip, 
						   	"lat" => $lat, 
						   	"long" => $long, 
			                "restaurant_timing" => $restaurant_timing
						);
						//
		            }

                    //echo json_encode($data);
		            //die();
		            
		            
				   $headers = array(
				    "Accept: application/json",
				    "Content-Type: application/json"
				   );
				   
					
				   $ch = curl_init( $baseurl.'/editRestaurant' );

				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				   
				   $return = curl_exec($ch);

					$json_data = json_decode($return, true);
    			    
    			    
    			    
					$curl_error = curl_error($ch);
					$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

					//var_dump($json_data['code']);
					//die;

					if($json_data['code'] !== 200){
						
						if(@$_GET['page']=="trackingCode")
						{
							echo "<script>window.location='dashboard.php?p=hotel_edit_profile&page=trackingCode&action=error'</script>";
						}
						else
						{
							echo "<script>window.location='dashboard.php?p=hotel_edit_profile&page=restaurantProfile&action=error'</script>";
						}
						

					} else {
						if(@$_GET['page']=="trackingCode")
						{
							echo "<script>window.location='dashboard.php?p=hotel_edit_profile&page=trackingCode&action=success'</script>";
						}
						else
						{
							echo "<script>window.location='dashboard.php?p=hotel_edit_profile&page=restaurantProfile&action=success'</script>";
						}
					}

					curl_close($ch);
				   ////

				} else {
				
					echo "<script>window.location='dashboard.php?p=hotel_edit_profile&action=error'</script>";
				} //

			}
		?>

		<?php 
		
		
		    //show details
			
            $user_id = $_SESSION[PRE_FIX.'restaurant_id'];
            
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"user_id" => $user_id
			);

			$ch = curl_init( $baseurl.'/showRestaurantDetail' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
			//var_dump($return);
			
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

           
		 
		    $data = array();
            $endpoint = "/showCountries";
        
            $country = curl_request($data, $endpoint, $baseurl);
            
			if($json_data['code'] == "202")
			{
				
				?>
                  
					<div class="form" style="padding:10px 0;">
						
						<?php
							if(@$_GET['page']=="profileSetting")
							{
								?>
									
									<div class="col50 twocll">
										<p>
											<input type="text" name="first_name" id="first_name"  required=''>
											<label alt='First Name' placeholder='First Name'></label>
										</p>
									</div>
									<div class="clear"></div>
									<div class="col50 twocll">
										<p>
											<input type="text" name="first_name" id="first_name" required=''>
											<label alt='Last Name' placeholder='Last Name'></label>
										</p>
									</div> 
									
									
									<div class="col50">
										<p>
											<input type="text" name="phone" id="phone" data-inputmask="'alias': 'phone'" required=''>
											<label alt='Phone' placeholder='Phone'></label>
										</p>
									</div>
									
									<div class="col50">
										<p>
											<input type="text" name="email" id="email" required=''>
											<label alt='Phone' placeholder='Phone'></label>
										</p>
									</div>
									
								<?php
							}
							else
							if(@$_GET['page']=="restaurantProfile")
							{
								?>
									<form method="post" class="hoteleditinfo" id="hoteleditinfo" action="dashboard.php?p=hotel_edit_profile&upd=ok" enctype="multipart/form-data">
										<div class="col50 left twocll">
											<label for="image" class="uploadbtn" style="background-image: url();">
												<h3>Select Image</h3>
												<input name="image" id="image" onchange="" />
											</label>
										</div>
										<div class="col50 right twocll">
											<label for="cover_image" class="uploadbtn" style=";">
												<h3>Select Cover Image</h3>
												<input name="cover_image" id="cover_image" onchange="" />
											</label>
										</div> <div class="clear"></div>
			
										<div class="col50 left twocll">
											<p><input type="text" name="name" id="name" required=''><label alt='Name' placeholder='Name'></label></p>
										</div>
										<div class="col50 right twocll">
											<p><input type="text" name="slogan" id="slogan" required=''><label alt='Slogan' placeholder='Slogan'></label></p>
										</div> 
										<div class="clear"></div>
										<p><textarea name="about" id="about" rows="6" placeholder='About Restaurant' required=''></textarea><label alt='About' placeholder='About'></label></p>
										
										<div class="cl25">
											
											<div class="col25 left">
												<p><input type="text" name="phone" id="phone" data-inputmask="'alias': 'phone'" required=''><label alt='Phone' placeholder='Phone'></label></p>
											</div> 
											<div class="col25 left">
											    <p>
												    <select name="delivery_free_range" class="form-control" required="">
                                                    <option value="">Select KM Range</option>
                                
                                                    <?php
                                
                                                        for($i = 1; $i<=49; $i++) {
                                                            ?>
                                                                <option value="<?php echo $i; ?>"><?php echo $i; ?> KM</option>
                                                            <?php
                                                        }
                                
                                                    ?>
                                
                                               </select>
                                                </p>
											</div> 
											<div class="col25 left">
												<p>
												    <select name="country" id="cntry">
                                                        <option value="">Select Country</option required>
                                                        <?php
                                                            foreach($country['currency'] as $cntry) 
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Currency']['country']; ?>"><?php echo $cntry['Currency']['country']; ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </p>
											</div> 
											<div class="col25 left" style="display:none;">
												<?php $timezone = $vaaal['Restaurant']['timezone']; ?>
												<p><input type="text" name="timezone" id="timezone"  required=''><label alt='timezone' placeholder='Timezone'></label></p>
											</div> 
											
											<div class="col25 left">
											    <p>
												    <select name="state" id="state">
                                                        <option value="">Select State</option required>
                                                        <?php 
                                                            foreach($country['taxes'] as $cntry) 
                                                            {
                                    	                        ?>
                                    	                            <option value="<?php echo $cntry['Tax']['state']; ?>"><?php echo $cntry['Tax']['state']; ?></option>
                                    	                        <?php
                                    	                    }
                                    	                ?>
                                                    </select>
                                                </p>
											</div> 
											
											<div class="clear"></div>
											
											<div class="col25 left">
											    <p>
												    <select name="city" id="city">
                                                        <option value="">Select City</option required>
                                                        <?php 
                                                            foreach($country['taxes'] as $cntry) 
                                                            {
                                    	                        ?>
                                    	                            <option value="<?php echo $cntry['Tax']['city']; ?>"><?php echo $cntry['Tax']['city']; ?></option>
                                    	                        <?php
                                    	                    }
                                    	                ?>
                                                    </select>
                                                </p>
											</div> 
											
											<div class="col25 left">
											    <p>
												    <select name="min_order_price" id="min_order_price">
                                                        <option value="">Min Order Amount</option>

                                                        <?php
                                    
                                                            for($i = 1; $i<=999; $i++) {
                                                                ?>
                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                <?php
                                                            }
                                    
                                                        ?>
                                                    </select>
                                                </p>
											</div> 
											<div class="col25 left">
											    <p>
												    <select name="preparation_time" class="form-control" required="">
                                                        <option value="">AVG Food Prepation Time</option>
                                                        <option value="30">30 min</option>
                                                        <option value="40">40 min</option>
                                                        <option value="50">50 min</option>
                                                        <option value="60">60 min</option>
                                                   </select>
                                                </p>
											</div> 
											<div class="col25 left twocll">
    											<p><input type="text" name="speciality" id="speciality" required=''><label alt='Speciality' placeholder='Speciality'></label></p>
    										</div>
											
											<div class="clear"></div>
											
											
										</div>
										
										<div class="cl25" style="display:none1;">
											<div class="col25 left">
												<p><input type="text" name="zip" id="zip" required=''><label alt='ZIP' placeholder='ZIP'></label></p>
											</div> 
											
											<div class="col25 left">
												<p><input type="text" id="address" value="" required=''><label alt='Address' placeholder='Address'></label></p>
											</div> 
											
											<div class="col25 left">
												<p><input type="text" name="lat" id="lat" required=''><label alt='Lattitude' placeholder='Lattitude'></label></p>
											</div> 
											<div class="col25 left">
												<p><input type="text" name="long" id="long" required=''><label alt='Longitude' placeholder='Longitude'></label></p>
											</div> 
											<div class="clear"></div>
											
										</div>
			                            
			                            <div class="mapdiv" style="margin:0px auto;">
											<div id="geo_button" onClick="getLocation()" style="position: absolute; right: 1%; bottom: 29%; cursor: pointer; z-index: 2147483647!important; background-color: white; width: 40px; height: 40px; border-radius: 2px;">
												<i class="fa fa-map-marker fa-2x" aria-hidden="true" 
													style="margin-top: margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); color: rgba(0,0,0,0.7);">
												</i>
											</div>
											<div id="us2" style="width: 100%; height: 400px;"></div>
										</div>
										<script>
											$('#us2').locationpicker({
												location: { latitude:"" , longitude:"" },
												radius: 5,
												scrollwheel: true,
												inputBinding: {
													latitudeInput: $('#lat'),
													longitudeInput: $('#long'),
													locationNameInput: $('#address')
												},
												enableAutocomplete: true,
												onchanged: function (currentLocation, radius, isMarkerDropped) {
													var addressComponents = $(this).locationpicker('map').location;
													var lat = addressComponents.latitude;
													var lng = addressComponents.longitude;
													var adr = addressComponents.formattedAddress;
													//console.log("Latitude: " +lat+ " longitude: " +lng+ " address: " +adr);
													//jQuery(".mapdiv").slideDown();
													$('#us2').locationpicker('autosize');
												}
											});
										</script>
										
			                            <div>
											<a style="cursor: pointer;" onClick="javascript:jQuery('#settime').show();">
												<button type="button" class="button" style="width: 100%;">Set time schedule</button>
											</a>
										</div> 
										
										
										<style type="text/css">
											#settime .timediv {
												box-shadow: 0 0 4px 0 rgba(0,0,0,0.2);
											}
											
										</style>
										<div id="settime" class="popup">
											<div class="popup_container">
												<a href="javascript:;" id="close" onClick="javascript:jQuery('#settime').hide();">&times;</a>
												<div class="schedulee" style="padding: 40px;">
													<h2 style="margin: 0 0 20px;">Set Time Schedule</h2>
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Monday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Monday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][0]['opening_time'])) { echo $vaaal['RestaurantTiming'][0]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot1" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																	<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][0]['closing_time'])) { echo $vaaal['RestaurantTiming'][0]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct1" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Tuesday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Tuesday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][1]['opening_time'])) { echo $vaaal['RestaurantTiming'][1]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot2" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][1]['closing_time'])) { echo $vaaal['RestaurantTiming'][1]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct2" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Wednesday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Wednesday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][2]['opening_time'])) { echo $vaaal['RestaurantTiming'][2]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot3" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][2]['closing_time'])) { echo $vaaal['RestaurantTiming'][2]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct3" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
													
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Thursday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Thursday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" id="ot4" value="<?php if(!empty($vaaal['RestaurantTiming'][3]['opening_time'])) { echo $vaaal['RestaurantTiming'][3]['opening_time']; } ?>" class="timepicker" name="opening_time[]" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][3]['closing_time'])) { echo $vaaal['RestaurantTiming'][3]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct4" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Friday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Friday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][4]['opening_time'])) { echo $vaaal['RestaurantTiming'][4]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot5" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][4]['closing_time'])) { echo $vaaal['RestaurantTiming'][4]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct5" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Saturday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Saturday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][5]['opening_time'])) { echo $vaaal['RestaurantTiming'][5]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot6" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][5]['closing_time'])) { echo $vaaal['RestaurantTiming'][5]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct6" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
									
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Sunday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Sunday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][6]['opening_time'])) { echo $vaaal['RestaurantTiming'][6]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot7" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][6]['closing_time'])) { echo $vaaal['RestaurantTiming'][6]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct7" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
			
													<br>
													<a href="javascript:;" onclick="javascript:jQuery('#settime').hide();" style=" display: inline-block; width: 100%; text-decoration: none; "><button type="button" class="button" style=" width: 100% !important; display: block !important; ">Save Changes</button></a>
												</div>
											</div>
										</div>
						                <br>
									</form>
								<?php
							}
							
						?>
						
					</div>
				<?php

			} 
			else 
			{
				//echo "<div class='alert alert-success'>Successfully payment method updated..</div>";
				//@header("Location: dashboard.php?p=payment");
				foreach( $json_data['msg'] as $stttr => $vaaal ) {
					//var_dump($vaaal);
					?>
                  
					<div class="form" style="padding:10px 0;">
						
						<?php
							if(@$_GET['page']=="profileSetting")
							{
								?>
									
									<div class="col50 twocll">
										<p>
											<input type="text" name="first_name" id="first_name" value="<?php echo $vaaal['UserInfo']['first_name']; ?>" required=''>
											<label alt='First Name' placeholder='First Name'></label>
										</p>
									</div>
									<div class="clear"></div>
									<div class="col50 twocll">
										<p>
											<input type="text" name="first_name" id="first_name" value="<?php echo $vaaal['UserInfo']['last_name']; ?>" required=''>
											<label alt='Last Name' placeholder='Last Name'></label>
										</p>
									</div> 
									
									
									<div class="col50">
										<p>
											<input type="text" name="phone" id="phone" data-inputmask="'alias': 'phone'" value="<?php echo $vaaal['UserInfo']['phone']; ?>" required=''>
											<label alt='Phone' placeholder='Phone'></label>
										</p>
									</div>
									
									<div class="col50">
										<p>
											<input type="text" name="email" id="email" value="<?php echo $vaaal['User']['email']; ?>" required=''>
											<label alt='Phone' placeholder='Phone'></label>
										</p>
									</div>
									
								<?php
							}
							else
							if(@$_GET['page']=="restaurantProfile")
							{
								?>
									<form method="post" class="hoteleditinfo" id="hoteleditinfo" action="dashboard.php?p=hotel_edit_profile&upd=ok" enctype="multipart/form-data">
										<div class="col50 left twocll">
											<label for="image" class="uploadbtn" style="background-image: url(<?php echo $image_baseurl.$vaaal['Restaurant']['image']; ?>);">
												<h3>Select Image</h3>
												<input name="image" id="image" onchange="" />
											</label>
										</div>
										<div class="col50 right twocll">
											<label for="cover_image" class="uploadbtn" style="background-image: url(<?php echo $image_baseurl.$vaaal['Restaurant']['cover_image']; ?>);">
												<h3>Select Cover Image</h3>
												<input name="cover_image" id="cover_image" onchange="" />
											</label>
										</div> <div class="clear"></div>
			
										<div class="col50 left twocll">
											<p><input type="text" name="name" id="name" value="<?php echo $vaaal['Restaurant']['name']; ?>" required=''><label alt='Name' placeholder='Name'></label></p>
										</div>
										<div class="col50 right twocll">
											<p><input type="text" name="slogan" id="slogan" value="<?php echo $vaaal['Restaurant']['slogan']; ?>" required=''><label alt='Slogan' placeholder='Slogan'></label></p>
										</div> 
										<div class="clear"></div>
										<p><textarea name="about" id="about" rows="6" placeholder='About Restaurant' required=''><?php echo $vaaal['Restaurant']['about']; ?></textarea><label alt='About' placeholder='About'></label></p>
										
										<div class="cl25">
											
											<div class="col25 left">
												<p><input type="text" name="phone" id="phone" data-inputmask="'alias': 'phone'" value="<?php echo $vaaal['Restaurant']['phone']; ?>" required=''><label alt='Phone' placeholder='Phone'></label></p>
											</div> 
											<div class="col25 left">
											    <p>
												    <select name="delivery_free_range" class="form-control" required="">
                                                    <option value="">Select KM Range</option>
													<option value="0" <?php if($vaaal['Restaurant']['delivery_free_range']==0){ echo "selected";} ?>>Non Free Delivery</option>
                                                    <?php
                                
                                                        for($i = 1; $i<=49; $i++) {
                                                            ?>
                                                                <option value="<?php echo $i; ?>" <?php if($vaaal['Restaurant']['delivery_free_range']==$i){ echo "selected";} ?>><?php echo $i; ?> KM</option>
                                                            <?php
                                                        }
                                
                                                    ?>
                                
                                               </select>
                                                </p>
											</div> 
											<div class="col25 left">
												<p>
												    <select name="country" id="cntry">
                                                        <option value="">Select Country</option required>
                                                        <?php
                                                            foreach($country['currency'] as $cntry) 
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Currency']['country']; ?>" <?php if($vaaal['RestaurantLocation']['country']==$cntry['Currency']['country']){ echo "selected";} ?>><?php echo $cntry['Currency']['country']; ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </p>
											</div> 
											<div class="col25 left" style="display:none;">
												<?php $timezone = $vaaal['Restaurant']['timezone']; ?>
												<p><input type="text" name="timezone" id="timezone" value="<?php echo $timezone; ?>"  required=''><label alt='timezone' placeholder='Timezone'></label></p>
											</div> 
											
											<div class="col25 left">
											    <p> 
												    <select name="state" id="state">
                                                        <option value="">Select State</option required>
                                                        <?php 
                                                            foreach($country['taxes'] as $cntry) 
                                                            {
                                                                ?>
                                    	                            <option value="<?php echo $cntry['Tax']['state']; ?>" <?php if($vaaal['RestaurantLocation']['state']==$cntry['Tax']['state']){ echo "selected";} ?>><?php echo $cntry['Tax']['state']; ?></option>
                                    	                        <?php
                                    	                    }
                                    	                ?>
                                                    </select>
                                                </p>
											</div> 
											
											<div class="clear"></div>
											
											<div class="col25 left">
											    <p>
												    <select name="city" id="city">
                                                        <option value="">Select City</option required>
														<option <?php if($vaaal['RestaurantLocation']['city']==$cntry['Tax']['city']){ echo "selected";} ?> value="Dammam">Dammam</option>
														<option <?php if($vaaal['RestaurantLocation']['city']==$cntry['Tax']['city']){ echo "selected";} ?> value="Dhahran">Dhahran</option>
														<option <?php if($vaaal['RestaurantLocation']['city']==$cntry['Tax']['city']){ echo "selected";} ?> value="Khobar">Khobar</option>
                                                    </select>
                                                </p>
											</div> 
											
											<div class="col25 left">
											    <p>
												    <select name="min_order_price" id="min_order_price">
                                                        <option value="">Min Order Amount</option>

                                                        <?php
                                    
                                                            for($i = 1; $i<=999; $i++) {
                                                                ?>
                                                                    <option value="<?php echo $i; ?>" <?php if($vaaal['Restaurant']['min_order_price']==$i){ echo "selected";} ?>><?php echo $i; ?></option>
                                                                <?php
                                                            }
                                    
                                                        ?>
                                                    </select>
                                                </p>
											</div> 
											<div class="col25 left">
											    <p>
												    <select name="preparation_time" class="form-control" required="">
                                                        <option value="">AVG Food Prepation Time</option>
                                                        <option value="30" <?php if($vaaal['Restaurant']['preparation_time']=="30"){ echo "selected";} ?>>30 min</option>
                                                        <option value="40" <?php if($vaaal['Restaurant']['preparation_time']=="40"){ echo "selected";} ?>>40 min</option>
                                                        <option value="50" <?php if($vaaal['Restaurant']['preparation_time']=="50"){ echo "selected";} ?>>50 min</option>
                                                        <option value="60" <?php if($vaaal['Restaurant']['preparation_time']=="60"){ echo "selected";} ?>>60 min</option>
                                                   </select>
                                                </p>
											</div> 
											<div class="col25 left twocll">
    											<p><input type="text" name="speciality" id="speciality" value="<?php echo $vaaal['Restaurant']['speciality']; ?>" required=''><label alt='Speciality' placeholder='Speciality'></label></p>
    										</div>
											
											<div class="clear"></div>
											
											
										</div>
										
										<div class="cl25" style="display:none1;">
											<div class="col25 left">
												<p><input type="text" name="zip" id="zip" value="<?php echo $vaaal['RestaurantLocation']['zip']; ?>" required=''><label alt='ZIP' placeholder='ZIP'></label></p>
											</div> 
											
											<div class="col25 left">
												<p><input type="text" id="address" value="<?php echo $vaaal['RestaurantLocation']['address']; ?>" required=''><label alt='Address' placeholder='Address'></label></p>
											</div> 
											
											<div class="col25 left">
												<p><input type="text" name="lat" id="lat" value="<?php echo $vaaal['RestaurantLocation']['lat']; ?>" required=''><label alt='Lattitude' placeholder='Lattitude'></label></p>
											</div> 
											<div class="col25 left">
												<p><input type="text" name="long" id="long" value="<?php echo $vaaal['RestaurantLocation']['long']; ?>" required=''><label alt='Longitude' placeholder='Longitude'></label></p>
											</div> 
											<div class="clear"></div>
											
										</div>
			                            
			                            <div>
											<p>
											    <a style="cursor: pointer;" onClick="javascript:jQuery('#settime').show();">
												    <button type="button" class="button" style="width: 100%;">Set time schedule</button>
										    	</a>
											</p>
										</div> 
										
										<div class="mapdiv" style="margin:0px auto; position: relative;">
											<div id="geo_button" onClick="geoLocateClick()" style="position: absolute; right: 1%; bottom: 29%; cursor: pointer; z-index: 2147483647!important; background-color: white; width: 40px; height: 40px; border-radius: 2px;">
												<i class="fa fa-map-marker fa-2x" aria-hidden="true" 
													style="margin-top: margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); color: rgba(0,0,0,0.7);">
												</i>
											</div>
											<div id="us2" style="width: 100%; height: 400px;"></div>
										</div>
										<script>
											getLocation();
											
											$('#us2').locationpicker({
												location: { latitude: <?php echo $vaaal['RestaurantLocation']['lat']; ?>, longitude: <?php echo $vaaal['RestaurantLocation']['long']; ?> },
												radius: 5,
												scrollwheel: true,
												inputBinding: {
													latitudeInput: $('#lat'),
													longitudeInput: $('#long'),
													locationNameInput: $('#address')
												},
												enableAutocomplete: true,
												onchanged: function (currentLocation, radius, isMarkerDropped) {
													var addressComponents = $(this).locationpicker('map').location;
													var lat = addressComponents.latitude;
													var lng = addressComponents.longitude;
													var adr = addressComponents.formattedAddress;
													
													//console.log("Latitude: " +lat+ " longitude: " +lng+ " address: " +adr);
													//jQuery(".mapdiv").slideDown();
													$('#us2').locationpicker('autosize');
												}
											});
										</script>
										
										
										
										<style type="text/css">
											#settime .timediv {
												box-shadow: 0 0 4px 0 rgba(0,0,0,0.2);
											}
											
										</style>
										<div id="settime" class="popup">
											<div class="popup_container">
												<a href="javascript:;" id="close" onClick="javascript:jQuery('#settime').hide();">&times;</a>
												<div class="schedulee" style="padding: 40px;">
													<h2 style="margin: 0 0 20px;">Set Time Schedule</h2>
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Monday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Monday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][0]['opening_time'])) { echo $vaaal['RestaurantTiming'][0]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot1" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																	<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][0]['closing_time'])) { echo $vaaal['RestaurantTiming'][0]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct1" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Tuesday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Tuesday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][1]['opening_time'])) { echo $vaaal['RestaurantTiming'][1]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot2" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][1]['closing_time'])) { echo $vaaal['RestaurantTiming'][1]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct2" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Wednesday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Wednesday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][2]['opening_time'])) { echo $vaaal['RestaurantTiming'][2]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot3" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][2]['closing_time'])) { echo $vaaal['RestaurantTiming'][2]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct3" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
													
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Thursday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Thursday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" id="ot4" value="<?php if(!empty($vaaal['RestaurantTiming'][3]['opening_time'])) { echo $vaaal['RestaurantTiming'][3]['opening_time']; } ?>" class="timepicker" name="opening_time[]" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][3]['closing_time'])) { echo $vaaal['RestaurantTiming'][3]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct4" placeholder="Closing Time" /></p>
															</div><div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Friday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Friday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][4]['opening_time'])) { echo $vaaal['RestaurantTiming'][4]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot5" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][4]['closing_time'])) { echo $vaaal['RestaurantTiming'][4]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct5" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="col33 left"><div class="timediv">
															<p>Saturday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Saturday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][5]['opening_time'])) { echo $vaaal['RestaurantTiming'][5]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot6" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][5]['closing_time'])) { echo $vaaal['RestaurantTiming'][5]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct6" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
									
													<div class="cl33">
														<div class="col33 left"><div class="timediv">
															<p>Sunday</p>
															<input type="hidden" name="day[]" placeholder="Day" value="Sunday" readonly>
															<div class="col50 left twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][6]['opening_time'])) { echo $vaaal['RestaurantTiming'][6]['opening_time']; } ?>" class="timepicker" name="opening_time[]" id="ot7" placeholder="Opening Time" /></p>
															</div>
															<div class="col50 right twocll">
																<p><input type="text" value="<?php if(!empty($vaaal['RestaurantTiming'][6]['closing_time'])) { echo $vaaal['RestaurantTiming'][6]['closing_time']; } ?>" class="timepicker" name="closing_time[]" id="ct7" placeholder="Closing Time" /></p>
															</div> <div class="clear"></div>
														</div></div>
														<div class="clear"></div>
													</div>
			
													<br>
													<a href="javascript:;" onclick="javascript:jQuery('#settime').hide();" style=" display: inline-block; width: 100%; text-decoration: none; "><button type="button" class="button" style=" width: 100% !important; display: block !important; ">Save Changes</button></a>
												</div>
											</div>
										</div>
						                <br>
									</form>
									
									
								<?php
							}
							
						?>
						
					</div>
					<?php
				} //
			}

			curl_close($ch);
		?>
	</div>
	<div class="clear"></div>
</div>

<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>