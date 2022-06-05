<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{       
        if(isset($_GET['action']))
        {
            
            
            if($_GET['action']=="acceptRestaurant") 
            {
    
                $user_id = htmlspecialchars($_GET['user_id'], ENT_QUOTES);
                
                $url=$baseurl . 'userBlockStatus';
                
                $data = 
                    array(
                        "user_id" => $user_id, 
                        "active" => "1"
                    );
                
                $json_data=@curl_request($data,$url);
               
               if($json_data['code'] !== 200)
               {
                    echo "<script>window.location='dashboard.php?p=restaurantRequest&action=error'</script>";
               }
               else
               {
                    echo "<script>window.location='dashboard.php?p=restaurantRequest&action=success'</script>";
               }
    
                
            }
            else
            if($_GET['action']=="rejectRestaurant") 
            {
    
                $user_id = htmlspecialchars($_GET['user_id'], ENT_QUOTES);
                
                $url=$baseurl . 'userBlockStatus';
                
                $data = 
                    array(
                        "user_id" => $user_id, 
                        "active" => "2"
                    );
                
                $json_data=@curl_request($data,$url);
               
               if($json_data['code'] !== 200)
               {
                    echo "<script>window.location='dashboard.php?p=restaurantRequest&action=error'</script>";
               }
               else
               {
                    echo "<script>window.location='dashboard.php?p=restaurantRequest&action=success'</script>";
               }
    
                
            }
            
        }
        
        $url=$baseurl . 'showNonActiveRestaurants';
        $data = [];
        
        $json_data=@curl_request($data,$url);
        
        
        
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title">
                                    <h2>All Restaurant Requests</h2>
                                    <div class="head-area">
                                    </div>
                                </div>

                                <!--start of datatable here-->


                                 <table id="table_view" class="display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Owner Name</th>
                                        <th>Register Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
        
                                    <?php 
                                    
                                    if($json_data['code']=="200")
                                    {
                                        foreach ($json_data['msg'] as $single_user): 
                                    
                                        ?>
            
            
                                            <tr>
                                                <td>
                                                    <?php echo $single_user['Restaurant']['id']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(!isset($single_user['Currency']['id'])){
                                                                echo "<span class='fa fa-exclamation-circle redLink' title='Currency information is missing or deleted'></span>";
            
                                                        }
                                                        else
                                                            if(!isset($single_user['Tax']['id'])) {
            
                                                                    echo "<span class='fa fa-exclamation-circle redLink' title='Tax information is missing or deleted'></span>";
            
                                                            }
                                                    ?>
                                                    <?php echo $single_user['Restaurant']['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $single_user['User']['email']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $single_user['Restaurant']['phone']; ?>
                                                </td>
                                                <td><?php echo $single_user['UserInfo']['first_name'] . " " . $single_user['UserInfo']['last_name']; ?></td>
                                                <td>
                                                    <?php echo $single_user['Restaurant']['created']; ?>
                                                </td>
                                                    
            
                                                <td>
                                                    <div class="more">
                                                        <button id="more-btn" class="more-btn">
                                                            <span class="more-dot"></span>
                                                            <span class="more-dot"></span>
                                                            <span class="more-dot"></span>
                                                        </button>
                                                        <div class="more-menu">
                                                            <div class="more-menu-caret">
                                                                <div class="more-menu-caret-outer"></div>
                                                                <div class="more-menu-caret-inner"></div>
                                                            </div>
                                                            <ul class="more-menu-items" tabindex="-1" role="menu" aria-labelledby="more-btn" aria-hidden="true">
                                                                <a href="manage_menu.php?resid=<?php echo $single_user['Restaurant']['id']; ?>&userid=<?php echo $single_user['Restaurant']['user_id']; ?>"
                                                                   target="_blank">
                                                                    <li class="more-menu-item" role="presentation">
                                                                        <button type="button" class="more-menu-btn" role="menuitem">
                                                                            Menu Item
                                                                        </button>
                                                                    </li>
                                                                </a>
                                                               
                                                               <a href="?p=editResturent&id=<?php echo $single_user['Restaurant']['id']; ?>">
                                                                    <li class="more-menu-item" role="presentation">
                                                                        <button type="button" class="more-menu-btn" role="menuitem">
                                                                            Edit
                                                                        </button>
                                                                    </li>
                                                                </a>
                                                                
                                                                <li class="more-menu-item" role="presentation">
                                                                    <a href="dashboard.php?p=restaurantRequest&action=acceptRestaurant&user_id=<?php echo $single_user['User']['id']; ?>">
                                                                        <button type="button" class="more-menu-btn" role="menuitem">Accept</button>
                                                                    </a> 
                                                                </li>
                                                                
                                                                <li class="more-menu-item" role="presentation">
                                                                    <a href="dashboard.php?p=restaurantRequest&action=rejectRestaurant&user_id=<?php echo $single_user['User']['id']; ?>">
                                                                        <button type="button" class="more-menu-btn" role="menuitem">Reject</button>
                                                                    </a>    
                                                                </li>
                                                                
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
            
            
                                            </tr>
            
                                        <?php 
                                        endforeach; 
                                    }
                                    
                                    ?>
        
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Owner Name</th>
                                        <th>Register Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
    <script>
        $(document).ready(function () {
            $('#table_view').DataTable({
                    "pageLength": 100
                }
            );
            $('#table_view2').DataTable({
                    "pageLength": 35
                }
            );
        });
        
        
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
        
        function addRider() 
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
            xmlhttp.open("GET", "ajex-events.php?action=addRider");
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