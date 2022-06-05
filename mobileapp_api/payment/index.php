<?php
    include('config.php');
    
    if (isset($_GET['payment'])) 
    {
        //print_r($_POST);
        if ($_GET['payment'] == 'paypal') 
        {
            
            try 
            {
                // login with paypal module
                $payer = new \PayPal\Api\Payer();
                $payer->setPaymentMethod('paypal');
                
                $amount = new \PayPal\Api\Amount();
                $amount->setTotal($total_price);
                $amount->setCurrency(PAYPAL_CURRENCY);
                
                $transaction = new \PayPal\Api\Transaction();
                $transaction->setAmount($amount);
                
                $redirectUrls = new \PayPal\Api\RedirectUrls();
                $redirectUrls->setReturnUrl(SET_RETURN_URL)
                    ->setCancelUrl(SET_CANCEL_URL);
                
                $payment = new \PayPal\Api\Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions(array($transaction))
                    ->setRedirectUrls($redirectUrls);
            
            
                $payment->create($apiContext);
                // login with paypal module
                
                echo "<script>window.location='".$payment->getApprovalLink() ."'</script>";
                
                // echo "<pre>";
                //     echo $payment;
                //     echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
                // echo "</pre>";
                
            } 
            catch (\PayPal\Exception\PayPalConnectionException $ex) 
            {
                // This will print the detailed information on the exception.
                //REALLY HELPFUL FOR DEBUGGING
                echo $ex->getData();
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    
    <title><?php echo TITLE_TAG; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
	<link href="assets/css/fonts.css" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="assets/css/style.css?time<?php echo time(); ?>">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <script src="assets/js/jquery-1.12.4.js"></script>
    <script>
	   $(window).load(function() {
	     $('#status').fadeOut();
	     $('#preloader').delay(350).fadeOut('slow');
	     $('body').delay(350).css({'overflow':'visible'});
	   })
	</script>
	
</head>
<body>
    
    
<div id="preloader" align="center">
  <div id="loading">
    <div class="spinner loading"></div>
  </div>
</div>

    
	<div class="mainServicesDetail">
	 
		    <?php
		        
		        //paypal thank you page
		        if(isset($_GET['status']))
		        {
		                
		            if($_GET['status']=="paymentSuccess")
		            {
		                ?>
    		                <br><br>
    		                <div class="container">
            		            <div class="restaurantInfo" style="text-align: center;">
                			        <img src="../../assets/images/done.png" style="width: 80px;">
                			        <h2 style="margin: 10px 0px;font-weight: 600;font-size: 18px; color:#363B3F;">Thank You</h2>
                			        <p>Your Payment is Successfully Done</p>
                			    </div>
                	        </div>
    		           <?php
		            }
		            else
		            if($_GET['status']=="paymentFaild")
		            {
		                ?>
    		                <br><br>
    		                <div class="container">
            		            <div class="restaurantInfo" style="text-align: center;">
                			        <img src="../../assets/images/error.png" style="width: 80px;">
                			        <h2 style="margin: 10px 0px;font-weight: 600;font-size: 18px; color:#363B3F;">Error</h2>
                			        <p>Something went wrong in placing the order. Please contact the administrator</p>
                			    </div>
                	        </div>
    		           <?php
		            }
		           die();
		        }
		        else
    		    if(isset($_GET['paymentId']) && isset($_GET['token']) && isset($_GET['PayerID']))
		        {
		                
		                $paymentId=$_GET['paymentId'];
		                
		                $App_Data;
		                //covert base64 to normal json from session App_Data
                        $json_data=base64_decode($App_Data); 
                        
                        //convert normal json into array
                        $json_app_date=json_decode($json_data, true);
                        // print_r($json_app_date);
                        
                        //modify json_array
                        $transaction_obj=array(
                                                "cod"=>"0",
                                                "payment_id"=>"0",
                                                "transaction"=> array
                            					(
                            					     "value"=>$paymentId,
                                                    "type"=>"paypal"
                            					)
                                            );
                        $newArrayPost=array_replace($json_app_date,$transaction_obj);
                        
                        // post data on service
                        $url=BASE_URL.'api/placeOrder';
                        $data =$newArrayPost;
                        //echo json_encode($data);
                        $result_data=@curl_request($data,$url);
                        
                        if($result_data['code']=="200")
                        {
                           echo "<script>window.location='./?status=paymentSuccess'</script>";
                        }
    		      
	                    die();
		        }
		        else
		        if(isset($_GET['payment']))
		        {   
		            
		            if(@$_GET['payment'] == 'cod')
		            {
		                
		                echo "<script>window.location='paymentMethods/cod/index.php?payment=cod'</script>";
		            }
		            else
		            if($_GET['payment'] == 'cards')
		            {
		                echo "<script>window.location='paymentMethods/stripe/index.php?payment=cards'</script>";
		            }
		            else
		            if(@$_GET['payment'] == 'omise')
		            {
		                echo "<script>window.location='paymentMethods/omise/index.php?payment=omise'</script>";
		            }
		        }
		        else
		        {
		            ?>
		            
		                
		                    <div class="container">    
                    			<div class="serviceDetails">
                    			    <div class="restaurantInfo" style="text-align: center;">
                    			        <div style="background: url('<?php echo BASE_URL.$restaurantImage; ?>') , grey;margin-left:35%;height: 80px;width: 80px;background-repeat: no-repeat;border-radius: 50%;background-size: cover;background-position: center;"></div>
                    			        <h2 style="margin: 10px 0px;font-weight: 500;font-size: 18px; color:#363B3F;"><?php echo $restaurantName; ?></h2>
                    			    </div>
                    			    
                    				<div class="detailOption">
                    				    <hr>
                    				    <p style="color: #6E6F74;font-size: 14px;font-weight: 300;"><?php echo SERVICE_DETAILS; ?></p>
                    					<div class="options">
                    						<ul>
                    							<!--<li><span><?php echo RIDER_TIP; ?></span></li>-->
                    							<li><span><?php echo SUB_TOTAL; ?></span></li>
                    							<li><span><?php echo TAX; ?> (<?php echo $taxPercentage; ?>%)</span></li>
                    							<li><span><?php echo DISCOUNT; ?> (0%)</span></li>
                    							<li><span><?php echo DELIVERY_FEE; ?></span></li>
                    						</ul>
                    						<ul class="textRight">
                    							<!--<li><span><?php echo $currency_symbol.$rider_tip; ?></span></li>-->
                    							<li><span><?php echo $sub_total.' '.$currency_symbol; ?></span></li>
                    							<li><span><?php echo $tax.' '.$currency_symbol;; ?></span></li>
                    							<li><span>&nbsp;</span></li>
                    							<li><span><?php echo $delivery_fee.' '.$currency_symbol; ?></span></li>
                    						</ul>
                    					</div>
                    					<hr>
                    					<div class="total">
                    						<ul>
                    							<li><span><?php echo TOTAL; ?></span></li>
                    							<li class="dollar textRight "><span><?php echo $price.' '.$currency_symbol;; ?></span></li>
                    						</ul>
                    					</div>
                    				</div>
                    			</div>
                    		</div>
                    		<style>
		                    
                               body
                               {
                                   -webkit-user-select: none; /* Safari */
                                  -ms-user-select: none; /* IE 10+ and Edge */
                                  user-select: none; /* Standard syntax */
                               }
                                
		                    </style>
                    		<div class="container">
                    				<div class="mainSecureCheckout">
                    					<div class="sectionHeading padding20">
                    						<h3><?php echo SECURE_CHECKOUT; ?></h3>
                    					</div>
                    					<hr>
                    					<form id="formvisa" action="index.php?action=payNow" method="get">
                    					    <!--<input type="hidden" name="total_amount" value="<?php echo $order_data['price']; ?>">-->
                    					    <div class="paymentMethod">
                    					    <h3 class="padding20"><?php echo PAYMENT_METHOD; ?></h3>
                    						<div class="itsSafeToPay padding20">
                    						    <i class="fa fa-lock" aria-hidden="true"></i>
                    							<p style="color:#797A7E;font-weight: 300;font-size: 13px;"> 
                    								<?php echo SSL_TEXT; ?> 
                    							</p>
                    						</div>
                    						<div class="pamentTypes" style="margin-top: 10px;">
                    							
                    							<?php
                    							    if(PAYMENT_METHOD_COD=="true")
                    							    {
                    							       ?>
                        							        <div class="choseOption" onclick="selectPayment('cod');">
                                								<input type="radio" id="cod" name="payment" value="cod" style="border: 0px; text-decoration: none;" checked>
                                								<!--<img src="assets/images/cod.png">-->
                                								<h4 style="margin: 0;color: #363B3F;font-weight: 600;font-size: 17px;"><?php echo CASH_ON_DELIVERY; ?></h4>
                                							</div>
                    							       <?php 
                    							    }
                    							    
                    							    if(PAYMENT_METHOD_STRIPE=="true")
                    							    {
                    							       ?>
                        							        <div class="choseOption" onclick="selectPayment('cards');">
                                								<input type="radio" id="cards" name="payment" value="cards">
                                								<img src="assets/images/cards.png">
                                							</div>
                    							       <?php 
                    							    }
                    							    
                    							    if(PAYMENT_METHOD_PAYPAL=="true")
                    							    {
                    							       ?>
                        							        <div class="choseOption" onclick="selectPayment('paypal');">
                                								<input type="radio" id="paypal" name="payment" value="paypal">
                                								<img src="assets/images/paypal.png">
                                							</div>
                    							       <?php 
                    							    }
                    							    
                    							    if(PAYMENT_METHOD_omise=="true")
                    							    {
                    							       ?>
                        							       <div class="choseOption" onclick="selectPayment('omise');">
                                								<input type="radio" id="omise" name="payment" value="omise">
                                								Pay With omise
                                							</div>
                    							       <?php 
                    							    }
                    							?>
                    							
                    							
                    							
                    							
                    							
                    							<!--<div class="choseOption" onclick="selectPayment('tap');">-->
                    							<!--	<input type="radio" id="tap" name="payment" value="tap">-->
                    							<!--	Pay With Tap-->
                    							<!--</div>-->
                    							
                    							<button type="submit" class="proceedBtn" onclick="showLoading()" style="border-radius: 25px!important; background-color: #E86942;">
                    								<?php echo CONTINUE_BTN; ?>
                    		                    </button>
                    						</div>
                    					</div>
                    				    </form>
                    				</div>
                    			
                    		</div>
		            <?php
		        }
		        
		        
		        
		    ?>
		    
		
		
	</div>
	
	<script>
	
	    function showLoading()
	    {
	        $('#preloader').fadeIn();
	    }
	
	    function selectPayment(payment_method)
	    {
	        document.getElementById(payment_method).checked = true;
	    }
	    
	    
	</script>
	
</body>
</html>