<ul class="login_leftsidebar"> 
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=order">طلباتي</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "account" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=account">حسابي</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=changepassword">غير كلمة السر</a></li>
    <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "payment" ) { echo 'class="active"'; } } ?> ><a href="dashboard.php?p=payment">طرق الدفع</a></li>
</ul>