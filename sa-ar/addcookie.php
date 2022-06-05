<?php
require_once("./config.php");


if(isset($_GET['getcart'])) {

	if($_SESSION['restaurantid']== true && $_SESSION['randID']==true) 
	{
		$restaurantid=$_SESSION['restaurantid'];	
		$randID=$_SESSION['randID'];


		$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
		);



		$ch = curl_init($firebaseBaseURL.'webCart/'.$randID.'/.json');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_POSTFIELDS,"hello");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
		

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$responseCount = count($json_data);
		
		if($responseCount!="0")
		{

			$subtotal="";
			foreach ($json_data as $key => $value) 
			{
				$subtotal += $value['totalPrice'];
			}
			$subtotal;

			//echo $return;

			$checkout_menu= '"menu_item":'.$return.'';

			
			?>
				<input type="hidden" id="menuItemData" value='<?php echo htmlspecialchars($checkout_menu) ?>'>
				<input type="hidden" id="time" value='<?php echo htmlspecialchars(date('Y-m-d H:i:s')); ?>'>

			<div style="">
			<?php
			
			$symbol=$json_data[0]['symbol'];

			$tax_free=$json_data[0]['tax_free'];
			$taxpersent=$json_data[0]['taxpersent'];

			if($tax_free=="1")
			{
				$tax_persent= 0;
				$tax_percentage = "0";
			}
			else
			{
				$tax_persent= $taxpersent."%";

				$tax_percentage = ($taxpersent / 100) * $subtotal;
			}

			$i="0";
			foreach ($json_data as $key => $value) 
			{
				$rowNumber = $i++;
				
				?>
						<ul class="exsection menupopup" style="margin:0px !important;">
							<li>
								<div class="topbotpadr padr" style="padding:10px 10px;">
									<h2 class="title" style="font-size:15px; ">
										<div class="right col70" style="font-weight: bold; direction: ltr;">
											<?php 
												echo "<span style=\"color: #00C269;\">".$value['menu_item_quantity']." x "."</span>";
												echo $value['menu_item_name'];
											?>	
										</div>
										<div class="left extraitemprice" style="font-weight: bold; color: #00C269; direction: ltr;">
											<?php 
											echo $value['menu_item_price'].' ';
												echo $value['symbol'];
												
											?>													
										</div>
										<div class="clear"></div>
									</h2>
								</div>

								<?php
									$extramenuCount=count($value['menu_extra_item']);
									if($extramenuCount!="0")
									{
										foreach ($value['menu_extra_item'] as $key1 => $value1)
										{
											?>
												<ul class="exsection_me">
													<li style="border-bottom: solid 1px #e6e6e6;">
														<div class="radio">
															<label class="lable-radio-container" style="padding:10px 10px 10px 10px;">
																<div class="right col70" style="font-weight: bold; direction: ltr;">
																	<?php 
																		echo $value1['menu_extra_item_quantity']." x ";
																		echo $value1['menu_extra_item_name'];
																	?>	
																</div>
																<div class="left extraitemprice" style="font-weight: bold; color: #00C269; direction: ltr;">
																	<?php 
																		echo $value1['menu_extra_item_quantity'] * $value1['menu_extra_item_price'];
																        echo ' '.$value1['symbol'];
																	?>													
																</div>
																<div class="clear"></div>
															</label>
														</div>
																										
													</li>
												</ul>
											<?php
										}
									}
									
								?>
								

							</li>
						</ul>				
								
									
				<?php
			}


			?>
				<div class="selectpayment">

					<?php
						if($_SESSION['id']!="")
						{
							?>
								<div class="selectpayment">
									<select name="paymentmethod" id="paymentmethod" onclick="popup('login')" >
										<option value="">اختار طريقة الدفع</option>
										<option value="cod">Cash on Delivery (COD)</option>
										<?php 
											$user_id = $_SESSION['id'];

											$headers2 = array(
												"Accept: application/json",
												"Content-Type: application/json"
											);

											$data = array(
												"user_id" => $user_id
											);

											 $endpoint = "/getPaymentDetails";

                                            $json_data = curl_request($data, $endpoint, $baseurl);
											if($json_data['code'] !== 200) {

											} else {
												foreach( $json_data['msg'] as $stttr => $vaaal ) {
													?>
													<option value="<?php echo $vaaal['PaymentMethod']['id']; ?>"><?php echo $vaaal['brand']; ?> Card (<?php echo "**** **** **** ".$vaaal['last4']; ?>)</option>
													<?php
												}
											}

										?>
									</select>
								</div>
							<?php
						}
						else
						{
							?>
								<div class="selectpayment" onclick="popup('login')">
									Select Payment Method
								</div>
							<?php
						}	
					?>
				<div class="couponcode delivery_row" style="background: white;">
					<div class="left col50" id="delivery" onClick="orderType('delivery');">توصيل</div>
					<div class="right col50" id="pickup" onClick="orderType('pickup');" >استلام</div>
					<div class="clear"></div>
					<input type="hidden" id="ordertype" value="1">
					<input type="hidden" id="restaurant_id" value="<?php echo htmlspecialchars($_SESSION["restaurantid"]); ?>">
					<input type="hidden" id="user_id" value="<?php echo htmlspecialchars($_SESSION["id"]); ?>">
				</div>
				<div class="couponcode">
					<input class="right" type="text" id="couponcode" name="couponcode" placeholder="رمز الكوبون" />
					<span  class="left col20" id="cupnbtn">
						<button type="button" onclick="verifyCoupan()">تحقق</button>
					</span>	
					<div class="clear"></div>
					<input type="hidden" id="coupancodeid" value="0">
				</div>
				<div class="couponcode" id="rider_tip" style="display: none;">
					<input type="text" id="riderTip" name="riderTip" onkeyup="rider_tip()" placeholder="Rider tip (Optional)" />
				</div>
				<div id="address_cart_container" style="display: none; width: 100%; text-align: center; margin-top: 5%; margin-bottom: 5%; font-family: Montserrat; font-size: 13px;">
					<span>تسليم الى:</span><br>
					<span id="address_cart" style="font-size: 12px; font-weight: 100;">
						&nbsp
					</span>
					<span onClick="popup('ordernow')" style="font-size: 10px; font-weight: 100; cursor: pointer; color: #be2c2c;">تعديل</span>
				</div>
			</div>
				<div class="couponcode totalBox">
					<div class="total_row" style="display: none">
						<div class="right col60" >Rider Tip</div>
						<div class="left col40 textright"><?php echo $symbol; ?><span id="checkoutridertip">0.00</span></div>
						<div class="clear"></div>
					</div>
					<div class="total_row">
						<div class="right col60" >المجموع الفرعي</div>
						<input type="hidden" id="subtotal" value="<?php echo $subtotal; ?>">
						<div class="left col40 textright" style="direction: ltr;"><span id="subtotalinvoice"><?php echo $subtotal; ?>.00</span><?php echo ' '.$symbol; ?></div>
						<div class="clear"></div>
					</div>
					<div class="total_row">
						<div class="right col60" >ضريبة(<?php echo $tax_persent; ?>)</div>
						<div class="left col40 textright" style="direction: ltr;"><?php echo $tax_percentage.' '; echo $symbol; ?></div>
						<input type="hidden" id="tax" value="<?php echo $tax_percentage; ?>">
						<div class="clear"></div>
					</div>
					<div class="total_row">
						<div class="right col60" >تخفيض</div>
						<div class="left col40 textright" style="direction: ltr;"><span id="discountinvoice">0.00</span><?php echo ' '.$symbol; ?></div>
						<div class="clear"></div>
						<input type="hidden" id="discountvalue" value="0">
					</div>
					<div class="total_row">
						<div class="right col60" >رسوم التوصيل</div>
						<div class="left col40 textright" style="direction: ltr;"><span id="deliveryfeeinvoice">0.00</span><?php echo ' '.$symbol; ?></div>
						<input type="hidden" id="delivery_fee" value="0">
						<div class="clear"></div>
					</div>
					<hr>
					<div class="total_row">
						<div class="right col60" >مجموع</div>
						<div class="right col40 textright" style="color: #00C269; direction: ltr;"><span id="total_price" style="color: #00C269"><?php echo $subtotal+$tax_percentage; ?></span><?php echo ' '.$symbol; ?></div>
						<input type="hidden" id="totalPrice" value="0">
						<div class="clear"></div>
					</div>
				</div>	
				<input type="text" id="orderformat" style="display: none;" required>
				<div class="cartbelow">
					<a <?php if($_SESSION['id']!=""){echo 'onClick="orderNow()"';} else {echo 'onClick="popup(\'login\')"';} ?> id="ordernowbutton_" name="ordernowbutton_"><button type="button" class="button">اطلب الان</button></a>
				</div>
				
			<?php


		}
		else
		{
			echo"<div class='empty_cart'><img src='assets/img/nocart.png' alt='empty cart'><br/><p>السلة فارغة!</p></div>";
		}
		

	}
	else
	{
			echo"<div class='empty_cart'><img src='assets/img/nocart.png' alt='empty cart'><br/><p>السلة فارغة!</p></div>";
	}

}
else
if(isset($_GET['addcart'])) {

	

	$array = $_POST;
	
	$removeKeys = array_keys($array, "on");
	
	foreach($removeKeys as $key) 
	{
	   unset($array[$key]);
	}
	
 	
 	//update quntiy start

	foreach ($array['menu_extra_item'] as $key => $val) 
	{

		$result_decoded = json_decode($val,true);
		$result_decoded['menu_extra_item_quantity'] = $array['menu_item_quantity'];
		$array['menu_extra_item'][$key] = json_encode($result_decoded);
	}

	//print_r($array);

	//update quntiy end



   	$dataCount =count($array['menu_extra_item']);


 	if($dataCount!=0)
	{
		$data="";
	 	foreach ($array['menu_extra_item'] as $key => $value) {
	 		$data .= $value.',';
	 	}
	}

	//die();

	// check if any count exist on firebase of that $randID  
	$hotel_id=$array['resturentid'];
	


	if($hotel_id!=$_SESSION["restaurantid"])
	{

		$randID=$_SESSION['randID'];
		 
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $firebaseBaseURL."webCart/".$randID.".json",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "DELETE",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Content-Type: application/json",
		    "Postman-Token: f0b47efd-fc83-4fac-89e0-2d9cc8aac349"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

	
		//echo"<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'>Cart is empty!</div>";
		echo"<div class='empty_cart'><img src='assets/img/nocart.png' alt='empty cart'><br/><p>Cart is empty!</p></div>";
	}

	$_SESSION["restaurantid"] = $hotel_id;

	$randID=$_SESSION["randID"];
	$headers = array(
	"Accept: application/json",
	"Content-Type: application/json"
	);

	

	$ch = curl_init($firebaseBaseURL.'webCart/'.$randID.'/.json');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_POSTFIELDS,"hello");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$return = curl_exec($ch);

	$json_data = json_decode($return, true);
	$existCount = count($json_data);
	
	$plusCount = $existCount;
	//var_dump($json_data);

	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	//End
	//die();
	

	if($existCount==0)
	{

				
		 		$today = date("F j, Y, g:i a"); 
		 		$curl = curl_init();
				if($dataCount == 0)
				{
					$data1 = '{
		 		 				"'.$array['randnumber'].'": 
		 		 					[
			 		 					
			 		 					{
										  "menu_item_name": "'.$array['menu_item_name'].'",
										  "menu_item_price": "'.$array['menu_item_price'].'",
										  "tax_free": "'.$array['tax_free'].'",
										  "taxpersent": "'.$array['taxpersent'].'",
										  "hotel_id": "'.$array['resturentid'].'",
										  "created": "'.$today.'",
										  "menu_item_quantity": "'.$array['menu_item_quantity'].'",
										  "totalPrice": "'.$array['menu_totalPrice'].'",
										  "symbol": "'.$array['symbol'].'"
										}  
									] 
							}';
				}
				else
				{
					$data1 = '{
		 		 				"'.$array['randnumber'].'": 
		 		 					[
			 		 					
			 		 					{
										  "menu_item_name": "'.$array['menu_item_name'].'",
										  "menu_item_price": "'.$array['menu_item_price'].'",
										  "tax_free": "'.$array['tax_free'].'",
										  "taxpersent": "'.$array['taxpersent'].'",
										  "hotel_id": "'.$array['resturentid'].'",
										  "created": "'.$today.'",
										  "menu_item_quantity": "'.$array['menu_item_quantity'].'",
										  "totalPrice": "'.$array['menu_totalPrice'].'",
										  "symbol": "'.$array['symbol'].'",
										   "menu_extra_item": 
										  [
										    '.rtrim($data,',').'
										  ]
										}  
									] 
							}';
				}	
				
				
			     curl_setopt_array($curl, array(
			     CURLOPT_URL => $firebaseBaseURL."webCart.json",
			     CURLOPT_RETURNTRANSFER => true,
			     CURLOPT_ENCODING => "",
			     CURLOPT_MAXREDIRS => 10,
			     CURLOPT_TIMEOUT => 30,
			     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			     CURLOPT_CUSTOMREQUEST => "PATCH",
			     CURLOPT_POSTFIELDS => $data1,
			     CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/json",
			    "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
			     ),
			   ));
			  

	}
	else
	{
				$today = date("F j, Y, g:i a"); 
		 		$curl = curl_init();
				if($dataCount == 0)
				{
					echo $data1 = '{
		 		 				"'.$plusCount.'": 
	 		 						{
									  "menu_item_name": "'.$array['menu_item_name'].'",
									  "menu_item_price": "'.$array['menu_item_price'].'",
									  "tax_free": "'.$array['tax_free'].'",
									  "taxpersent": "'.$array['taxpersent'].'",
									  "hotel_id": "'.$array['resturentid'].'",
									  "created": "'.$today.'",
									  "menu_item_quantity": "'.$array['menu_item_quantity'].'",
									  "totalPrice": "'.$array['menu_totalPrice'].'",
									  "symbol": "'.$array['symbol'].'"
									}  
							}';
				}
				else
				{
					$data1 = '{
		 		 				"'.$plusCount.'":  
		 		 					{
									  "menu_item_name": "'.$array['menu_item_name'].'",
									  "menu_item_price": "'.$array['menu_item_price'].'",
									  "tax_free": "'.$array['tax_free'].'",
									  "taxpersent": "'.$array['taxpersent'].'",
									  "hotel_id": "'.$array['resturentid'].'",
									  "created": "'.$today.'",
									  "menu_item_quantity": "'.$array['menu_item_quantity'].'",
									  "totalPrice": "'.$array['menu_totalPrice'].'",
									  "symbol": "'.$array['symbol'].'",
									   "menu_extra_item": 
									  [
									    '.rtrim($data,',').'
									  ]
									}  
							}';
				}	
				
				

			     curl_setopt_array($curl, array(
			     CURLOPT_URL => $firebaseBaseURL."webCart/".$randID."/.json",
			     CURLOPT_RETURNTRANSFER => true,
			     CURLOPT_ENCODING => "",
			     CURLOPT_MAXREDIRS => 10,
			     CURLOPT_TIMEOUT => 30,
			     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			     CURLOPT_CUSTOMREQUEST => "PATCH",
			     CURLOPT_POSTFIELDS => $data1,
			     CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/json",
			    "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
			     ),
			   ));
			   
			   
	}

	//print_r($data1);

	$response = curl_exec($curl);
    $err = curl_error($curl);
   

   
   if ($err) 
   {

    //

   } 
   else 
   {
   		echo "done";

   }
	

	
 	

}
else
if(isset($_GET['fbase'])) {
	//var_dump($_POST);
	$fbasedata=json_encode($_POST, true);
	//print_r($fbasedata);
	$symbol= $_GET['data2'];
	$value3=json_decode($fbasedata, true);
	//echo $fbasedata['name'];
	$randnumber=$_SESSION["randID"];
	
	

	?>
		<form id="addtocart_popup" name="addtocart_popup" action="#" method="post">
			<div class="padr">
				<h1 style="font-family: Montserrat;"><?php echo $value3['name']; ?></h1>
				<p style="font-family: Montserrat; font-weight: 100; font-size: 12px; color: grey;"><?php echo $value3['description']; ?></p>
			</div>
			<input type="hidden" id="menu_item_name" name="menu_item_name" value="<?php echo $value3['name']; ?>">
			<input type="hidden" id="menu_item_price" name="menu_item_price" class="qty1" value="<?php echo $value3['price']; ?>">

			<input type="hidden" id="tax_free" name="tax_free"  value="<?php echo htmlspecialchars($_GET['tax_free']); ?>">
			<input type="hidden" id="taxpersent" name="taxpersent" value="<?php echo htmlspecialchars($_GET['taxpersent']); ?>">
			<input type="hidden" id="resturentid" name="resturentid" value="<?php echo htmlspecialchars($_GET['resturentid']); ?>">
			<input type='hidden' id="symbol" name="symbol" value="<?php echo htmlspecialchars($symbol); ?>" placeholder="symbol">
			<input type='hidden' id="session_restaurantid" name="session_restaurantid" value="<?php echo htmlspecialchars($_SESSION['restaurantid']); ?>" >
			

			<ul class="exsection">
				<?php 
					$sectionCount= count($value3['RestaurantMenuExtraSection']);
					if($sectionCount!=0)
					{
						foreach ($value3['RestaurantMenuExtraSection'] as $key4 => $value4) {
							$req = $value4['required'];
							?>
							<li>
								<div class="topbotpadr padr" style="padding-top: 0; padding-bottom: 0;">
									<h2 class="title"><?php echo ucwords($value4['name']); ?></h2>
									<?php if( $req == 1 ) { ?>
									<div style="margin-top: 5px;font-size: 16px;">
										<div class="right col80">
											<p><span style='color:#aaa;display:inline-block;'>اختر واحد</span></p>
										</div>
										<div class="left">
											<p><span style='color:red;display:inline-block;'>مطلوب</span></p>
										</div>
										<div class="clear"></div>
									</div>
									<?php } ?>
								</div>
								<ul class="exsection_me">

									<div id="section_<?php echo $value4['id']; ?>">
										<!-- <input type='text' name='section_name_<?php echo $value4['id']; ?>' id='section_name_<?php echo $value4['id']; ?>' placeholder="section_name" value="<?php echo $value4['name']; ?>"> -->
										<input type='hidden' id='section_id_<?php echo htmlspecialchars ($value4['id']); ?>' placeholder="section_name" value="<?php echo htmlspecialchars( $value4['id']);  ?>">
									</div>


									<?php if( $req == 1 ) { ?>
										<div id="section_<?php echo $value4['id']; ?>">
											<input type='text' name='menu_extra_item[]' class='extraMenu_item' id='section_json<?php echo $value4['id']; ?>' style='display: none;' required="" aria-required="true" >
											<input type='hidden' id='Rdioprice' class="qty1" >
								            <input type='hidden' id="symbol" name="symbol" value="<?php echo htmlspecialchars($symbol); ?>" placeholder="symbol">
										</div>
									<?php } ?>

									


									<?php 
									    if(count($value4['RestaurantMenuExtraItem'])!="0")
									    {
									        foreach ($value4['RestaurantMenuExtraItem'] as $key5 => $value5) {
    										?>
    										<li>
    											<?php if( $req == 1 ) { ?>
    												<div class="radio">
    													
    													<label class="lable-radio-container" style="padding-top: 2.5%; padding-bottom: 2.5%;">
    														<input type="radio" name="lable-radio-container extraitem_req<?php echo $value4['id']; ?>" id="extraitem_req<?php echo $value4['id']; ?>" onclick='filRadioVal("<?php echo $value4['id']; ?>","<?php echo $value5['id']; ?>","<?php echo $value5['name']; ?>","<?php echo $value5['price']; ?>","<?php echo $symbol; ?>");' style="display: block !important; " > 
    														<span class="checkmark"></span>
    														<div class="left col70"><?php echo $value5['name']; ?></div>
    														<div class="right extraitemprice" style="direction: ltr;">
    															<?php 
    																if($value5['price']!="0")
    																{
    																    echo $value5['price'].' ';
    																	echo $symbol; 
    																	
    																} 
    														
    															?>
    														</div>
    														<div class="clear"></div>
    													</label>
    												</div>
    											<?php } else { ?>
    												<div class="checkbox">
    													
    													<label class="lable-container extraitem_req_<?php echo $value5['id']; ?>" >
    														
    														<input type="checkbox" id="extraitem_req_<?php echo $value5['id']; ?>" onChange='filCheckVal("<?php echo $value4['id']; ?>","<?php echo $value5['id']; ?>","<?php echo $value5['name']; ?>","<?php echo $value5['price']; ?>","<?php echo $symbol; ?>");' style="display: block !important; "> 
    														<span class="checkmark"></span>
    														<div class="left col70"><?php echo $value5['name']; ?></div>
    														<div class="right extraitemprice" style="color: #00C269; direction: ltr;">
    																<?php
    																echo $value5['price'].' '; 
    																	echo $symbol;
    																	
    																?>
    														</div>
    														<div class="clear"></div>
    													</label>
    													
    													<div class="filds" id="checkedFilds_<?php echo $value5['id']; ?>">
    														<!-- <div id="menu_item">
    															<input type="text" id="menu_Name_<?php echo $value5['id']; ?>"  >
    															<input type="text" id="menuitem_id_<?php echo $value5['id']; ?>" >
    															<input type="text" id="menu_price_<?php echo $value5['id']; ?>" >
    															<input type="text" id="menu_symbol_<?php echo $value5['id']; ?>" >
    														</div> -->
    													</div>
    												</div>
    											<?php } ?>
    											
    										</li>
    										<?php
    									}        
									    }
									?>
								</ul>
							</li>
							<?php
						} 	
					}
					
				?>
			</ul>

			<!-- <div class="addinstrctions topbotpadr padr">
				<div class="instructionbox">
					<h3 class="instructn_heading"><i class="fa fa-plus-circle"></i> Add Instructions here</h3>
				</div>
			</div> -->

			<div class="reviews_addcart" style="background:#f2f2f2;">
				<div class="q">
					<button class="dec button" id="btn" onClick="countQty('-1');" type="button">-</button>
					<input type="text" id="menu_item_quantity" name="menu_item_quantity" value="1" style="text-align: center;width:60px; border: 0px; box-shadow:none; background:#f2f2f2;">
					<button class="inc button" id="btn" onClick="countQty('1');" type="button">+</button>
					
				</div>
				
			</div>

			<div class="submitbtnn" style="background: white;">
				
				
				<input type="hidden" id="randnumber" name="randnumber" value="<?php echo $randnumber; ?>">
				<input type="hidden" id="menu_totalPrice" name="menu_totalPrice" value="<?php echo $value3['price']; ?>">
				<script>
					jQuery(document).ready(function($) {
					    var inputs = jQuery('.p_<?php echo $value3['id']; ?> .menupopup input[type="checkbox"], .p_<?php echo $value3['id']; ?> .menupopup input[type="radio"]')
					    inputs.on('change', function () {
					        var sum = 0
					        inputs.each(function() {
					           if(this.checked)
					           		if( $.trim( jQuery(this).parent().find('.extraitemprice').html() ).length ) {
					               		sum += parseInt(jQuery(this).parent().find('.extraitemprice').html())
					               }
					        })
					        jQuery(".totalcharge_<?php echo $value3['id']; ?>").html(sum)
					        jQuery(".totalcharge_val_<?php echo $value3['id']; ?>").val(sum)
					    })
					})
				</script>
				
				<button type="button" class="submitbtnn" id="popupAddtocartbtn" onClick="return addtocart(addtocart_popup)" style="background: white; ">
					<span class="center" style="background: #E86942; padding: 13px; border-radius: 25px; width: 20%; margin: 0 0 0 10%;">أضف إلى السلة</span>
					<div class="right extraitemprice" style="color: #00C269; font-weight: bold; direction: ltr;">
						<span id="total_popup" style="color: #00C269; font-weight: bold;"><?php echo $value3['price'].' '; ?></span>
						<?php echo $symbol; ?>
					</div>
					<span class="clear"></span>
				</button>
			</div>
			</form>
	<?php

} 


