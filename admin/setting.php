<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{
    if(isset($_GET['action'])){
        
        if($_GET['action']=="deleteSlider") 
        {

            $id = htmlspecialchars($_GET['id'], ENT_QUOTES);
            
            $url=$baseurl . 'deleteAppSliderImage';
            
            $data = 
                array(
                    "id" => $id
                );
            
            $json_data=@curl_request($data,$url);
           
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=appSliders&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=appSliders&action=success'</script>";
           }

            
        }
        else
        if($_GET['action']=="addAppSliderImage") 
        {
            $user_id=$_SESSION[PRE_FIX.'id'];
            $image_base = file_get_contents($_FILES['image']['tmp_name']);
            $image = base64_encode($image_base);
            
            $url=$baseurl . 'addAppSliderImage';
            
            $data = 
                array(
                    "user_id" => $user_id, 
                    "image" => array("file_data" => $image)
                );
            
            $json_data=@curl_request($data,$url);
            
            
           // do some checking to make sure it sent
           //var_dump($json_data);
           //die;
    
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=appSliders&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=appSliders&action=success'</script>";
           }

        }
    }
    
    
        $url=$baseurl . 'showSettings';
        $data = [];
        
        $json_data=@curl_request($data,$url);
        
        $autoAssign_value=@$json_data['msg']['auto_assign_order']['value'];
        $paypal_value=@$json_data['msg']['paypal']['value'];
        $stripe_value=@$json_data['msg']['stripe']['value'];
        $cod_value=@$json_data['msg']['cod']['value'];
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title">
                                    <h2>All Settings </h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                <div id="settingStatus"></div>
                                
                                <div class="qr-row1">
                                    
                                    <style>
                                        .tabs-button.active
                                        {
                                            background: #F5F5F5 !important;
                                        }
                                        .tabs .tabs-button
                                        {
                                            cursor: pointer;
                                            background: #FBFBFB;
                                            display: inline;
                                            padding: 10px 10px;
                                            border-radius: 4px 4px 0 0;
                                            border: solid 1px #f2f2f2;
                                        }
                                        .tabs
                                        {
                                            border-bottom: solid 1px whitesmoke;
                                        }
                                        .tabSections
                                        {
                                            padding:20px 0 10px 0;
                                        }
                                        
                                        
                                        //custom radio button css
                                        .customRadioBtn .radioBtn {
                                          display: block;
                                          position: relative;
                                          padding-left: 35px;
                                          margin-bottom: 12px;
                                          cursor: pointer;
                                          font-size: 22px;
                                          -webkit-user-select: none;
                                          -moz-user-select: none;
                                          -ms-user-select: none;
                                          user-select: none;
                                        }
                                        
                                        /* Hide the browser's default radio button */
                                        .customRadioBtn .radioBtn input {
                                          position: absolute;
                                          opacity: 0;
                                          cursor: pointer;
                                        }
                                        
                                        /* Create a custom radio button */
                                        .customRadioBtn .checkmark {
                                          position: absolute;
                                          /*top: 0;*/
                                          /*left: 0;*/
                                          height: 20px;
                                          width: 20px;
                                          background-color: #eee;
                                          border-radius: 50%;
                                          margin-left: 5px;
                                        }
                                        
                                        /* On mouse-over, add a grey background color */
                                        .customRadioBtn .radioBtn:hover input ~ .checkmark {
                                          background-color: #ccc;
                                        }
                                        
                                        /* When the radio button is checked, add a blue background */
                                        .customRadioBtn .radioBtn input:checked ~ .checkmark {
                                          background-color: #E77830;
                                        }
                                        
                                        /* Create the indicator (the dot/circle - hidden when not checked) */
                                        .customRadioBtn .checkmark:after {
                                          content: "";
                                          position: absolute;
                                          display: none;
                                        }
                                        
                                        /* Show the indicator (dot/circle) when checked */
                                        .customRadioBtn .radioBtn input:checked ~ .checkmark:after {
                                          display: block;
                                        }
                                        
                                        /* Style the indicator (dot/circle) */
                                        .customRadioBtn .radioBtn .checkmark:after {
                                         	top: 6px;
                                        	left: 6px;
                                        	width: 9px;
                                        	height: 9px;
                                        	border-radius: 50%;
                                        	background: white;
                                        }
                                        
                                        
                                        .tabSections .settingName
                                        {
                                            float: left;
                                            width: 500px;
                                        }
                                        
                                        .tabSections .settingBtns 
                                        {
                                            float: right;
                                            width: 500px;
                                            padding: 15px 0;
                                            text-align: right;
                                            font-size:14px;
                                        }
                                        
                                        .tabSections .settingRow
                                        {
                                            padding: 0 40px 0 0;
                                        }
                                    </style>
                                    
                                    <div class="tabs">
                                      <button class="tabs-button basicSetting active" onclick="openCity('basicSetting')">Basic Setting</button>
                                    </div> 
                                    
                                    <div class="tabSections">
                                        <div id="basicSetting" class="contentSection">
                                            <div class="settingRow">
                                                <div class="settingName">
                                                    <h3 style="color:#666;">Order Assign</h3>
                                                    <p style="font-size: 14px;">You can change the order assign setting with Auto-assign or Manual-assign</p>
                                                </div>
                                                <div class="settingBtns customRadioBtn">
                                                    <label class="radioBtn" style="width: 120px; text-align: left;" onclick="tabSettingAutoAssign('auto_assign_order','1')">
                                                        Auto-Assign
                                                        <input type="radio" value="1" <?php if($autoAssign_value=="1"){echo "checked";} ?> name="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label class="radioBtn" style="width: 120px; text-align: left;" onclick="tabSettingAutoAssign('auto_assign_order','0')">Manual-Assign
                                                      <input type="radio" value="0" <?php if($autoAssign_value=="0"){echo "checked";} ?> name="radio">
                                                      <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <hr>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                                    <script>
                                        function openCity(contentSection) 
                                        {
                                          
                                          $(".tabs button").removeClass("active");
                                          var i;
                                          var x = document.getElementsByClassName("contentSection");
                                          for (i = 0; i < x.length; i++) {
                                            x[i].style.display = "none";
                                          }
                                          document.getElementById(contentSection).style.display = "block";
                                             
                                          $(".tabs ."+contentSection).addClass("active");
                                        }
                                        
                                       
                                        
                                        function tabSettingAutoAssign(type,value)
                        				{
                        				    
                        					
                        					document.getElementById("preloader").classList.remove("hide");
                                            
                        					var xmlhttp;
                                            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                                                xmlhttp = new XMLHttpRequest();
                                            } else {// code for IE6, IE5
                                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                            }
                                            xmlhttp.onreadystatechange = function () {
                                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                                                {
                                                    // alert(xmlhttp.responseText);
                        							document.getElementById("settingStatus").innerHTML=xmlhttp.responseText;
                        							document.getElementById("preloader").classList.add("hide");
                                                }
                                            }
                                            xmlhttp.open("GET","ajex-events.php?action=tabSettingAutoAssign&type="+type+"&value="+value);
                                            xmlhttp.send();
                        				}
                                    </script>
                                    

                                   
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
    
    <?php
    
} 
else 
{
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>