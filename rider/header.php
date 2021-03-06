<?php require_once("./config.php");
$pgname = explode(".php", basename($_SERVER['PHP_SELF']));
if($pgname[0] == "index") {
    $pagename="home";
} else {
    $pagename= $pgname[0];
}
//echo $pagename;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weydrop Food Delivery & Take Out</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="rs-plugin/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="fontawesome/css/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	<link rel="shortcut icon" href="img/new/fav.png">	
	
    <script src="js/jquery-1.12.4.js"></script>
    <script src="js/jquery-ui.js"></script>
	<script src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>
	<script src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script src="js/jquery.inputmask.bundle.js"></script>
	<script src="js/inputmask.numeric.extensions.js"></script>
	<script src="js/phone.js"></script>
	<script src="js/jquery.timepicker.js"></script>
	<script src="js/pagination.js"></script>
	<script src="js/custom.js?<?php echo time(); ?>"></script>
	

	<script src="https://maps.google.com/maps/api/js?key=AIzaSyAEDq8M6WsXVmo_08lPapjlqYCFVRBt6ro&libraries=places"></script>
	<script src="js/locationpicker.jquery.js"></script>

	<link rel="stylesheet" type="text/css" href="slick/slick.css">
	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"> 
	<script src="slick/slick.js" type="text/javascript" charset="utf-8"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88404643-1"></script>
	<!-- Google Translate -->
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	<script type="text/javascript">
		function googleTranslateElementInit() {
		  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ar,en'}, 'google_translate_element');
		}

		$( document ).ready(function() {
		   
			var selected_value = $("goog-te-combo").val();
			console.log(selected_value);
			
			if(selected_value == "undefined"){
				jQuery(".goog-te-combo").css('text-indent','30px');
			}else{
				jQuery(".goog-te-combo").css('text-indent','0px');
			}

		});
	</script>	
	<style>
	.goog-te-banner-frame.skiptranslate {
		display: none !important;
		}
	body {
		top: 0px !important; 
		}
	</style>	
	<style>
	.content{
	  background:#FFF;
	  opacity:.6;
	  text-align:center;
	}
	.goog-te-combo{
		background: #f4c951;
		color: #38215C;
		min-width:100px;
		/*text-indent :30px;*/
		//margin-right: 3% !important;
		//margin-top: 1% !important;
		padding: 8px;
		border-radius: 5px;
		width: 100%;
		/*float: right;*/
		z-index: 999;
		//position: relative;
		//margin-left: 85% !important;
	  
	}
	.goog-logo-link {
	   display:none !important;
	}

	.goog-te-gadget{
	   color: transparent !important;
	}
	select{
		-moz-appearance: window;
		-webkit-appearance: none;
		background: #f5f5f5 url("/images/arrow_down.png") right center no-repeat;
		//padding-right: 20px;
	}
	</style>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-88404643-1');
    </script>
	<script>
	jQuery(document).ready(function(){

		jQuery(".quote ul").slick({
		    dots: true,
			arrows: false,
		    infinite: true,
		    centerMode: false,
		    autoplay: true,
			autoplaySpeed: 5000,
		    slidesToShow: 1,
		    slidesToScroll: 1
		});

	});

	//toggle menu
	function openNav() {
		jQuery('#opensidemenu').toggleClass('change');
		jQuery('#mySidenav').toggleClass('toggle');
	}
	</script>

</head>
<body class="<?php echo $pagename; ?>">
<?php
    if(@$_GET['action']=="notAllowDemo")
    {
        ?>
            <div class="statusBar">This is a demo account. Some of the options have been disabled for example updation. You will receive an error if you try to update something </div>
        <?php
    }
?>
<script>
   $(window).load(function() {
     $('#status').fadeOut();
     $('#preloader').delay(350).fadeOut('slow');
     $('body').delay(350).css({'overflow':'visible'});
   })
  </script>

