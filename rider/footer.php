<div class="clear"></div>

<footer class="sitefooter" style="background-color: #7954B5;"><div class="wdth">


	<div class="copyright">
		By continuing past this page, you agree to our Terms of Service, Cookie Policy, Privacy Policy and Content Policies. All trademarks are properties of their respective owners. &copy; 2020 - Weydrop. All rights reserved.
	</div> <?php //copyright ?>
</div></footer>

<div class="popup" id="page_content"><div class="popup_container">
	<a href="javascript:;" onClick="javascript:jQuery('#page_content').hide();" id="close">&times;</a>
	<div id="page_content_sec">&nbsp;</div>
</div></div>

<?php 
if( isset($_GET['action']) ) {
	
	if( $_GET['action'] == "error" ) 
	{
		if(!isset($_SESSION[PRE_FIX.'errorMsg']))
        {
            $msg="Something went wrong";
        }
        else
        {
            $msg=$_SESSION[PRE_FIX.'errorMsg'];
        }
        echo "<div class='notification'><div class='wdth'><div class='alert alert-error'>".$msg."</div></div></div>";
		
		?>
		<script>setTimeout(function() {
		    $('#mydiv').fadeOut('fast');
		}, 1000); // <-- time in milliseconds</script>
		<?php
	}
	else
	if( $_GET['action'] == "success" ) 
	{
		
		if(!isset($_SESSION[PRE_FIX.'successMsg']))
        {
            $msg="Saved successfully";
        }
        else
        {
            $msg=$_SESSION[PRE_FIX.'successMsg'];
        }
        
		echo "<div class='notification'><div class='wdth'><div class='alert alert-success'>".$msg."</div></div></div>";
		?>
		<script>
			setTimeout(function() {
		    	$('.notification').fadeOut();
			}, 5000); 
		</script>
		<?php
	}
} //
?>

</body>
</html>