<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{       
        if(isset($_GET['action'])){
            
            if($_GET['action']=="addBookingTime") 
            {
    
                $booking_time = htmlspecialchars($_POST['booking_time'], ENT_QUOTES);
                $status_id = htmlspecialchars($_POST['status_id'], ENT_QUOTES);
                $booking_id = htmlspecialchars($_POST['booking_id'], ENT_QUOTES);
                            
                $url=$baseurl . 'createBookingTime';
                
                $data = 
                    array(
                        "booking_time" => $booking_time, 
                        "status_id" => $status_id,
                        "booking_id" => $booking_id
                    );
                
                $json_data=@curl_request($data,$url);
               
                
             
               if($json_data['code'] !== 200)
               {
                    $_SESSION[PRE_FIX.'customMsg'] =$json_data['msg'];
                    echo "<script>window.location='dashboard.php?p=booking_time&action=error'</script>";
               }
               else
               {
                    echo "<script>window.location='dashboard.php?p=booking_time&action=success'</script>";
               }
    
                
            }
        }
        
        $url=$baseurl . 'allBooking';
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
                                    <h2>All Booking Time</h2>
                                    <div class="head-area">
                                    </div>
                                </div>

                                <div class="right" style="padding: 10px 0;">
                                   <button onclick="addBookingTime();"
                                           class="com-button com-submit-button com-button--large com-button--default">
                                       <div class="com-submit-button__content"><span>Add Booking Time</span></div>
                                   </button>
                                </div>
                                <!--start of datatable here-->


                                <table id="table_view" class="display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                    
                                    if($json_data['code']=="200")
                                    {
                                       
                                        foreach($json_data['msg'] as $data): ?>
    
                                            
                                            <tr>
                                                <td><?php echo $data['BookingTime']['id']; ?></td>
                                                <td><?php echo $data['BookingTime']['booking_time']; ?></td>
                                                <td>
                                                    <?php 
                                                        if($data['BookingTime']['b_status'] == 1)
                                                        {
                                                            echo "Publish";
                                                        }else{
                                                            echo "Draft";
                                                        }
                                                    ?>
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
                                                            <!-- editBookingTime -->
                                                            <ul class="more-menu-items" tabindex="-1" role="menu" aria-labelledby="more-btn" aria-hidden="true">
                                                                <li class="more-menu-item" role="presentation" onclick="editBookingTime(<?php echo $data['BookingTime']['id']; ?>)">
                                                                    <button type="button" class="more-menu-btn" role="menuitem">Edit</button>
                                                                </li>
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                </td>        

                                            </tr>
    
                                        <?php endforeach;
                                    }
                                    
                                    ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Time</th>
                                            <th>Status</th>
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
        
        
        function editBookingTime(id) 
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
            xmlhttp.open("GET", "ajex-events.php?action=editBookingTime&id="+id);
            xmlhttp.send();
        }
        
        function addBookingTime() 
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
            xmlhttp.open("GET", "ajex-events.php?action=addBookingTime");
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