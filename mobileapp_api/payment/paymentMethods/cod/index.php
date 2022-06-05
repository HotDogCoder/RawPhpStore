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
		            if($_GET['payment'] == 'cod')
		            {
		                
		                //modify json_array
                        $transaction_obj=array(
                                                "cod"=>"1",
                                                "payment_id"=>"0",
                                                "transaction"=> array
                            					(
                            					     "value"=>"1",
                                                    "type"=>"cod"
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