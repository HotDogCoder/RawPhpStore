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
		            if(@$_GET['payment'] == 'MyFatoorah')
		            {
		                 
		                ?>
		                    <form id="form-container" method="post" action="authorize.php">
                              <!-- Tap element will be here -->
                              <div id="element-container"></div>
                              <div id="error-handler" role="alert"></div>
                              <div id="success" style=" display: none;;position: relative;float: left;">
                                    Success! Your token is <span id="token"></span>
                              </div>
                              <!-- Tap pay button -->
                              <button id="tap-btn">Submit</button>
                            </form>
                            <script src="https://secure.gosell.io/js/sdk/tap.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.4/bluebird.min.js"></script>
                            <script src="script.js"></script>
		                <?php
		                
		            }
		            else
		            if ($_GET['payment'] == 'payMyFatoorah') 
                    {
                        $selectCard=@$_GET['selectCard'];
                        $endpoint_myfatoorah   =   "ExecutePayment";
                        $data_myfatoorah       =   [
                            'PaymentMethodId'    => $selectCard,
                            'CustomerName'  =>  'Ahmed',
                            'DisplayCurrencyIso'  =>  'KWD',
                            'MobileCountryCode'  =>  '+965',
                            'CustomerMobile'  =>  '92249038',
                            'CustomerEmail'  =>  'aramadan@myfatoorah.com',
                            'InvoiceValue'  =>  $total,
                            'CallBackUrl'  =>  BASE_URL.'payment/paymentMethods/MyFatoorah/index.php?callBack=payMyFatoorah&type=success',
                            'ErrorUrl'  =>  BASE_URL.'payment/paymentMethods/MyFatoorah/index.php?callBack=payMyFatoorah&type=error',
                            'Language'  =>  'en',
                            'CustomerReference'  =>  'ref 1',
                            'CustomerCivilId'  =>  '12345678',
                            'UserDefinedField'  =>  'field',
                            'ExpireDate'  =>  '',
                            'CustomerAddress'  =>  array(
                                                        'Block'    => 'blk',
                                                        'Street'  =>  'street',
                                                        'HouseBuildingNo'  =>  'houseqwe',
                                                        'Address'  =>  'asdadfasdf test',
                                                        'AddressInstructions'  =>  'address instruction',
                                                       
                                                    
                                                ),
                            'InvoiceItems[]'  =>  array(
                                    'ItemName'    => 'test product',
                                    'Quantity'  =>  '1',
                                    'UnitPrice'  =>  '200'
                                    
                                   
                                
                            )
                           
                        ];
                        
                        $result=curl_request_myfatoorah($data_myfatoorah,$endpoint_myfatoorah,$apibaseurl_myfatoorah,$token_myfatoorah);
                        //print_r($result);
                        echo "<script>window.location='".$result['Data']['PaymentURL'] ."'</script>";
                    }
		        }
		        else
		        if(isset($_GET['callBack']))
		        {
		                
		                if(@$_GET['type']=="success" && isset($_GET['paymentId']))
		                {
		                    $paymentId=$_GET['paymentId'];
		                    
    		                //modify json_array
                            $transaction_obj=array(
                                                    "cod"=>"0",
                                                    "payment_id"=>"0",
                                                    "transaction"=> array
                                					(
                                					     "value"=>$paymentId,
                                                        "type"=>"MyFatoorah"
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
        		            else
                            {
                                echo "<script>window.location='./?status=paymentFaild'</script>";
                            }
        		            
		                }
    	                else
                        {
                            echo "<script>window.location='./?status=paymentFaild'</script>";
                        }
		                
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
	    
	    function addCard()
	    {
	        
	        document.getElementById("preloader").style.display = "block";
            var full_name=document.getElementById("full_name").value;
            var car_number=document.getElementById("car_number").value;
            var month=document.getElementById("month").value;
            var year=document.getElementById("year").value;
            var cvv_number=document.getElementById("cvv_number").value;
            
            if(full_name=="" || car_number=="" || month=="" || year=="" || cvv_number=="")
            {
                document.getElementById("preloader").style.display = "none";
                alert("Information must be filled");
                return false;    
            }
            
            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    //alert(xmlhttp.responseText);
                    //document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                    if(xmlhttp.responseText=="200")
                    {
                        document.getElementById("preloader").style.display = "block";
                        window.location='index.php?payment=cards';
                    }
                    else
                    {
                        //alert(xmlhttp.responseText);
                        if(xmlhttp.responseText)
                        {
                            window.location='./?payment=cards';
                            document.getElementById("preloader").style.display = "none";
                        }
                        
                    }
                    
                }
            }
            xmlhttp.open("GET", "ajex-event.php?q=addNewCard&full_name="+full_name+"&car_number="+car_number+"&month="+month+"&year="+year+"&cvv_number="+cvv_number);
            xmlhttp.send();
	    }
	</script>
	
</body>
</html>