if(isset($_GET['setcook'])) 
{
	if($_GET['setcook']=="ok") {
		
		//echo $_GET['setcook'];
		//$restaurantid=$_COOKIE["restaurantid"];
		//$uniqueID=$_COOKIE["uniqueID"];

		$sectionID= $_GET['sectionID'];
		$teamName = $_GET['teamName'];
		$itemID = $_GET['itemID'];
		$price = $_GET['price'];
		$symbol = $_GET['symbol'];

		

		echo $value = '{"menu_extra_item_name": "'.$teamName.'", "menu_extra_item_quantity": "1", "menu_extra_item_price": "'.$price.'","symbol": "'.$symbol.'"}';
		

		//setcookie($uniqueID.'-'.$sectionID, $value);
		
		//print_r($_COOKIE);


	}
	else
	if($_GET['setcook']=="check") {
	
		//echo $_GET['setcook'];
		//$restaurantid=$_COOKIE["restaurantid"];
		//$uniqueID=$_COOKIE["uniqueID"];

		$sectionID= $_GET['sectionID'];
		$itemName = $_GET['itemName'];
		$itemID = $_GET['itemID'];
		$price = $_GET['price'];
		$symbol = $_GET['symbol'];



		echo $value = '{"menu_extra_item_name": "'.$itemName.'", "menu_extra_item_quantity": "1", "menu_extra_item_price": "'.$price.'","symbol": "'.$symbol.'"}';
		

		//setcookie($uniqueID.'-'.$sectionID, $value);
		
		//print_r($_COOKIE);


	}
}


