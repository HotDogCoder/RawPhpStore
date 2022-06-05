<?php 
if( isset($_SESSION[PRE_FIX.'sessionPortal']))
{       
       
        $url=$baseurl . 'showTransactions';
        $data =array();
        
       
        $json_data=@curl_request($data,$url);
        $json_data=$json_data['msg'];
        
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title half_width float_left">
                                    <h2>All Transactions</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                
                                <!--<div class="half_width float_right right page-title " style="padding: 10px 0;">-->
                                <!--    <span onclick="filterEarning();" style="color:#80808080; font-size: 14px;">-->
                                <!--        <span class="fa fa-filter"></span>-->
                                <!--        Filter Date-->
                                <!--    </span>-->
                                <!--</div>-->
                                
                                <div class="clear"></div>
                                <br>
                                

                                <table id="table_view" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Paid Date</th>
                                            <th>Payment Method</th>
                                            <th>Transaction Type</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                    
                                    
                                    foreach ($json_data as $single_user): ?>
                                        
                                        <tr>
                                            <td><?php echo $single_user['Transaction']['id']; ?></td>
                                            <td>
                                                <?php 
                                                    if($single_user['Restaurant']['name']=="")
                                                    {
                                                           echo $single_user['UserInfo']['first_name']." ".$single_user['UserInfo']['last_name']; 
                                                    }
                                                    else
                                                    {
                                                        echo $single_user['Restaurant']['name']; 
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $single_user['Transaction']['amount']; ?>
                                            </td>
                                            <td>
                                                <?php echo $single_user['Transaction']['paid_date']; ?>
                                            </td>
                                            <td>
                                                <?php echo $single_user['Transaction']['pay_via']; ?>
                                            </td>
                                            <td>
                                                <?php echo $single_user['Transaction']['type']; ?>
                                            </td>
                                            <td>
                                                <?php echo $single_user['Transaction']['created']; ?>
                                            </td>
                                            
                                    <?php endforeach; ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Restaurant Name</th>
                                            <th>Amount</th>
                                            <th>Paid Date</th>
                                            <th>Payment Method</th>
                                            <th>Transaction Type</th>
                                            <th>Created</th>
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