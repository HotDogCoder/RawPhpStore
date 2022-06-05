<?php 
if(isset($_GET['add'])) {
	foreach ($_POST['menu_extra_item_name'] as $key => $value) {
		if(!empty($value)){
			$a[] = $key;
		}
	}

	$totalrows = count($a);

	for($i=1; $i<=$totalrows; $i++) {



		$aa[] = array(
				"menu_extra_item_name" => $_POST['menu_extra_item_name'][$i],
				"menu_extra_item_quantity" => $_POST['menu_extra_item_quantity'][$i],
				"menu_extra_item_price" => $_POST['menu_extra_item_price'][$i]
			);
	}

	$aaa = $aa;

	$data = array(
		"menu_item_price" => $_POST['menu_item_price'],
		"menu_item_quantity" => $_POST['menu_item_quantity'],
		"menu_item_name" => $_POST['menu_item_name'],
		"menu_extra_item" => $aaa
	);


	$data_to_save = json_encode($data, true);

	$cookie_name = "cartitem";
	$cookie_value = $data_to_save;

	if(!isset($_COOKIE[$cookie_name])) {
	    //echo "Cookie named '" . $cookie_name . "' is not set!";
	    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
	    header("Location: cart.php");
	    echo "<script>window.location='cart.php';</script>";
	} else {
	    //echo "Cookie '" . $cookie_name . "' is set!<br>";
	    //echo $_COOKIE[$cookie_name];
	    header("Location: cart.php");
	    echo "<script>window.location='cart.php';</script>";
	}
}

else {
require_once("header.php");
?>

<div class="section"><div class="wdth">
	<h2 class="title">Cart</h2>
	<table class="order_table cart">
		<thead>
			<tr>
				<td>Menu Name</td>
				<td width="200">Qty.</td>
				<td width="200">Price</td>
			</tr>
		</thead>
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
		<?php if(isset($_SESSION['id'])){
			?><a href="checkout.php" class="proeceedbtn"><button class="button">Proceed to Checkout</button></a><?php
		} else {
			?><a href="javascript:;" onclick="popup('login')" class="proeceedbtn"><button class="button">Please Login to Purchase</button></a><?php
		} ?>
	</div>
</div></div>

<?php require_once("footer.php");
} ?>