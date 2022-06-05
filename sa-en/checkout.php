<?php require_once("header.php"); ?>

<?php 
$cookie_name = "cartitem";
$menuitems_val = $_COOKIE[$cookie_name];
if(isset($_COOKIE[$cookie_name])) {
	$val = json_decode($_COOKIE[$cookie_name], true);
	$qty = $val['menu_item_quantity'];
	$mainmenuprice = $val['menu_item_price']*$val['menu_item_quantity'];
	foreach ($val['menu_extra_item'] as $key => $value) {
		$mainmenu_subitemsprice[] = $value['menu_extra_item_price']*$val['menu_item_quantity'];
	} 
	$sumofpr = array_sum($mainmenu_subitemsprice);
	$totalamount = $mainmenuprice+$sumofpr;
} else {
	$qty = "";
	$totalamount = "";
}
?>

<div class="section"><div class="wdth">
	<h2 class="title">Checkout</h2>
	<?php if(isset($_GET['sub'])) {
		if($_GET['sub']=="mit") {

			if(isset($_POST['paymentmethod'])) {
				if($_POST['paymentmethod']=="cod") {
					$payment_id = "0";
					$cod = "1";
				}
				if($_POST['paymentmethod']=="card"){
					$payment_id = "1";
					$cod = "0";
				}
			}
			$instructions = $_POST[''];
			$coupon_id = $_POST[''];
			$restaurant_id = $_POST[''];
			$quantity = $qty;
			$user_id = $_SESSION['id'];
			$address_id = $_POST[''];
			$price = $totalamount;
			$menu_item = array($menuitems_val);

		}
	} ?>
	<form action="checkout.php?sub=mit" method="post">
		<div class=""><div class="wdth">
			<h3>Fill information below</h3>
			<div class="rw">
				<h4>Payment Method</h4>
				<select name="paymentmethod">
					<option value="cod">Cash on Delivery (COD)</option>
					<?php 
						$user_id = $_SESSION['id'];

						$data = array(
							"user_id" => $user_id
						);



                    $endpoint = "/getPaymentDetails";

                    $json_data = curl_request($data, $endpoint, $baseurl);

						if($json_data['code'] !== 200) {

						} else {
							foreach( $json_data['msg'] as $stttr => $vaaal ) {
								?>
								<option value="card"><?php echo $vaaal['brand']; ?> Card (<?php echo "**** **** **** ".$vaaal['last4']; ?>)</option>
								<?php
							}
						}

					?>
				</select>
			</div>
			<div class="rw">
				<h4>Instructions</h4>
				<textarea name="instructions"></textarea>
			</div>
			<div class="rw">
				<h4>Coupon id</h4>
				<input type="text" name="coupon_id">
			</div>
			<div class="rw">
				<h4>Restaurant</h4>
				<select name="restaurant_id">
					<?php 
					$lat = "0";
					$long = "0";
					

					
					$data = array(
						"lat" => $lat,
						"long" => $long
					);
					



                    $endpoint = "/showRestaurants";

                    $json_data = curl_request($data, $endpoint, $baseurl);


                    if($json_data['code'] !== 200){
					
					} else {
						foreach ($json_data['msg'] as $key => $value) {
							if(!empty($value['Restaurant']['id'])) {
								?>
								<option value="<?php echo $value['Restaurant']['id']; ?>"><?php echo $value['Restaurant']['name']; ?></option>
								<?php
							}
							//
						}
						//
					}
					
					curl_close($ch);
					?>
				</select>
			</div>
			<div class="rw">
				<h4>Delivery Address</h4>
				<select name="address_id">
					<?php 
					$user_id = $_SESSION['id'];

					
					$data = array(
						"user_id" => $user_id
					);
					

                    $endpoint = "/getDeliveryAddresses";

                    $json_data = curl_request($data, $endpoint, $baseurl);

					if($json_data['code'] !== 200){
					
					} else {
						foreach ($json_data['msg'] as $key => $value) { 
							?>
							<option value="<?php echo $value['Address']['id']; ?>"><?php echo ucwords($value['Address']['apartment']." ".$value['Address']['street'].", ".$value['Address']['city'].", ".$value['Address']['state']." ".$value['Address']['zip'].", ".$value['Address']['country']); ?></option>
							<?php 
							//
						}
						//
					}
					
					curl_close($ch);
					?>
				</select>
			</div>

			<table class="order_table cart">
				<tbody>

					<?php $cookie_name = "cartitem";
					if(isset($_COOKIE[$cookie_name])) {
					    $val = json_decode($_COOKIE[$cookie_name], true);
					    $mainmenuprice = $val['menu_item_price']*$val['menu_item_quantity'];
					    ?>
					    <tr <?php if(!empty($val['menu_extra_item'])) { echo 'class="removeborder"'; } ?> >
				    		<td><?php echo $val['menu_item_name']; ?></td>
				    		<td><?php echo $val['menu_item_quantity']; ?></td>
				    		<td><?php echo $val['menu_item_price']; ?></td>
		    			</tr>
			    		<tr>
			    			<td colspan="3">
						    	<?php
							    foreach ($val['menu_extra_item'] as $key => $value) {
							    	//var_dump($value);
							    	?>
							    		<table width="100%">
					    					<tr class="extraitemtr">
								    			<td><?php echo $val['menu_item_quantity']*$value['menu_extra_item_quantity']; ?> x <?php echo $value['menu_extra_item_name']; ?> = <?php echo $value['menu_extra_item_price']; ?></td>
								    		</tr>
					    				</table>
							    	<?php
								    	$mainmenu_subitemsprice[] = $value['menu_extra_item_price']*$val['menu_item_quantity'];
							    } 
							    $sumofpr = array_sum($mainmenu_subitemsprice);
							    ?>
					   		</td>
					    </tr>
					    <tr class="subtotal">
					    	<td colspan="2">Subtotal</td>
					    	<td><?php echo $mainmenuprice+$sumofpr; ?></td>
					    </tr>
					    <tr class="total">
					    	<td colspan="2">Total</td>
					    	<td><?php echo $mainmenuprice+$sumofpr; ?></td>
					    </tr>
				    	<?php
					} else { ?>
						<tr>
							<td colspan="3">
								<h3 class="cartempty">(Cart is Empty)</h3>
							</td>
						</tr>
					<?php } ?>

				</tbody>
			</table>
			<div class="cartbelow">
				<a href="paynow.php" class="proeceedbtn"><button class="button">Proceed to Payment</button></a>
			</div>
		</div></div>
	</form>
</div></div>

<?php require_once("footer.php"); ?>