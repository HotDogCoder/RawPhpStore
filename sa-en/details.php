<?php require_once("header.php"); ?>
<style>

.time_slot li {
    display: inline-block;
    background-color: #f2f2f2;
    padding: 5px 10px;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
}

.time_slot_day li {
    display: inline-block;
    background-color: #f2f2f2;
    padding: 5px 4px;
    border-radius: 5px;
    margin: 3px;
    cursor: pointer;
}

.time_slot li.selected, .time_slot_day li.selected {
    background-color: #E77830;
    color: #fff;
}
</style>
<?php if( isset($_GET['id']) && !empty($_GET['id']) ) { //isset id
$id = $_GET['id'];

$randnumber=rand();
$randID=@$_SESSION["randID"];
if($randID == true)
{
	
}
else
{
	@$_SESSION['randID'] = $randnumber; 
}	

$headers = array(
	"Accept: application/json",
	"Content-Type: application/json"
);

$data = array(
	"id" => $id
);

$ch = curl_init( $baseurl.'/showRestaurantsMenu' );

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$return = curl_exec($ch);

$json_data = json_decode($return, true);
// echo '<pre>';

// print_r($json_data);
// echo '</pre>';
// exit;
$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


$tax_free=$json_data['msg'][0]['Restaurant']['tax_free'];
$tax_persent=$json_data['msg'][0]['Tax']['tax'];

 
if($json_data['code'] !== 200){
	echo "Error in fetching menu, try again later..";

} else {
	foreach ($json_data['msg'] as $key => $value) {
		//var_dump($value);
		$_SESSION['booking_available'] = $value['Restaurant']['booking_available'];
		if(checkImageExist($image_baseurl.$value['Restaurant']['image'])=="200")
		{
			$restaurantImage=$image_baseurl.$value['Restaurant']['image'];
		}
		else
		{
			$restaurantImage="assets/img/noImage.png";
		}		
		?>
	<div class="left" style="width: 68%; margin-left: 3.5%; margin-top: 1.5%;">
		<div class="banner bgimage col100 left" style="height: 40vh; border-radius: 10px; background-image: url(<?php echo $image_baseurl.$value['Restaurant']['cover_image']; ?>); margin:0px; -webkit-box-shadow: 10px 10px 28px -17px rgba(0,0,0,0.5);
																																																	-moz-box-shadow: 10px 10px 28px -17px rgba(0,0,0,0.5);
																																																	box-shadow: 10px 10px 28px -17px rgba(0,0,0,0.5);">
			<div style="background-color: white; width: 97%; position: absolute; bottom: 0; right: 0; border-radius: 0 0 10px 0;">
				<img style="display: inline-block; vertical-align: text-top; width: 90px; border-radius: 10px; margin: 1%;" src="<?php echo $restaurantImage; ?>" alt="<?php echo substr($value['Restaurant']['name'], 0, "20"); ?>">				
				<div style="display: inline-block; vertical-align: text-top; width: 83%; margin-top: 1.5%;">
					<div>
						<?php
						$openStatus = $value['Restaurant']['is_online'];
                                                        $status_text = "Close";
                                                        $status_color = "red"; 
                                                        if($value['Restaurant']['is_online'] == 1)
                                                        {
                                                            $status_text = "Open";
                                                            $status_color = "green";
                                                        }
                                                        ?>
						<h2 class="title" style="font-weight: bold; font-size: 20px; width: 80%; display: inline-block; color: rgba(0,0,0,0.85); margin-bottom: 0px;"><?php echo $value['Restaurant']['name']; ?>&nbsp;<span style="color: <?php echo $status_color;?>;font-size: 15px; font-weight: bold;">(<?php echo $status_text;?>)</span></h2>
						<div style="display: inline-block;">
					<?php
							if($value['TotalRatings']['avg']!="")
							{
								$star=5-$value['TotalRatings']['avg'];
								for($i=0; $i<$value['TotalRatings']['avg'];$i++)
								{
									?>
										<i class="fa fa-star"></i>
									<?php
								}
								if($star>0)
								{
									for($i=0; $i<$star; $i++)
									{
										?>
											<i class="fa fa-star-o"></i>
										<?php
									}
								}
							}
							else
							{
								?>
									<span class="rest_rat"> 
										<i class="fa fa-star-o"></i>
										<i class="fa fa-star-o"></i>
										<i class="fa fa-star-o"></i>
										<i class="fa fa-star-o"></i>
										<i class="fa fa-star-o"></i>  
									</span> 
								<?php
							}	
							
					?>
					</div>					
					</div>
					<div style="font-weight: 100; color: grey; font-size: 14px;"><?php echo $value['RestaurantTiming'][0]['opening_time']." - ".$value['RestaurantTiming'][0]['closing_time']; ?></div>	
					<p style="font-weight: 100; color: grey;"><?php echo $value['Restaurant']['slogan']; ?></p>	
					

					<div style="width: 100%; text-align: left;">
						<div style="display: inline-block; width: 12%;">
							<span style="display: block; font-size: 12px; color: grey;">Preparation:</span>
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php echo $value['Restaurant']['preparation_time']." min"."&nbsp;";?></span>
						</div>
						<div style="display: inline-block; width: 10%;">
							<span style="display: block; font-size: 12px; color: grey;">Delivery:</span>
							<div style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php echo $value['Tax']['delivery_time']." min"."&nbsp;"; ?></div>
						</div>
						<div style="display: inline-block; width: 18%; display: none;">
							<span style="display: block; font-size: 12px; color: grey;">Delivery Fee per km:</span>
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php echo $value['Tax']['delivery_fee_per_km']."&nbsp;".' '; echo $value['Currency']['symbol']; ?></span>
							<input style="display: none;" id="delivery_fee_per_km" value="<?php echo $value['Tax']['delivery_fee_per_km']; ?>" />
						</div>
						<div style="display: inline-block; width: 20%;">
							<span style="display: block; font-size: 12px; color: grey;">Minimum Order:</span>
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php  echo $value['Restaurant']['min_order_price']."&nbsp;".' '; echo $value['Currency']['symbol']; ?></span>
						</div>
					</div>

					
				</div>				
			</div>
		</div>
		<div class="clear"></div>
		<!--<div class="rowline2 rowline2_item menuItem" style="margin-top: 20px;">
			<h2>Timings</h2>
			<div style="width: 100%; text-align: left;">
						<div style="display: inline-block; width: 12%;">
							<span style="display: block; font-size: 12px; color: grey;">Day:</span>
							
						</div>
						<div style="display: inline-block; width: 10%;">
							<span style="display: block; font-size: 12px; color: grey;">Open:</span>
							
						</div>
						<div style="display: inline-block; width: 18%;">
							<span style="display: block; font-size: 12px; color: grey;">Close:</span>
						</div>
					</div>
					<div style="width: 100%; text-align: left;">
						<?php //foreach($value['RestaurantTiming'] as $key => $val){?>
						<div style="display: inline-block; width: 12%;">
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php //echo $val['day'];?></span>
							
						</div>

						<div style="display: inline-block; width: 10%;">
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php //echo $val['opening_time'];?></span>
							
						</div>
						<div style="display: inline-block; width: 18%;">
							<span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php //echo $val['closing_time'];?></span>
						</div>
						<div style="clear:both;"></div>
						<?php //}?>
					</div>
		</div>
		<div class="clear"></div>-->
		<div class="section itemsmenu mini">
			<div class="wdth">

				<div class="hotel_detailspage">
					<div class="col25 left" id="menulist_">
						<ul class="menuitems" id="menuListLeft" style="border: 1px solid rgba(0,0,0,0.1);">
						<!--<li style="padding: 5px; padding-left: 15px; padding-top: 15px; font-size: 16px;">Categories</li>-->
                            <?php $index = 0;?>
							<?php foreach ($value['RestaurantMenu'] as $key1 => $value1) {
							    if($index == $key1)
							    {
							        $class = "highlightMenu";
							    }else{
							        $class = "";
							    }
								?>
								<li id="<?php echo $value1['name']."Item"; ?>" style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 15px; color: grey;">
								    <a onclick="showMenuSection(<?php echo $value1['id']; ?>); highlightSection('<?php echo $value1['name']."Item"; ?>');">
								        <?php echo $value1['name']; ?>
								    </a>
								</li>
								<?php
							} ?>
							<li style="  padding: 5px; padding-left: 15px; padding-bottom: 0; font-size: 20px;"> </li>
						</ul>
					</div>
				<?php } ?>
					
					
				<?php foreach ($json_data['msg'] as $key => $value) {  ?>
					<div class="col75 right" >
						<div class="rows" id="menuListRight">

							<?php foreach ($value['RestaurantMenu'] as $key2 => $value2) {
								
								if(checkImageExist($image_baseurl.$value2['image'])=="200")
                        		{
                        		    $menuImage=$image_baseurl.$value2['image'];
                        		}
                        		else
                        		{
                        		    $menuImage="assets/img/noImage.png";
                        		}
								?>
								<div class="menuextraitems" id="<?php echo $value2['id']; ?>" style="display: none;">
									<div class="rowline2 rowline2_mn">
										<div class="left col100">
											<div class="col100 left">
												<div class="right menu_image" style="background:url('<?php echo $menuImage; ?>'); background-size: cover;"></div>
												<h3 style="  color: rgba(0,0,0,0.75); font-size: 28px; margin-top: 2.5%;"><?php echo $value2['name']; ?></h3>
												<!--<p style="  font-weight: 100;"><?php //echo $value2['description']; ?></p>-->
											</div>
											<div class="clear"></div>
										</div>
										<div class="right col20 textcenter">&nbsp;</div>
										<div class="clear"></div>
									</div>

									<?php foreach ($value2['RestaurantMenuItem'] as $key3 => $value3) {
										//var_dump($value3);
										
										if(checkImageExist($image_baseurl.$value3['image'])=="200")
                                		{
                                		    $menuImage=$image_baseurl.$value3['image'];
                                		}
                                		else
                                		{
                                		    $menuImage="assets/img/noImage.png";
                                		}
                                		
										?>
										<div class="rowline2 rowline2_item menuItem" style="margin-top: 10px;">
										    <?php 
										        if($openStatus == 1)
										        {
										    ?>
										    <a onclick='showMenuItem(<?php $fbase =json_encode($value3, true); echo $fbase; ?>,"<?php echo $value['Currency']['symbol']; ?>","<?php echo $tax_free; ?>","<?php echo $tax_persent; ?>","<?php echo $_GET['id']; ?>");'>
										    <?php
										        }else{
										  ?>
										  <a href="javascript:" style="cursor:default;">
										  <?php
										        }
										    ?>
											
												<div class="left col15">
													<div class="menu_image" style="background:url('<?php echo $menuImage; ?>'); background-size: cover;"></div>
												</div>
												<div class="left col85" style="margin-left: 10px;">
													<div class="left">
														<p style="  font-weight: bold;" class="bold"><?php echo $value3['name']; ?></p>
														<p style="  font-size: 12px; color: grey;"><?php echo $value3['description']; ?></p>
														<p style="  font-size: 15px; color: #00C269; font-weight: bold;" class="bold"><?php echo $value3['price'].' '; echo $value['Currency']['symbol']." ";  ?></p>
													</div>
													<div class="clear"></div>
												</div>
												<div class="clear"></div>
											</a>
											
										</div>

										<?php
									} ?>

								</div>
								<?php
							} ?>

						</div>
					</div>
					<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	</div>

	<div class="col20 right carttcol" id="fixcart">
		<div class="cartbox wdth" style="width: 25%">
			<div>
				<div class="left col60 textleft">
					<h2 style=" " class="title" style="color: #38215C">Cart Items</h2>
				</div>
				<div class="right col20 textright">
					<div style=" " class="clearCart" onclick="clearCart();">Clear</div>
				</div>
				<div class="clear"></div>
				<div id="showcart"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>

		<?php
	}
	
	?>

		

		<div class="popup menu_item" style="overflow: auto;">
			<div class="popup_container menupopup">
				<a onclick="javscript:$('.menu_item').hide();" id="close">×</a>
				<div id="menuItem"></div>
			</div>
		</div>	
		
		<div class="popup" id="ordernowpopup">
			<div class="ordernow_container" style="width: 90% height: 100%;"><a style="display: absolute; top: 2%; right: 2%;" href="javascript:;" onClick="javascript:jQuery('#ordernowpopup').hide();" id="close">&times;</a>
				<div style="width: 100%; text-align: center; font-size: 25px;"><span style="display:block; padding: 2%;  ">Delivery Address</span></div>
				<div style="display: absolute; bottom: 0; width: 100%; height: 65vh;">
				    <input type="text" id="address_autocomplete" name="address_autocomplete" style="display: none; z-index: 2147483647!important; position:absolute; top: 18%; left: 3%; width: 20%;"/>
					<div id="geo_button" onClick="geoLocateClick()" style="display: none; cursor: pointer; z-index: 2147483647!important; position: absolute; left: 55.83%; bottom: 22%; background-color: white; width: 40px; height: 40px; border-radius: 2px;">
						<i class="fa fa-map-marker fa-2x" aria-hidden="true" 
							style="margin-top: margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); color: rgba(0,0,0,0.7);">
						</i>
					</div>
					<div id="us2" class="mapPreview" style="display: inline-block; width: 60%; height: 100%; border-radius: 0;"></div>
					<div style="display: inline-block; width: 39%; height: 100%; vertical-align: top; border-top: 1px solid #d1d1d1;">
						<div style="width: 90%; margin: auto; margin-top: 5%; font-weight: bold; font-size: 15px;">
							Deliver to:
						</div>
						<div class="instrctns" style="width: 90%; margin: 3%;" >
							<input id="address_house" name="address_house" placeholder="House/apartment" required />
						</div>
						<div class="instrctns" style="width: 90%; margin: 3%;" >
							<input id="address_street" name="address_street" placeholder="Street" required />
						</div>
						<div class="instrctns" style="width: 90%; margin: 3%;" >
							<input id="address_city" name="address_city" placeholder="City" required />
						</div>
						<input type="text" id="addrsid" name="addrsid" style="display: none"/>						
						<input type="text" id="address_lat" name="address_lat" style="display: none"/>						
						<input type="text" id="address_long" name="address_long" style="display: none" />										
						<div class="instrctns" style="width: 90%; margin: 3%;">
							<textarea type="text" name="instructions" id="instructions" placeholder="Instructions (Optional)"></textarea>
						</div>
						<div style="position: absolute; bottom: 3%; right: 8%; width: 25%;"><a onClick="deliverHere()"><button type="button" class="button" style="width: 100%; height: 100%;">Deliver Here</button></a></div>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			window.onload = function () { 
		        showcartdata();
		    }
		</script>
	<?php

}

curl_close($ch);
?>



<?php 
} 

require_once("footer.php"); ?>