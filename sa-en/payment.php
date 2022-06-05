<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "user" )
{ 

    $user_id = $_SESSION['id'];
    $data = array(
    	"user_id" => $user_id
    );
    $endpoint = "/getPaymentDetails";
    $json_data = curl_request($data, $endpoint, $baseurl);

?>

<h2 class="title">Payment Method</h2>
<div class="form payment">
    <div class="left col60">

        <h3>Add Payment Method</h3>
        <div class="form">
            <form action="dashboard.php?p=payment&action=add_payment" id="paymethd" method="post">
                <p>
                    <input type="text" name="cardname" id="cardname" required>
                    <label alt="Card Holder Name" placeholder="Card Holder Name"></label>
                </p>
                <p>
                    <input type="text" name="cardnum" id="cardnum" data-inputmask="'mask': '9999 9999 9999 9999'" maxlength="19" required>
                    <label alt="Card Number" placeholder="Card Number"></label>
                </p>
                <div class="col30 left">
                    <p>
                        <input type="text" maxlength="4" name="cardcvc" id="cardcvc" required>
                        <label alt="CVV" placeholder="CVV"></label>
                    </p>
                </div>
                <div class="right col70">
                    <div class="col50 left">
                        <p>
                            <select name="cardmn" id="cardmn">
                                <option value="">Exp Month</option>
                                <option value="01">January</option>
                                <option value="02">February </option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </p>
                    </div>
                    <div class="col50 right">
                        <p>
                            <select name="cardyr" id="cardyr">
                                <option value="">Exp Year</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <p>
                    <input type="submit" value="Add Payment Method">
                </p>
            </form>
        </div>
    </div>
    <div class="right col40">
        <br><br>
        <?php 

			if($json_data['code'] !== 200){

			} 
			else 
			{

				echo '<div class="paymentlist">';
    				foreach( $json_data['msg'] as $stttr => $vaaal ) 
    				{
    					?>
                            <div class="itm">
                                <div class="cardnm left col60">
                                    <strong><?php echo ucwords($vaaal['name']); ?></strong>
                                    <br>
                                    <?php echo "**** **** **** ".$vaaal['last4']; ?>
                                    <br><span class="expire">Exp. <?php echo $vaaal['exp_month']."/".$vaaal['exp_year']; ?></span>
                                </div>
                                <div class="cardtype right col40 textright">
                                    <strong>&nbsp;</strong>
                                    <br>
                                    <?php echo $vaaal['brand']; ?>
                                </div>
                                <div class="clear"></div>
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