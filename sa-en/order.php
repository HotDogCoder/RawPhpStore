<?php if (isset($_SESSION['id']) && $_SESSION['user_type'] == "user") 
{ 
?>

    <?php
    if (isset($_GET['detail'])) 
    {
        ?>
            
        <?php
        $order_id = $_GET['detail'];
        $user_id = $_SESSION['id'];

        $endpoint = "/showOrderDetail";
        $data = array(
            "order_id" => $order_id,
            "user_id" => $user_id
        );

        $json_data = curl_request($data, $endpoint, $baseurl);
        if ($json_data['code'] !== 200) {
            echo "<div class='alert alert-danger'>Error in fetching order details, try again later..</div>";
        } 
        else 
        {
            foreach ($json_data['msg'] as $str => $val) 
            {
                ?>
                <h2 class="title">Order No# <?php echo $val['Order']['order_number']; ?></h2>
                
                    <div class="orderinformation">
                        <div class="sect">
                            <h3>Buyer Details</h3>
                            <p>
                                <i class="fa fa-user"></i>
                                <?php echo $val['UserInfo']['first_name'] . " " . $val['UserInfo']['last_name']; ?>
                            </p>
                            <p><i class="fa fa-phone"></i>
                                <?php echo $val['UserInfo']['phone']; ?>
                            </p>
                            <p>
                                <i class="fa fa-map-marker"></i>
                                <?php echo $val['Address']['street'] . " " . $val['Address']['city'] . ", " . $val['Address']['country']; ?>
                            </p>
                        </div>
                        
                        <?php
                            if (isset($val['RiderOrder']['Rider']) != "") 
                            {
                                ?>
                                    <div class="sect">
                                        <h3>Rider Details</h3>
                                        <p>
                                            <i class="fa fa-user"></i>
                                            <?php echo $val['RiderOrder']['Rider']['first_name'] . " " . $val['RiderOrder']['Rider']['last_name']; ?>
                                        </p>
                                        <p><i class="fa fa-phone"></i>
                                            <?php echo $val['RiderOrder']['Rider']['phone']; ?>
                                        </p>
                                    </div>
                                <?php
                            }
                        ?>

                        <div class="sect">
                            <h3>Restaurant Details</h3>
                            <p><i class="fa fa-building"></i>
                                <?php echo $val['Restaurant']['name']; ?>
                            </p>
                            <p><i class="fa fa-map-marker"></i>
                                <?php
                                    $stret = $val['Restaurant']['RestaurantLocation']['street'];
                                    if (!empty($stret)) {
                                        echo $stret;
                                    }
                                    $city = $val['Restaurant']['RestaurantLocation']['city'];
                                    if (!empty($city)) {
                                        echo $city.", ";
                                    }
                                    $country = $val['Restaurant']['RestaurantLocation']['country'];
                                    if (!empty($country)) {
                                        echo $country;
                                    }
                                ?>
                            </p>
                        </div>
                        
                        <?php
                            if($val['Order']['instructions']!="")
                            {
                                ?>
                                    <div class="sect">
                                        <h3>Instructions</h3>
                                        <p><i class="fa fa-exclamation-circle"></i>
                                            <?php echo $val['Order']['instructions']; ?>
                                        </p>
                                    </div>
                                <?php
                            }
                        ?>
                        
                        <?php
                            if($val['Order']['available_status'] ==1)
                            {
                        ?>

                        <div class="sect">
                            <h3>Booking Delivery Details</h3>
                            <p><i class="fa fa-calendar-o"></i>
                                <?php echo date('d-m-Y', strtotime($val['Order']['booking_date'])); ?>
                            </p>
                            <p><i class="fa fa-calendar"></i>
                                <?php echo strtoupper($val['Order']['booking_day']); ?>
                            </p>
                            <p><i class="fa fa-clock-o"></i>
                                 <?php echo $val['Order']['booking_day_time']; ?>
                            </p>
                        </div>
                        <?php
                            }
                        ?>
                        
                        <div class="sect">
                            <div class="menutable_div">
                                <table width="100%" class="menutable orderDetail" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <h4>Menu Item</h4></td>
                                        <td width="100" class="textcenter">
                                            <h4>Qty.</h4></td>
                                        <td width="100" class="textcenter">
                                            <h4>Price</h4></td>
                                    </tr>
                                    <?php
                                        foreach ($val['OrderMenuItem'] as $key => $value) 
                                        {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <p><strong><?php echo $value['name']; ?></strong></p>
                                                    </td>
                                                    <td class="textcenter">
                                                        <?php echo $value['quantity']; ?>
                                                    </td>
                                                    <td class="textcenter">
                                                        <?php 
                                                            echo $value['price'].' '; 
                                                            echo $val['Restaurant']['Currency']['symbol'];
                                                            
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            if (count($value['OrderMenuExtraItem']) > 0) 
                                            {
                                                foreach ($value['OrderMenuExtraItem'] as $key11 => $value11) 
                                                {
                                                    echo "<tr><td class='extraMenuItem' colspan='3'><span>" . $value['quantity']; ?> x
                                                    <?php echo $value11['name']; ?> &nbsp;&nbsp; + <?php echo $value11['price'].' '; echo $val['Restaurant']['Currency']['symbol']
                                                    . "</span></td></tr>";
                                                }
                                            }
        
                                        }
                                    ?>
                                </table>
                            </div>

                            <hr>
                            <table width="100%" class="menutable_div" cellpadding="0" cellspacing="0">
                                
                                <tr>
                                    <td class="width100"><strong>Sub Total</strong></td>
                                    <td class="textcenter">
                                        <strong>
                                            <?php
                                            echo $val['Order']['sub_total'].' ';
                                                echo $val['Restaurant']['Currency']['symbol'];
                                                
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                
                                <tr style="display: none">
                                    <td class="width100"><strong>Rider Tip</strong></td>
                                    <td class="textcenter">
                                        <strong>
                                            <?php
                                            echo $val['Order']['rider_tip'].' ';
                                                echo $val['Restaurant']['Currency']['symbol'];
                                                
                                                ?>
                                        </strong>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="width100"><strong>Delivery Fee</strong></td>
                                    <td class="textcenter">
                                        <strong>
                                            <?php
                                            echo $val['Order']['delivery_fee'].' ';
                                                echo $val['Restaurant']['Currency']['symbol'];
                                                
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="width100"><strong>Tax</strong></td>
                                    <td class="textcenter">
                                        <strong>
                                            <?php
                                            echo $val['Order']['tax'].' ';
                                            echo $val['Restaurant']['Currency']['symbol'];
                                            
                                            ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width100"><strong>Total (<?php echo $val['Restaurant']['Currency']['symbol']; ?>)</strong></td>
                                    <td class="textcenter">
                                        <strong>
                                            <?php
                                            echo $val['Order']['price'].' ';
                                            echo $val['Restaurant']['Currency']['symbol'];
                                            
                                            ?>
                                        </strong>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        
                       
                    </div>
                <?php
            }
        }
    } 
    else 
    { 
    ?>

        <h2 class="title">My Orders</h2>
        <?php

            $user_id = $_SESSION['id'];
    
            $data = array(
                "user_id" => $user_id
            );
    
            $ch = curl_init($baseurl . '/showUserOrders');
    
            $endpoint = "/showUserOrders";
            $data = array(
    
                "user_id" => $user_id
            );
    
            $json_data = curl_request($data, $endpoint, $baseurl);

            if ($json_data['code'] !== 200) {
            ?>
                <div class="textcenter nothingelse">
                    <img src="assets/img/noorder.png" alt="" />
                    <h3>Whoops!</h3>
                </div>
            <?php

        } 
        else 
        {
            ?>

                            <?php $rows = count($json_data['msg']);
            if ($rows == 0) {
                ?>
                    <div class="textcenter nothingelse">
                        <img src="assets/img/noorder.png" alt="" />
                        <h3>Whoops!</h3>
                    </div>
                <?php
            }
            echo "<table class='order_table' border='0' cellpadding='0' cellspacing='0' id='myTable'>
			<thead></thead>
			<tbody id='myTable_row'>";
            foreach ($json_data['msg'] as $str => $val) 
            {
                
                ?>
                    <tr>
                        <td>
                            <?php echo "#" . $val['Order']['order_number']; ?>
                            <!-- <?php echo "#" . $val['Order']['id']; ?> -->
                        </td>
                        <td class="restaurant_name">
                            <?php
                                echo $val['Restaurant']['name'] . "<br><span class='order_status'> " . $val['Order']['price'].' '. $val['Restaurant']['Currency']['symbol'] .  "</span>";
                            ?>
                            <br>
                            <span class="order_status">Status:</span>
                            <span>
    							<?php
                                    if ($val['Order']['status'] == "2") 
                                    {
                                        echo "Order Completed";
                                    }  
                                    else if ($val['Order']['status'] == 4) 
                                    {
                                        echo "<span>Order Cancelled</span>";
                                    } 
                                    else
                                    {
                                        if ($val['Order']['hotel_accepted'] == "1") 
                                        {
                                            echo "Order has been accepted";
                                        } 
                                        else 
                                        {
                                            echo "Processing...";
                                            ?>             <a href="javascript:" onClick="jQuery('#reg<?php echo $val['Order']['id'];?>').toggle();" style="color: red;">Click to Cancel</a>
 
                                            <div id="reg<?php echo $val['Order']['id'];?>" class="popup">
                                                <div class="popup_container col40"> <a href="javascript:;" onClick="jQuery('#reg<?php echo $val['Order']['id'];?>').hide();" id="close">&times;</a>
                                                <div class="paddingallsides form" style="padding: 20px;">
                                                    <h2 class="title" style="text-align:center">Cancel Reason</h2>
                                                    <form action="dashboard.php?p=hotel_order&detail=<?php echo $_GET['orderID']; ?>&order=reject" id="hoteldealsfrmd" method="post">
                                                    <p>
                                                        <textarea class="textarea" style="font-size: 16px;" name="reason" id="dsc<?php echo $val['Order']['id'];?>" placeholder="description" value=""></textarea>
                                                    </p>
                                                    <input type="hidden" value="<?php echo $_SESSION['id'];?>" id="user_id<?php echo $val['Order']['id'];?>">
                                                    
                                                    <p>
                                                        <input type="button" style="background: #E86942;color: #fff;cursor: pointer;border: none;padding: 10px;font-size: 18px;" onclick="order_cancel(<?php echo $val['Order']['id'];?>);" value="Order Cancel" name="">
                                                    </p>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
    						</span>
                            <br>
                        </td>
                        <td class="textright dateAddress">
                            <?php echo convertintotime($val['Order']['created']) . "<span class='blok'>" . $val['Address']['street'] . " " . $val['Address']['city'] . ", " . $val['Address']['country'] . "</span>"; ?>
                        </td>
                        <td>
                            <a href="dashboard.php?p=order&detail=<?php echo $val['Order']['id']; ?>">
                                <button>Order Details</button>
                            </a>
                        </td>
                    </tr>
            <?php
            }
            echo "</tbody></table> <nav><ul class='pagination pagination-sm' id='myPager'></ul></nav>";
            
        }

        
    }
} 
else 
{
    echo "<script>window.location='index.php'</script>";
    die;
} 
?>