if(isset($_GET['placeorder'])) 
{

		



		$data = $_GET['data'];
		



		$endpoint = "/Placeorder";


        $json_data = curl_request($data, $endpoint, $baseurl);

		if($json_data['code']=="202")
		{
            
			echo"<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'><img src='assets/img/nocart.png' alt='place order' style='width: 180px; margin-top:60px;''> <br/><div style='margin: 20px 0 0 0; padding: 0; font-size: 20px; font-weight: 300;'>Address is different from the restaurant location, Plase check your Delivery Address</div><br> <a href='#' onClick='window.location.reload(true)'>Reload</a>";
			die();
		}		
		
		if($json_data['code']=="888")
		{
            echo '888, test: '.$json_data['msg'];
			die();
		}
		



		
		$randID=$_SESSION['randID'];
		 
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $firebaseBaseURL."webCart/".$randID.".json",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "DELETE",
		  CURLOPT_HTTPHEADER => array(
		    "Cache-Control: no-cache",
		    "Content-Type: application/json",
		    "Postman-Token: f0b47efd-fc83-4fac-89e0-2d9cc8aac349"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);


			
		echo "<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'><img src='assets/img/nocart.png' alt='place order' style='width: 180px; margin-top:60px;''> <br/><div style='margin: 20px 0 0 0; padding: 0; font-size: 20px; font-weight: 300;'>Order has been placed.</div>";
		
}
if(isset($_GET['verifycoupan'])) 
{


		$data = array(
			"user_id" => $_SESSION['id'],
			"restaurant_id" => $_SESSION['restaurantid'],
			"coupon_code"=> $_GET['data']
		);
		
		

		    $endpoint = "/verifyCoupon";


        $json_data = curl_request($data, $endpoint, $baseurl);



		if($json_data['code']=="200")
		{
			echo $json_data['msg'][0]['RestaurantCoupon']['id'].",";
			echo $json_data['msg'][0]['RestaurantCoupon']['discount'];
		}
		else
		{
			echo "رقم قسيمه غير صالح";
		}
		
		
		//die;

		
		


		
}


if(isset($_GET['clearCart']))
{
	$randID=$_SESSION['randID'];
		 
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $firebaseBaseURL."webCart/".$randID.".json",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "DELETE",
	  CURLOPT_HTTPHEADER => array(
	    "Cache-Control: no-cache",
	    "Content-Type: application/json",
	    "Postman-Token: f0b47efd-fc83-4fac-89e0-2d9cc8aac349"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);


	echo"<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'>السلة فارغة!</div>";
}








?>