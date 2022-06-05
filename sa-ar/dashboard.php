<?php 
require_once("header.php");
if(!isset($_SESSION['id']))
{ 
    echo "<script>window.location='index.php'</script>";
    die;
} 
?>

<div class="section mini dashboardscreen"><div class="wdth">
	<div class="col25 left">
		<?php require_once("login_leftsidebar.php"); ?> 
	</div>
	<div class="col75 right contentside">
		<?php 
    		if(!isset($_GET['p']) ) 
    		{ 
    			?>
    				<h2 class="title">Dashboard</h2>
    				<p>Main dashboard here.. </p>
    			<?php
    		}
    		else 
    		{
    
    			if( $_SESSION['user_type'] == "user" ) 
    			{ 
                    if( $_GET['p'] == "order" ) 
    				{ 
    					include("order.php");
    				} 
                    if( $_GET['p'] == "account" ) 
    				{
    					include("account.php");
    				} 
                    if( $_GET['p'] == "changepassword" ) 
    				{
    					include("changepassword.php");
    				} 
                    if( $_GET['p'] == "address" ) 
    				{
    					include("address.php");
    				} 
                    if( $_GET['p'] == "payment" ) 
    				{ 
    					include("payment.php");
    				} 
    			} 
    		}
	    ?>
	</div>
	<div class="clear"></div>
</div></div>

<?php require_once("footer.php"); ?>