<div id="preloader" align="center">
	<div id="loading">
		<img src="img/loader.gif" alt="Loading.." height="140" />
	</div>
</div>

<!--  THIS WRAPPER NEEDS TO EXIST IN ORDER TO SEND THE MARKUP TO GOOGLE -->
<?php 
if(!isset($_GET['p'])){
	?>
    <div id="google_translate_element" style="z-index: 9999; position: absolute; top: 0.3%; right: 2%; height: 65px; width: 146px;"></div>
	<?php
}
?>


<?php if( isset($_SESSION[PRE_FIX.'rider_id']) ){ ?>
<!-- toggle menu -->
<div id="mySidenav" class="sidenav">
	<ul>
		<?php if( isset($_SESSION[PRE_FIX.'rider_id']) ){ ?>
			<li><a><span class="myacc">My Account</span> <span class="nameontop"><?php echo $_SESSION[PRE_FIX.'name']; ?></span></a>
				<ul class="sub-menu">
					<?php if( $_SESSION[PRE_FIX.'user_type'] == "rider" ) { //rider ?>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=summary">Summary <span class="blok">View work summary</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "account" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=account">My Account <span class="blok">View or edit your account details</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=changepassword">Change Password <span class="blok">Change your account password</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "bankinfo" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=bankinfo">Withdraw & Bank Information <span class="blok">View or edit your bank details</span></a></li>

						<?php } //rider = end

					else { } ?>

					<li class="logout"><a href="index.php?log=out">Log Out</a></li>
				</ul>
			</li>
			
		<?php } else { ?>
			<li><a href="javascript:;" onClick="popup('login')">Login / Sign Up</a></li>
		<?php } ?>
	</ul>
</div>
<!-- toggle menu --> 
<?php } ?>

<header class="siteheader" style="background: white; background-image: url(img/new/topbar_yellow.png); background-size: contain; background-repeat:no-repeat;">
<div class="wdth" style="margin-left: 2%;">
	<div class="left">
		<a href="index.php"><img src="img/new/logo.png" style="height: 30px;" alt="" /></a>
	</div>
	<div class="right navbar">
		<?php if( isset($_SESSION[PRE_FIX.'rider_id']) ){ ?>
		<span class="menu-icon opensidemenu" id="opensidemenu" onClick="openNav()">
		  <span class="bar1"></span>
		  <span class="bar2"></span>
		  <span class="bar3"></span>
		</span>
		<?php } else { /* ?>
		<ul class="nav-menu mnav">
			<li><a href="javascript:;" onClick="popup('login')"><i class="fa fa-user-circle"></i></a></li>
		</ul>
		<?php */ } ?>
		<ul class="nav-menu dnav">
			<?php if( isset($_SESSION[PRE_FIX.'rider_id']) ){ ?>
				<li class="lastmenu">
				
					<?php 
					if(isset($_GET['p'])){
						?>
						<div id="google_translate_element" style="z-index: 9999; position: absolute; top: -1vh; right: 12.5vw; height: 50px; width: 120px;"></div>
						<?php
					}
					?>
					
				<a> <span class="nameontop" style="color: #38215C"><?php echo $_SESSION[PRE_FIX.'name']; ?> <i class="fa fa-caret-down"></i></span></a>
					<ul class="submenu">
						<?php if( $_SESSION[PRE_FIX.'user_type'] == "rider" ) { //rider ?>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=summary">Summary <span class="blok">View work summary</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "account" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=account">My Account <span class="blok">View or edit your account details</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=changepassword">Change Password <span class="blok">Change your account password</span></a></li>

							<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "bankinfo" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=bankinfo">Withdraw & Bank Information <span class="blok">View or edit your bank details</span></a></li>

						<?php } //rider = end

						else { } ?>

						<li class="logout"><a href="index.php?log=out">Log Out</a></li>
					</ul>
				</li>
				
			<?php } else { ?>
				
			<?php } ?>
		</ul>
	</div>
	<div class="clear"></div>
</div></header>