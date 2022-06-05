<?php if( isset($_SESSION[PRE_FIX.'restaurant_id']) && ($_SESSION[PRE_FIX.'user_type'] == "hotel" || $_SESSION[PRE_FIX.'user_type'] == "store") ){ ?>


<div class="form" style="background:#F9F9F9; padding:40px 0px;  margin-bottom:100px;">
		
        <?php
        	$user_id = $_SESSION[PRE_FIX.'restaurant_id'];
	
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);
	
			$data = array(
				"user_id" => $user_id
			);
		
			$ch = curl_init( $baseurl.'/showEarnings' );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$return = curl_exec($ch);
	
			$json_data = json_decode($return, true);
	
			//var_dump($return);
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//echo $json_data['code'];
			//die;
	
			if($json_data['code'] == "201" || $json_data['code'] == "401")
			{
	
				?>
                    <div class="textcenter nothingelse">
                        <img src="img/nomenu.png" style="display: inline-block;" alt="" />
                        <h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
                    </div>
                <?php
                die();
	
			} 
			else 
			{
				
				$currency=$json_data['msg']['Currency']['symbol'];
			}
		?>
        
		<div class="col33 left" align="center" style="border-right: solid 1px #e7e7e7;">
			<h2 class="title" style="font-size:28px;">
				<span>Total Orders</span>
			</h2>
			<h2 style="font-size:15px; color:#E86942; margin:20px ;"><?php echo $json_data['msg']['TotalEarnings']['total_orders']; ?></h2>
		</div>
		
		<div class="col33 left" align="center" style="border-right: solid 1px #e7e7e7;">
			<h2 class="title" style="font-size:28px;">
				<span>Total Earning</span>
			</h2>
			<h2 style="font-size:15px; color:#E86942; margin:20px ;"><?php echo $json_data['msg']['TotalEarnings']['total_price'].' '; echo $currency;  ?></h2>
		</div>
		
		
		<div class="col33 left" align="center">
			<h2 class="title" style="font-size:28px;">
				<span>Your Earned</span>
			</h2>
			<h2 style="font-size:15px; color:#E86942; margin:20px ;"><?php echo $json_data['msg']['TotalEarnings']['you_earned'].' '; echo $currency; ?></h2>
		</div>
		
		<div class="clear"></div>
		
        <br>
        <div style="padding:20px 0px; border-top:20px solid #f2f2f2">
        	<div style="padding:0px 20px;">
                <div class="left">
                    <h2 class="title">Transaction History</h2>
                </div>
                <div class="right">
                    <a href="javascript:;" class="filtericon"></a>
                </div>
                <div class="clear"></div>
            </div>
            
            
             <style>
			 	.order_table td {
					padding: 18px 25px !important;
					font-weight: 300;
					border-bottom: 3px solid #f2f2f2;
				}
			 </style>
        	 <table class="order_table" id="myTable" cellspacing="0" cellpadding="0" border="0">
                <thead>
                    <tr>
                        <td style="font-weight: bold;"><strong>ID</strong></td>
                        <td style="font-weight: bold;"><strong>Amount</strong></td>
                        <td style="font-weight: bold;"><strong>Paid Date</strong></td>
                        <td style="font-weight: bold;"><strong>Paid Via</strong></td>
                    </tr>
                </thead>
                    <tbody id="myTable_row">
                        <?php
                        	foreach ($json_data['msg']['Transactions'] as $key => $value) 
							{

							    //var_dump($value);
								
								?>
									<tr style="display: table-row;">
                                        <td><?php echo $value['Transaction']['id']; ?></td>
                                        <td><?php echo $value['Transaction']['amount'].' '.$currency; ?></td>
                                        <td><?php echo $value['Transaction']['paid_date']; ?></td>
                                        <td><?php echo $value['Transaction']['pay_via']; ?></td>
                                    </tr>
								<?php
							
							}
						?>
                        
                        
                        
                        
                   </tbody>
            </table>
        </div>
        
        
		<script src="asset-js/jquery-2.js" type="text/javascript"></script>
		
		<!--<script type="text/javascript" src="asset-js/jquery_002.js"></script>-->
		<!--<script type="text/javascript" src="asset-js/canvasjs-charts.js"></script>-->
		<!--<script type="text/javascript" src="asset-js/performance-earnings.js"></script>-->
		<!--<script type="text/javascript" src="asset-js/mustache.js"></script>-->
		
		<!--<script type="text/javascript">-->
			
		<!--			var revenueChartParams = {-->
		<!--				chartContainerSelector: "#revenue",-->
		<!--				chartCallOutSelector: "#revenue-chart-call-out"-->
		<!--			};-->
	
		<!--			window.foodomia.PerformanceEarnings.renderRevenueChart(	-->
		<!--				revenueChartParams, -->
		<!--				[	-->
		<!--					{"y": <?php echo $weekly_earning; ?>, "label": "<?php echo $weekly_earning_week_start; ?>"}-->
							
		<!--				]);-->
	
					
		<!--</script>-->
</div>









<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>