<?php
include('config.php');
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
    <title>Payment | Checkout</title>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
	<link href="../../assets/css/fonts.css" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css?time<?php echo time(); ?>">
	<script src="../../assets/js/jquery-1.12.4.js"></script>
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
		       
		        //thank you page
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
    		    if(isset($_GET['payment']))
		        {   
		            if($_GET['payment'] == 'omise')
		            {
		                
		                ?>
		                    <form id="checkoutForm" method="POST" action="charge.php">
                                <script type="text/javascript" src="https://cdn.omise.co/omise.js" data-key="<?php echo OMISE_PUBLIC_KEY ?>" data-amount="<?php echo $price;?>" data-currency="USD"  data-default-payment-method="credit_card"></script>
                            </form>
		                <?php
		            }
		        }
		        
		        
		    ?>
		    
		
		
	</div>
	<style>
	    .omise-checkout-button
	    {
	        display:none;
	    }
	</style>
	<script>
	
	    function showLoading()
	    {
	        $('#preloader').fadeIn();
	    }
	
	    function selectPayment(payment_method)
	    {
	        document.getElementById(payment_method).checked = true;
	    }
	    
	    $( ".omise-checkout-button" ).click();
	</script>
	
</body>
</html>