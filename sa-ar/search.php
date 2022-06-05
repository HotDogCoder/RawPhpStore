<?php require_once("header.php"); ?>

<div class="section mini" <?php if(isset($_GET['city'])){echo "style=\"height: 82%;\"";} ?>>
	<div class="wdth" style="">
	    <div style="padding-bottom: 20px; padding-right: 1.5%;">
	        <div style="display: inline-block; padding: 15px; position: relative; width: 390px;">
	            <img src="assets/img/new/banner_1.png" width="390" height="250"> 
                <img src="assets/img/new/play_button.png" height="25" style="position: absolute; bottom: 10%; right: 10%;"> 
                <img src="assets/img/new/ios_button.png" height="25" style="position: absolute; bottom: 10%; right: 33%;"> 
                <img src="assets/img/new/download_button.png" height="20" style="position: absolute; bottom: 45%; right: 10%;"> 
	        </div>
	        <div style="display: inline-block; padding: 10px;">
                <img src="assets/img/new/banner_3.png" height="250"> 
	        </div>
	        <div style="display: inline-block; padding: 10px;">
                <img src="assets/img/new/banner_2.png" height="250"> 
	        </div>
	    </div>
        <div class="col20 right" id="menulist_" style="padding: 5px;">
            <div id="search-field" style="width: 100%;">
              <input type="search" id="header-search" placeholder="بحث..." />
              <svg id="search-icon" class="search-icon" viewBox="0 0 24 24">
                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                <path d="M0 0h24v24H0z" fill="none"/>
              </svg>
            </div>
            <div style="border: 1px solid #ebebeb; -webkit-box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.1);-moz-box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.1);box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.1);">
			<ul class="menuitems" id="menuListLeft" style="border: 1px solid rgba(0,0,0,0); margin: 0!important;">
				<li style="padding: 5px; padding-left: 15px; padding-top: 15px; font-size: 16px;">المطابخ:</li>
				<li id="All" style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 15px; color: grey;"><a onclick="highlightSpecialty('All');">جميع المطابخ</a></li>
					<?php 
                        $headers = array(
                        	"Accept: application/json",
                        	"Content-Type: application/json"
                        );
                        
                        $data = array(
                        	"id" => 0
                        );
                        
                        $ch = curl_init( $baseurl.'/showRestaurantsSpecialities' );
                        
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        
                        $return = curl_exec($ch);
                        
                        $json_data = json_decode($return, true);
                        
                        foreach ($json_data['msg'] as $key1 => $value1) {
                            ?>
        						<li id="<?php echo $value1['Restaurant']['speciality']; ?>" style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 15px; color: grey;"><a onclick="highlightSpecialty('<?php echo $value1['Restaurant']['speciality']; ?>');"><?php echo $value1['Restaurant']['speciality']; ?></a></li>
                            <?php
                        }
                
                    ?>
				<li style="  padding: 5px; padding-left: 15px; padding-bottom: 0; font-size: 20px;"> </li>
			</ul>
			<div id="minimum_order_container" style="padding-top: 25px; padding-bottom: 25px; border-top: 1px solid rgba(0,0,0,0.1);">
			    <div id="minimum_order_div" style="margin-left: 15px; padding-top: 5px;">
        			<div><label for="slider_container" style="display: inline-block; margin-right: 5px; margin-left: 5px;">الحد الأدنى للطلب: </label><div style="direction: ltr; width: 60px; display: inline-block; text-align: right;"><span id="minimum_order" style="display: inline-block;">0</span><span  style="display: inline-block;"> SAR</span></div></div><br>
        			<div style="display: flex; align-items: center; margin-top: 10px; direction: ltr;" id="slider_container"><span style="width: 20px; text-align: center;">0</span><input id="slider" type="range" min="0" max="200" value="0" step="1"><span id="order_maximum" style="margin-left: 5px; text-align: center;">200</span></div>
                </div>
            </div>
        </div>
        </div>
		<div class="col80 left" id="restaurants_list">
		    <div style="padding: 10px;"><span onClick="sortByDistance()" style="cursor: pointer;">فرز حسب الأقرب</span></div>

			<?php 
				$lat = $_GET['lat'];
				$long = $_GET['lng'];

				if( !empty($lat) && !empty($long) ) { 

					$headers = array(
						"Accept: application/json",
						"Content-Type: application/json"
					);

					$data = array(
						"lat" => $lat,
						"long" => $long
					);



                    $endpoint = "/showRestaurants";


                    $json_data = curl_request($data, $endpoint, $baseurl);
					if($json_data['code'] !== 200){
						//echo "Error in fetching data, try again later..";
						?>
						<div style="margin: 70px 0;" class="textcenter">
							<img src="assets/img/norestaurants.png" style="display: inline-block;" alt="" />
							<h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
						</div>
						<?php
						//
					} else {
						foreach ($json_data['msg'] as $key => $value) {
							//var_dump($value);
							if(!empty($value['Restaurant']['id'])) {
								$headers = array(
									"Accept: application/json",
									"Content-Type: application/json"
											);

								$data = array(
									"id" => $value['Restaurant']['id']
								);

								$ch = curl_init( $baseurl.'/showRestaurantsMenu' );

								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
								curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
								curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

								$return = curl_exec($ch);

								$background_json = json_decode($return, true);
								$background_image = $image_baseurl.$background_json['msg'][0]['Restaurant']['cover_image'];
								
								if(checkImageExist($image_baseurl.$value['Restaurant']['image'])=="200")
                        		{
                        		    $restaurantImage=$image_baseurl.$value['Restaurant']['image'];
                        		}
                        		else
                        		{
                        		    $restaurantImage="assets/img/noImage.png";
                        		}
								?>
								    <a href="details.php?id=<?php echo $value['Restaurant']['id']; ?>" name="<?php echo $value['Restaurant']['name']; ?>" data-specialty="<?php echo $value['Restaurant']['slogan']; ?>" data-minimum="<?php echo $value['Restaurant']['min_order_price']; ?>" distance="<?php echo $value[0]['distance']; ?>"> 
        								<div class="col3" style="float: right; font-family: Montserrat; border-radius: 10px; border: 1px solid #ebebeb; -webkit-box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.33);
                                                                                                                                            -moz-box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.33);
                                                                                                                                            box-shadow: -1px 11px 28px -10px rgba(0,0,0,0.33);">
											<div style="height: 50%; width: 100%; background-color: #f0f0f0; background-image: url(<?php echo $background_image; ?>); ">
												<img style="min-height: 50px; height: 50px; border-radius: 10px; margin: 10px; margin-bottom: 30px;" src="<?php echo $restaurantImage; ?>" alt="<?php echo substr($value['Restaurant']['name'], 0, "20"); ?>">
											</div>
											<div style="height: 50%; width: 100%;">
											    <div style="width: 100%; display: inline-block; margin-top: 10px;">
											        <div style="float: left;">
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
											        <div style="direction: ltr; text-align: right; font-weight: bold;"><?php echo substr($value['Restaurant']['name'], 0, "20"); ?></div>
											    </div>
                                                <div style="font-weight: 100; font-size: 12px; margin-bottom: 10px;"><?php echo $value['Restaurant']['slogan']; ?></div>
                                                <div style="width: 100%; text-align: right;">
                                                    <div style="display: inline-block; width: 20%;">
                                                        <span style="display: block; font-size: 12px;">تحضير:</span>
                                                        <span style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php echo $value['Restaurant']['preparation_time']."دقيقة "."&nbsp;";?></span>
                                                    </div>
                                                    <div style="display: inline-block; width: 20%;">
                                                        <span style="display: block; font-size: 12px;">توصيل:</span>
                                                        <div style="height: 50%; width: 100%; display: block; font-size: 12px;"><?php echo $value['Tax']['delivery_time']."دقيقة "."&nbsp;"; ?></div>
                                                    </div>
                                                    <div style="display: inline-block; width: 24%; display: none;">
                                                        <span style="display: block; font-size: 12px;">رسوم التوصيل:</span>
                                                        <span style="height: 50%; width: 100%; display: block; font-size: 12px; direction: ltr;"><?php  echo $value['Tax']['delivery_fee_per_km']."&nbsp;".' '; echo $value['Currency']['symbol']; ?></span>
                                                    </div>
                                                    <div style="display: inline-block; width: 24%;">
                                                        <span style="display: block; font-size: 12px;">الحد الأدنى:</span>
                                                        <span style="height: 50%; width: 100%; display: block; font-size: 12px; direction: ltr;"><?php echo $value['Restaurant']['min_order_price']."&nbsp;".' '; echo $value['Currency']['symbol'];  ?></span>
                                                    </div>                                                    
                                                    <div style="display: inline-block; width: 24%;">
                                                        <span style="display: block; font-size: 12px;">مسافة:</span>
                                                        <span style="height: 50%; width: 100%; display: block; font-size: 12px; direction: rtl;"><?php echo intval($value[0]['distance'])." كم"; ?></span>
                                                    </div>

                                                </div>
											</div>
										</div>
                                    </a>
								<?php
							}
							//
						}
						//
					}


					?>
						<div class="clear"></div>
					<?php

				} else {
					?>
					<div style="margin: 70px 0;" class="textcenter">
						<img src="assets/img/norestaurants.png" style="display: inline-block;" alt="" />
						<h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
					</div>
					<?php
				} //
				?>

		</div>
	</div>
</div>
<script>
	$("#header-search").keyup(function() {
      search();
    });
    var mySlider = document.getElementById('slider');
    rangesliderJs.create(mySlider, {
      onInit: (value, percent, position) => {},
      onSlideStart: (value, percent, position) => {},
      onSlide: (value, percent, position) => {changeMinimumOrder(value);},
      onSlideEnd: (value, percent, position) => { filterMinimumOrder(value);}
    });
</script>
<style>
    #header-search {
      width: 100%;
      background: @header-color;
      color: black;
      font-size: 12pt;
      border: 1px solid rgba(0,0,0,0.1);
      outline: 0;
      vertical-align: -50%;
      height: 44px;
      /*border: 1px solid #333;*/
    }
    
    #header-search::-webkit-input-placeholder {
      color: black;
    }
    
    #search-field svg {
      fill: rgba(0,0,0,0.3);
      width: 30px;
      position: absolute;
      top: 8px;
      left: 5px;
    }
    #search-field{display: inline-block; position: relative; margin-bottom: 10px;}
</style>
<?php require_once("footer.php"); ?>