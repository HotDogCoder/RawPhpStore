<div class="qr-sidebar">
    <div class="qr-sidebar-title-area">
        <div class="logo-area">
            <div class="qr-logo">
                <a href="#"> <img src="frontend_public/uploads/attachment/logo.png" alt=""> </a>
            </div>
        </div>
        <div class="burger-icon"> ☰</div>
    </div>
<!--  THIS WRAPPER NEEDS TO EXIST IN ORDER TO SEND THE MARKUP TO GOOGLE -->
    <div id="google_translate_element" style="position: absolute; top: 25px; right: 35px;"></div>

    <?php 
        if (@$_SESSION[PRE_FIX.'role'] == "0") 
        { 
        
            
            $url=$baseurl . 'showTablesCount';
            $data =array();
            $json_data=@curl_request($data,$url);
            
            $currency_count=$json_data['msg']['currency_count'];
            $app_sliders_count=$json_data['msg']['app_sliders_count'];
            $web_sliders_count=$json_data['msg']['web_sliders_count'];
            $taxes_count=$json_data['msg']['taxes_count'];
            $restaurant_count=$json_data['msg']['restaurant_count'];
            $stores_count=$json_data['msg']['stores_count'];
            $user_admin_count=$json_data['msg']['user_admin_count'];
            $user_count=$json_data['msg']['user_count'];
            $rider_count=$json_data['msg']['rider_count'];
            $rider_request_count=$json_data['msg']['rider_request_count'];
            $restaurant_request_count=$json_data['msg']['restaurant_request_count'];
            $transaction_count=$json_data['msg']['transaction_count'];
            $category_count=$json_data['msg']['category_count'];
    ?>
        <div class="not-mobile">
            <ul>
                
                <li>
                    <a href="dashboard.php?p=users" class="<?php if (strpos($_SERVER['REQUEST_URI'], "users") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-users"></i> Users
                        
                    </a>
                    <span class='menuCount'><?php echo $user_count; ?></span>
                </li>
                <li>
                    <a href="dashboard.php?p=restaurants" class="<?php if (strpos($_SERVER['REQUEST_URI'], "Restaurants") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-utensils"></i> Restaurants
                        
                    </a>
                    <span class='menuCount'><?php echo $restaurant_count; ?></span>
                </li>
                
              
                <li>
                    <a href="dashboard.php?p=booking_time" class="<?php if (strpos($_SERVER['REQUEST_URI'], "booking_time") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-clock-o"></i> Booking Time
                    </a>
                </li>
                
                
                <li>
                    <a href="dashboard.php?p=stores" class="<?php if (strpos($_SERVER['REQUEST_URI'], "stores") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-store"></i> Stores
                        
                    </a>
                    <span class='menuCount'><?php echo $stores_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=manageOrders" class="<?php if (strpos($_SERVER['REQUEST_URI'], "manageOrders") !== false) { echo "router-link-active "; } ?>"> 
                        <i aria-hidden="true" class="fa fa-stream"></i> Manage Orders
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=rider" class="<?php if (strpos($_SERVER['REQUEST_URI'], "rider") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-biking"></i> Riders
                        
                    </a>
                    <span class='menuCount'><?php echo $rider_count; ?></span>
                </li>
                <li>
                    <a href="dashboard.php?p=inbox" class="<?php if (strpos($_SERVER['REQUEST_URI'], "inbox") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-inbox"></i> Inbox
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=appSliders" class="<?php if (strpos($_SERVER['REQUEST_URI'], "appSliders") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-image"></i> App Sliders
                        
                    </a>
                    <span class='menuCount'><?php echo $app_sliders_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=webSliders" class="<?php if (strpos($_SERVER['REQUEST_URI'], "webSliders") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-image"></i> Web Sliders
                        
                    </a>
                    <span class='menuCount'><?php echo $web_sliders_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=taxSetting" class="<?php if (strpos($_SERVER['REQUEST_URI'], "taxSetting") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-hand-holding-usd"></i> Tax Setting
                        
                    </a>
                    <span class='menuCount'><?php echo $taxes_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=manageCurrency" class="<?php if (strpos($_SERVER['REQUEST_URI'], "manageCurrency") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-dollar-sign"></i> Manage Currency
                        
                    </a>
                    <span class='menuCount'><?php echo $currency_count; ?></span>
                </li>

                <!--<li>-->
                <!--    <a href="dashboard.php?p=manageCategory" class="<?php if (strpos($_SERVER['REQUEST_URI'], "manageCategory") !== false) { echo "router-link-active ";} ?>"> -->
                <!--        <i aria-hidden="true" class="fa fa-dollar-sign"></i> Manage Category-->
                        
                <!--    </a>-->
                <!--    <span class='menuCount'><?php echo $category_count; ?></span>-->
                <!--</li>-->
               
                <li>
                    <a href="dashboard.php?p=manageCategoryNew" class="<?php if (strpos($_SERVER['REQUEST_URI'], "manageCategoryNew") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-dollar-sign"></i> Manage Category
                        
                    </a>
                    <span class='menuCount'><?php echo $category_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=adminUsers" class="<?php if (strpos($_SERVER['REQUEST_URI'], "adminUsers") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-users"></i>
                        Admin Users
                    </a>
                    <span class='menuCount'><?php echo $user_admin_count; ?></span>
                </li>
                <li>
                    <a href="dashboard.php?p=riderRequest" class="<?php if (strpos($_SERVER['REQUEST_URI'], "riderRequest") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-file-alt"></i>
                        Rider Request
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=restaurantRequest" class="<?php if (strpos($_SERVER['REQUEST_URI'], "restaurantRequest") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-file-alt"></i>
                        Restaurant Request
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=pushNotification" class="<?php if (strpos($_SERVER['REQUEST_URI'], "pushNotification") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-bell"></i>
                        Push Notification
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=earning" class="<?php if (strpos($_SERVER['REQUEST_URI'], "earning") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-dollar"></i>
                        Earning
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=transactions" class="<?php if (strpos($_SERVER['REQUEST_URI'], "transactions") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-file-invoice"></i>
                        Transactions
                    </a>
                    <span class='menuCount'><?php echo $transaction_count; ?></span>
                </li>
                
                <li>
                    <a href="dashboard.php?p=setting" class="<?php if (strpos($_SERVER['REQUEST_URI'], "setting") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fas fa-cog"></i>
                        Setting
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?p=changePassword" class="<?php if (strpos($_SERVER['REQUEST_URI'], "changepassword") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="fa fa-unlock-alt"></i>
                        Change Password
                    </a>
                </li>
                
                <li>
                    <a href="dashboard.php?p=logout" class="<?php if (strpos($_SERVER['REQUEST_URI'], "logout") !== false) { echo "router-link-active ";} ?>"> 
                        <i aria-hidden="true" class="right-align fa fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                
            </ul>
            <div class='clear'></div>
        </div>
        <?php } ?>
                <div class="mobile-only"></div>
</div>