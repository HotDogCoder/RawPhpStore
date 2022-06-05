<ul class="login_leftsidebar"> 
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=order">My Orders</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "account" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=account">My Account</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=changepassword">Change Password</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "payment" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=payment">Payment Methods</a></li>
</ul>