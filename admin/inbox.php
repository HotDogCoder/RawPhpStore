<script src="https://www.gstatic.com/firebasejs/5.9.4/firebase.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "<?php echo apiKey;?>",
        authDomain: "<?php echo authDomain;?>",
        databaseURL: "<?php echo databaseURL;?>",
        projectId: "<?php echo projectId;?>",
        storageBucket: "<?php echo storageBucket;?>",
        messagingSenderId: "<?php echo messagingSenderId;?>",
        appId: "<?php echo appId;?>"
    };
    firebase.initializeApp(config);
    
    print_r(config);
</script>


<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{
        $timestampString=date("d-m-Y H:i:s")."+0500";
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title">
                                    <h2>Inbox</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                
                                <div class="qr-row1">
                                    
                                    <div class="qr-el qr-el-0" style="float: left; height:440px;">
                                        <div id='left_inbox'><p style='text-align:center;'>Loading...</p></div>
                                    </div>
                                    <div class="qr-el qr-el-4" style="float: left;">
                                        
                                        <div class="messages" id="messages" style="height: 350px;overflow: scroll;border: solid 1px #edeaea;">
					    					<ul id="msgview">
                        						<div align="center" style="margin-top:30px;"><img src="frontend_public/uploads/emptyinboxchat.jpg" style="width: 500px;"></div>
                        					</ul>
                        					
                        				</div>
                        				<div id="inbox_input_area" style="padding: 5px 0 5px 0; display:none;">
                        			        <input placeholder="Write your message..." id="msg" type="text" style="width: 635px;  border-radius: 25px; color: #555; box-shadow: inset 0 1px 1px rgba(0,0,0,0.0); padding: 0 10px; height: 43px;border: solid 1px #edeaea;font-size: 13px;background: #f9f9f9;">
				                    	    <button class="submit" id="sendmsg" style="padding: 10px 10px 10px 9px;position: absolute;border: 0px;outline: 0px;background:#C3242E;color: white;border-radius: 25px;margin-left: -72px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button> 
                        				</div>
                        				<input type="hidden" id='timee' value='<?php echo $timestampString; ?>'>
                        				<input type="hidden" id='my_name'>
                        				<input type="hidden" id='chatNode'>
                        				
                                    </div>
                                    <div class='clear'></div>
                                
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
        </div>
        
        
        
        <script>
            
            $(document).ready(function() {
    			
    			var list = firebase.database().ref('Chat/');
    			
    			//list.off();
    			list.on('child_added', getData, errData);
    			$("#left_inbox").html("");
    			
    			function getData (data) 
    			{   
    				
    			    console.log(data.key); 
    			    var splitData = data.key.split("-");
            
                    var rider_id=splitData[0];
                    var admin_id=splitData[1];
                    var refID=rider_id+"_"+admin_id
                    
    			    
    			    $("#left_inbox").append("<div id='chat_"+refID+"' onclick=loadChat('"+data.key+"') style='border-bottom:solid 1px #ededed;padding: 6px 0 6px 0;cursor: pointer;'><div style='width:55px;float: left;text-align: center;'><img src='frontend_public/assets-minified/images/user.jpg' style='width: 50px;height: 50px;border-radius: 30px;border: solid 1px #f6f4f4;'></div><div style='width:65%;float: left;padding:7px 0 0 7px;'><div style='font-size: 14px;' id='rider_name'>Rider One</div><p id='last_msg_p' style='font-size: 12px;font-style: italic;color: #c3c3c3;'></p></div><div class='clear'></div></div>");
    				//console.log("div append"); 
    			    
    			    //get last msg 
        			    var list_last_msg = firebase.database().ref('Chat/'+data.key);
        			    list_last_msg.orderByKey().limitToLast(1).on('child_added', last_msg, last_msg_errData);
        			    
        			    function last_msg (last_msg) 
            			{   
            			    var messages=last_msg.val().text;
            			    //console.log(messages); 
            			    $("#chat_"+refID+" #last_msg_p").html(messages);
            			}
            			
            			function last_msg_errData() {
            				alert('Errors');
            			}
        			//get last msg end
        			
        			//get rider name 
        			    var list_rider_name = firebase.database().ref('Chat/'+data.key);
        			    list_rider_name.orderByKey().limitToFirst(1).on('child_added', rider_name, rider_name_errData);
        			    
        			    function rider_name (last_msg) 
            			{   
            			    var sender_name=last_msg.val().sender_name;
            			    //console.log(sender_name); 
            			    $("#chat_"+refID+" #rider_name").html(sender_name);
            			}
            			
            			function rider_name_errData() {
            				alert('Errors');
            			}
        			//get rider name end
    			    
    			}
    			
    			function errData() {
    				alert('Errors');
    			}
    			
    			
        		
        			
    
    		});
    		
    		function loadChat(node)
    		{   
    		    //alert(node);
    		    $("#msgview").html("");
    		    var list = firebase.database().ref('Chat/'+node);
    			//list.off();
    			list.on('child_added', getData, errData);
    			
    			
    			function getData (data) 
    			{   
    			   
    			    var chatSnap=data.key; // last message of each user
    			    var messages=data.val().text; // last message of each user
    			    var sender=data.val().sender_id; // last message of each user
    				var sender_name=data.val().sender_name; // last message of each user
    				var receiver_id=data.val().receiver_id; // last message of each user
    				
    				document.getElementById("inbox_input_area").style.display = "block";
    				$( "#inbox_input_area input" ).focus();
    				
    				$("#chatNode").val(node);
    			    $("#my_name").val('admin');
    			    
    			    if(receiver_id == "1")
					{
						var className="sent";
					}
					else
					{
						var className="replies";
					}
					
					
    			    $("#msgview").append("<li class="+className+"><p>"+messages+"</p></li>");
    				//console.log(messages);
    				
    				$('.messages').scrollTop(100000);
    			}
    			
    			function errData() {
    				alert('Errors');
    				
    			}
    		}
    		
    		
    		$(window).on('keydown', function(e) {
    		  if (e.which == 13) {
    			sendMsgModule();
    			return false;
    		  }
    		});	
    			
    		$(document).on('click', '#sendmsg', function() {
    			sendMsgModule();
    			$( ".message-input #msg" ).focus();
    		});
    		
    		
    		
    		function sendMsgModule()
    		{
    		     //alert('triger');
    		     var chatNode=document.getElementById("chatNode").value;
    			 var message=document.getElementById("msg").value;
    			 var my_name=document.getElementById("my_name").value;
    			 var timee=document.getElementById("timee").value;
    			 
    			 
    			 if(timee && chatNode && message && my_name)
    		     {
    		        var splitData = chatNode.split("-");
                
                     
                     var rider_id=splitData[0];
                     var my_id=splitData[1];
                     
                     //var chatNode1=rider_id+'-'+my_id;
                     
                     var list = firebase.database().ref('Chat/'+chatNode);
        			 var snapID=list.push().getKey();
        			 var list = firebase.database().ref('Chat/'+chatNode+'/'+snapID);
        			 var obj = {
        				 receiver_id: rider_id,
                         sender_id: my_id,
                         sender_name: my_name,
                         text: message,
                         timestamp: timee,
                     };
        			 
        			 list.update(obj);
        			 
        			
        			 document.getElementById("msg").value="";
        			 
    		     }
    		     else
    		     {
    		         alert("something went wrong");
    		     }
    			 	
    		}
        </script>
        <style>
            .messages ul li {
                display: inline-block;
                clear: both;
                float: left;
                margin: 5px 0 10px 12px;
                width: calc(100% - 20px);
                font-size: 0.9em;
            }
    
            .messages ul li.replies img {
                margin: 11px 8px 0 0;
            }
    
            .messages ul li.sent img {
                float: right;
                margin: 6px 0 0 8px;
            }
    
            .messages ul li.sent p {
                background:#C3242E;
                float: right;
                color: white;
            }
    
            .messages ul li.replies p {
                background: #f5f5f5;
                color: black;
            }
    
            .messages ul li img {
                width: 22px;
                border-radius: 50%;
                float: left;
            }
    
            .messages ul li p {
                display: inline-block;
                padding: 10px 15px;
                border-radius: 20px;
                max-width: 300px;
                line-height: 130%;
                font-size: 14px;
                word-break: break-all;
            }
            .tabbing .tabcontent {
    
                height: unset !important;
    
            }
            .tabbing .tabcontent {
                height: unset !important;
                overflow: hidden;
            }
    
            .rightmoview.fas.fa-plus {
                float: right;
                display: block;
                width: 150px;
                background: linear-gradient(to right, #ff5e62, #ff9966);
                color: #fff !important;
                padding: 11px;
                text-align: center;
                margin: 0;
                margin-top: 10px;
                margin-bottom: 10px;
                cursor: pointer;
            }
            .redbtun {
                background: linear-gradient(to right, #ff5e62, #ff9966);
                color: white !important;
                border: none;
                cursor: pointer;
                padding: 10px;
                float: right;
                width: 150px;
                margin: 10px 0px;
                font-size: 12px !important;
            }
            .form.text-left {
                text-align: left;
            }
            .radions {
                width: 150px;
            }
            .wdth {
                height: auto !important;
            }
            #myTable_row tr {
                line-height: normal !important;
            }
           .newtablesing tr {
                line-height: unset !important;
            }
            .selecta {
                font-weight: 400;
                font-size: 12px;
                width: 100%;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 3px;
                color: #555;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            }
            .centeradjsut div {
             display: inline-block;
            }
            .centeradjsut {
        
                float: left;
                max-width: 253px;
                width:100%;
                text-align: center;
                margin: 0 auto;
            }
            .adjsutwidth {
                max-width: 48px;
                width: 100%;
            }
            .supericon {
    			max-width: 27px;
    			vertical-align: middle;
    			border: -14px;
    			position: relative;
    			display: inline;
    			margin-bottom: -11px;
    			bottom: 6px;
    		}
        </style>
    <?php
    
} 
else 
{
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>