<?php 
if( isset($_SESSION[PRE_FIX.'sessionPortal']))
{       
       
        $url=$baseurl . 'showPlatformTotalEarnings';
        $data = 
            array(
                "start_date" => @$_GET['start_date'], 
                "end_date" =>  @$_GET['end_date']
            );
        
       
        $json_data=@curl_request($data,$url);
       
        $allusers = [];
        if ($json_data['code'] == 200) {
            $allusers = $json_data['msg']['RestaurantEarnings'];
            $RiderEarnings=$json_data['msg']['RiderEarnings'];
            $currency = $json_data['msg']['Currency'];
            $restaurant_earnings = $json_data['msg']['RestaurantEarnings'];
            $platform_earnings = $json_data['msg']['PlatformEarnings'];
            $yourEarning="0";

            foreach ($allusers as $single_user):
                $total_earning=$single_user['0']['total_price']-$single_user['0']['total_tax'];
                $earnedCommission=$total_earning*$single_user['Restaurant']['admin_commission']/100;
                $yourEarning=$earnedCommission+$yourEarning;
            endforeach;

            $currency_symbol = $restaurant_earnings[0]['Restaurant']['Currency']['symbol'];

            $total_orders = $platform_earnings['total_orders'];
            $total_earned = $platform_earnings['total_price'].' '.$currency_symbol;
            $your_earnings = $yourEarning.' '.$currency_symbol;
            $total_tax = $platform_earnings['total_tax'].' '.$currency_symbol;
            $total_rider_tip = $platform_earnings['total_rider_tip'].' '.$currency_symbol;
            $rider_total_earnings = $platform_earnings['rider_total_earnings'].' '.$currency_symbol;



        }else {
            $total_orders = 0;
            $total_earned = 0;
            $your_earnings = 0;
            $total_tax = 0;
            $total_rider_tip = 0;
            $rider_total_earnings = 0;

        }
        
        
        if (isset($_GET['action'])) 
        {

            if ($_GET['action'] == "paytoRestaurant") {
    
                $id = htmlspecialchars($_POST['restaurant_id'], ENT_QUOTES);
                $amount = htmlspecialchars($_POST['amount'], ENT_QUOTES);
                $pay_via = htmlspecialchars($_POST['pay_via'], ENT_QUOTES);
                $paid_date = htmlspecialchars($_POST['paid_date'], ENT_QUOTES);
                $type = htmlspecialchars($_POST['type'], ENT_QUOTES);
                $note = htmlspecialchars($_POST['note'], ENT_QUOTES);
                
                $url = $baseurl . 'addTransaction';
                
                if($type=="rider")
                {
                    $data = array(
                        "rider_user_id" => $id,
                        "amount" => $amount,
                        "pay_via" => $pay_via,
                        "paid_date" => $paid_date,
                        "type" => $type,
                        "note" => $note
                    );
                }
                else
                {
                    $data = array(
                        "restaurant_id" => $id,
                        "amount" => $amount,
                        "pay_via" => $pay_via,
                        "paid_date" => $paid_date,
                        "type" => $type,
                        "note" => $note
                    );
                }
                
               
                $json_data = @curl_request($data, $url);
    
                $json_return = $json_data['msg'];
                
                if ($json_data['code'] !== 200) {
                    echo "<script>window.location='dashboard.php?p=earning&action=error'</script>";
                } else {
                    echo "<script>window.location='dashboard.php?p=earning&action=success'</script>";
                }
    
    
            }
        }
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title half_width float_left">
                                    <h2>All Earning</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                
                                <div class="half_width float_right right page-title " style="padding: 10px 0;">
                                    
                                    <span onclick="filterEarning();" style="color:#80808080; font-size: 14px;">
                                        <span class="fa fa-filter"></span>
                                        Filter Date
                                    </span>
                                    
                                </div>
                                
                                <div class="clear"></div>
                                <br>
                                

                                
                                 
                                <div class="qr-row1">
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Total Orders
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $total_orders;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Completed orders</p>
                                        <p style="font-size:9px;" align="center">&nbsp;</p>
                                    </div>
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Total Earned
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $total_earned;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Includes everything (tax,delivery fee,tips etc)</p>
                                        <p style="font-size:9px;" align="center">&nbsp;</p>
                                    </div>
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Your Earning
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $your_earnings;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Excluding tax and according to the commission</p>
                                        <p style="font-size:9px;" align="center">&nbsp;</p>
                                    </div>
                                    
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Total Tax
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $total_tax;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Total Tax against each order</p>
                                        <p style="font-size:9px;" align="center">&nbsp;</p>
                                    </div>
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Rider Tip
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $total_rider_tip;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Total tips which customer has given in each order</p>
                                        <p style="font-size:9px;" align="center">&nbsp;</p>
                                    </div>
                                    
                                    
                                    
                                    <div class="qr-el qr-el-22" style="float: left;min-height: auto;">
                                        <h2 style="text-align: center;font-size: 30px !important; color:#E77830;">
                                            Rider Earnings
                                        </h2>
                                        <h2 style="text-align: center;font-size: 25px !important;">
                                            <?php
                                                echo  $rider_total_earnings;
                                            ?>
                                        </h2>
                                        <p style="font-size:9px;" align="center">Rider tip included</p>
                                        
                                        <a href="dashboard.php?p=earning&view=earningRider" style="color: #E77830;">
                                            <p style="font-size:9px;" align="center">View Earning</p>
                                        </a>
                                    </div>
                                    
                                    
                                    
                                   
                                    <div style="clear:both;"></div>
                                    
                                </div>
                                <!--start of datatable here-->
                                <br><br>
                                
                                <?php
                                    if(@$_GET['view']=="earningRider")
                                    {
                                        ?>
                                            <table id="table_view" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Rider Commission</th>
                                                        <th>Earned</th>
                                                        <th>Rider Tip</th>
                                                        <th>Unpaid Balance</th>
                                                        <th>Pay</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
            
                                                <?php foreach ($RiderEarnings as $single_user): ?>
                                                    <tr>
                                                        <td><?php echo $single_user['Rider']['user_id']; ?></td>
                                                        <td>
                                                            <?php echo $single_user['Rider']['first_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $single_user['Rider']['phone']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $single_user['Rider']['rider_fee'].' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                           <?php echo $single_user['Rider']['earning'].' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $single_user['Rider']['total_rider_tip'].' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                if($single_user['Rider']['unpaid']!="")
                                                                {
                                                                    echo $single_user['Rider']['unpaid'].' '.$currency['symbol'];
                                                                }
                                                                else
                                                                {
                                                                    echo "0".' '.$currency['symbol'];
                                                                }
                                                            ?>
                                                        </td>
                                                        
                                                        <td>
                                                            <?php
                                                                if($single_user['Rider']['unpaid']!="")
                                                                {
                                                                    ?>
                                                                        <span onclick="paytoRestaurant('<?php echo $single_user['Rider']['user_id']; ?>','<?php echo $single_user['Rider']['unpaid'];?>','rider')" style="background: #E77830;font-size: 11px;padding: 3px 3px;border-radius: 4px;">
                                                                            Pay Now
                                                                        </span>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    echo "Paid";
                                                                }
                                                            ?>
                                                        </td>
                                                        
                                                <?php endforeach; ?>
            
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Rider Commission</th>
                                                        <th>Earned</th>
                                                        <th>Rider Tip</th>
                                                        <th>Unpaid Balance</th>
                                                        <th>Pay</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <table id="table_view" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Total Orders</th>
                                                        <th>Total Earning</th>
                                                        <th>Total Tax</th>
                                                        <th>Admin Earned</th>
                                                        <th>Restaurant Earned</th>
                                                        <th>Unpaid Balance</th>
                                                        <th>Pay</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
            
                                                <?php foreach ($allusers as $single_user): ?>
                                                    <tr>
                                                        <td><?php echo $single_user['Restaurant']['id']; ?></td>
                                                        <td>
                                                            <?php echo $single_user['Restaurant']['name']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $single_user['0']['total_orders']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $total_earning=$single_user['0']['total_price']-$single_user['0']['total_tax'];
                                                            echo $total_earning.' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $single_user['0']['total_tax'].' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $earnedCommission=$total_earning*$single_user['Restaurant']['admin_commission']/100;
                                                                echo $earnedCommission.' '.$currency['symbol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $restaurentEarned=$total_earning-$earnedCommission;
                                                                echo $restaurentEarned.' '.$currency['symbol']; ?>
                                                        </td>
            
                                                        <?php
                                                            $yourEarning=$earnedCommission+$yourEarning;
                                                        ?>
                                                        <td>
                                                            <?php
                                                                if($single_user['0']['unpaid']!="")
                                                                {
                                                                    echo $single_user['0']['unpaid'].' '.$currency['symbol'];
                                                                }
                                                                else
                                                                {
                                                                    echo "0".' '.$currency['symbol'];
                                                                }
                                                            ?>
                                                        </td>
                                                        
                                                        <td>
                                                            <?php
                                                                if($single_user['0']['unpaid']!="")
                                                                {
                                                                    ?>
                                                                        <span onclick="paytoRestaurant('<?php echo $single_user['Restaurant']['id']; ?>','<?php echo $single_user['0']['unpaid'];?>','restaurant')" style="background: #E77830;font-size: 11px;padding: 3px 3px;border-radius: 4px;">
                                                                            Pay Now
                                                                        </span>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    echo "Paid";
                                                                }
                                                            ?>
                                                        </td>
            
                                                <?php endforeach; ?>
            
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Total Orders</th>
                                                        <th>Total Earning</th>
                                                        <th>Total Tax</th>
                                                        <th>Admin Earned</th>
                                                        <th>Restaurant Earned</th>
                                                        <th>Unpaid Balance</th>
                                                        <th>Pay</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php
                                    }
                                ?>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
    <script>
        $(document).ready(function () {
            $('#table_view').DataTable({
                    "pageLength": 15
                }
            );
            $('#table_view2').DataTable({
                    "pageLength": 35
                }
            );
        });
        
        function filterEarning()
        {
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                    
                    $( function() { 
                        $("#start_date").datepicker({dateFormat: 'yy-mm-dd'});
                        $("#end_date").datepicker({dateFormat: 'yy-mm-dd'});
                    } );
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=filter");
            xmlhttp.send();
        }
        
        function paytoRestaurant(id,payment,type)
        {
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                    
                    $( function() { 
                        $("#paid_date").datepicker({dateFormat: 'yy-mm-dd'});
                    } );
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=paytoRestaurant&id="+id+"&payment="+payment+"&type="+type);
            xmlhttp.send();
        }
        
        function editUser(id) 
        {
            
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=editUser&id="+id);
            xmlhttp.send();
        }
        
        function changePassword(id) 
        {
            
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=changePassword&id="+id);
            xmlhttp.send();
        }
        
        
    </script>
    <?php
    
} 
else 
{
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>