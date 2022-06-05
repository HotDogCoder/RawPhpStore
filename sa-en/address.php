<?php 
if( isset($_SESSION['id']) && $_SESSION['user_type'] == "user" )
{ 

    $data = array();
    $endpoint = "/showCountries";

    $json_data = curl_request($data, $endpoint, $baseurl);

	
?>
<h2 class="title">My Addresses</h2>
<div class="form address">
    <div class="left col60">

        <h3>Add Delivery Address</h3>
        <div class="form">
            <form action="dashboard.php?p=address&action=add_address" id="adrsfrm" method="post">
                <div class="left col50">
                    <p>
                        <input type="text" name="str" id="str" required>
                        <label alt="Street" placeholder="Street"></label>
                    </p>
                </div>
                <div class="right col50">
                    <p>
                        <input type="text" name="zp" id="zp" required>
                        <label alt="ZIP" placeholder="ZIP"></label>
                    </p>
                </div>
                <div class="clear"></div>

                <div class="left col50">

                    <p>

                        <select name="cty" id="cty" class="form-control" required>
                            <option value="">Select City</option>
                            <?php 
                                foreach($json_data['taxes'] as $cntry) 
                                {
                                    ?>
                                        <option value="<?php echo $cntry['Tax']['city']; ?>"><?php echo $cntry['Tax']['city']; ?></option>
                                    <?php
                                }
                            ?>
                        </select>

                    </p>
                </div>
                <div class="right col50">
                    <p>
                        <select name="stt" id="stt" class="form-control" required>
                            <option value="">Select State</option>
                            <?php 
                                foreach($json_data['taxes'] as $cntry) 
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

                <div class="col100">
                    <p>
                        <select name="cntry" id="cntry">
                            <option value="">Select Country</option required>
                            <?php
                                foreach($json_data['currency'] as $cntry) 
                                {
                                    ?>
                                        <option value="<?php echo $cntry['Currency']['country']; ?>"><?php echo $cntry['Currency']['country']; ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </p>
                </div>
                
                <div class="col100">
                    <p>
                        <textarea maxlength="150" class="textarea" name="ins" id="ins" placeholder="Note" required></textarea>
                        <span class="characterLimit"></span>
                    </p>
                </div>
                <div class="col100">
                    <p>
                        <input type="text" id="address" required />
                    </p>
                </div>
                <div class="col100">
                    <p>
                        <input type="hidden" name="lat" id="us2-lat" />
                        <input type="hidden" name="lng" id="us2-lon" />
                        <div id="us2" class="mapPreview"></div>
                    </p>
                </div>
                <div class="col100">
                    <p>
                        <input type="submit" value="Add Delivery Address">
                    </p>
                </div>
            </form>
        </div>

    </div>
    <div class="right col40">
        <br><br>
        <?php 
			$user_id = $_SESSION['id'];

			$data = array(
				"user_id" => $user_id
			);

            $endpoint = "/getDeliveryAddresses";
            $json_data = curl_request($data, $endpoint, $baseurl);
			if($json_data['code'] !== 200)
			{
				echo "<div class='alert alert-danger'>No record found</div>";

			} 
			else 
			{
				echo '<div class="addresslist">';
				foreach( $json_data['msg'] as $stttr => $vaaal ) 
				{
					?>
                        <div class="itm">
                            <div class="street">
                                <?php echo ucwords($vaaal['Address']['street'].", ".$vaaal['Address']['city'].", ".$vaaal['Address']['state']." ".$vaaal['Address']['country']); ?> - 
                                <a href="https://www.google.com/maps/@<?php echo $vaaal['Address']['lat']." , ".$vaaal['Address']['long']; ?>,15z" target="_blank">View Map</a>
                            </div>
                            <?php
    					        if(@$vaaal['Address']['instructions']!="")
    					        {
    					            ?>
                                        <span class="addressInstructions">Instructions: <?php echo $vaaal['Address']['instructions']; ?></span>
                                    <?php
    					        }
        					?>
                        </div>
                    <?php
				}
				echo '</div>';
			}

		?>

    </div>
    <div class="clear"></div>
</div>

<?php } else {
	
	echo "<script>window.location='index.php'</script>";
    die;
    
} ?>