<?php 

require_once("header.php"); 

if(isset($_SESSION[PRE_FIX.'rider_id'])){ 
    echo "<script>window.location='dashboard.php?p=summary'</script>";
    die;
} 

if( isset($_GET['riderRequest']) ) { //forget password

    $first_name = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
	
	if( !empty($email) ) 
	{ 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"email" => $email,
			"password" => $password,
			"role" => "rider",
			"device_token"=>""
		);
        
		$ch = curl_init( $baseurl.'/registerUser' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($json_data['code'] !== 200)
		{
		    $_SESSION[PRE_FIX.'errorMsg']=$json_data['msg'];
			echo "<script>window.location='index.php?action=error'</script>";

		} 
		else 
		{
		    $_SESSION[PRE_FIX.'successMsg']="Thank you for registering. Your profile will be activated by the admin";
			echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
		echo "<script>window.location='index.php?action=error'</script>";
	} 

}


?>



<div class="landingimage courier bgimage" style="background-image: url(img/new/bg.png);  background-repeat: no-repeat;  background-position: top;">

		<img src="img/new/yellow_top.png" style="position: absolute; top: 8vh; left: 0; height: 22vh; width: 85%;"> 
		<img src="img/new/yellow_bottom.png" style="position: absolute; top: 60%; right: 0%; height: 25%; width: 13%;">
		
<div class="inner"><div class="wdth">

	<div class="topvh">

		<div class="col40 left" style="display: none;">

			<h1>At foodies</h1>

			<h3>it's all about delivering what you desire for.</h3>

			<p><a href="#" class="button">Learn More</a></p>

		</div>

		<?php if( !isset($_SESSION[PRE_FIX.'rider_id']) ){ ?>

		<div class="col40 right" style="margin: 4% 0 15% 0;">

			<div class="frm" style="border-radius: 25px;">

				<form action="index.php?reset=ok" method="post" id="forform" style="display: none;">

					<h2 class="title" style="color: #38215C;">Forget Passowrd?</h2>

					<p>

						<input placeholder="Email" type="text" name="emailaddr" style="border-radius: 25px;">

					</p>

					<p>

						<input type="submit" class="button" value="Recover Password" style="background-color: #E86942; border-radius: 25px;">

					</p>

					<p class="byproceeding">

						Already have an account? <a href="javascript:;" id="login">Login</a>

					</p>

				</form>

				<form action="?log=in" method="post" id="logform" style="display: none;">

					<h2 class="title" style="color: #38215C;">Earn More</h2>

					<p>

						<input placeholder="Email" type="text" name="eml" style="border-radius: 25px;">

					</p>

					<p>

						<input placeholder="Password" type="password" name="pswd" style="border-radius: 25px;">

					</p>

					<p>

						<input type="submit" class="button" value="Log In" style="background-color: #E86942; border-radius: 25px;">

					</p>

					<p class="byproceeding">

						Not have an account? <a href="javascript:;" id="register">Register</a>

					</p>

					<p class="byproceeding" style="margin-top: 5px;">

						Forgot Password? <a href="javascript:;" id="forgot">Recover</a>

					</p>

				</form>

				<script>

				$(document).ready(function(){

					$("input#phne").inputmask();

				});

				</script>

				<form action="?riderRequest=ok" method="post" id="regform">

					<h2 class="title" style="color: #38215C">Register Now</h2>

					<p>

						<span class="left col50" style="position: relative;"><input placeholder="First Name" type="text" name="firstname" style="border-radius: 25px;"></span>

						<span class="right col50"><input placeholder="Last Name" type="text" name="lastname" style="border-radius: 25px;"></span>

						<span class="clear" style="display: block;"></span>

					</p>
                    <span class="clear" style="display: block;"></span>
					<p>
                    	<span><input placeholder="Email" type="email" name="emailaddr" style="border-radius: 25px;"></span>
					</p>
					
					<p>

						<span><input placeholder="Password" type="password" name="password" style="border-radius: 25px;"></span>
					</p>

					<p>

						<input type="submit" class="button" value="Get Started" style="border-radius: 25px; background-color: #E86942;">

					</p>

					<p class="byproceeding">

						Already have an account? <a href="javascript:;" id="login">Login</a>

					</p>

				</form>

			</div>

		</div>

		<?php } ?>

		<div class="clear"></div>

	</div>

</div></div></div>



<script type="text/javascript">

jQuery(document).ready(function(){

	jQuery("a#forgot").on("click", function(){

		jQuery('form#logform').hide();

		jQuery('form#regform').hide();

		jQuery('form#forform').show();

	});

	jQuery("a#login").on("click", function(){

		jQuery('form#regform').hide();

		jQuery('form#logform').show();

		jQuery('form#forform').hide();

	});

	jQuery("a#register").on("click", function(){

		jQuery('form#logform').hide();

		jQuery('form#regform').show();

		jQuery('form#forform').hide();

	});

});

</script>





<div class="howto section" style="background-color: white;" ><div class="wdth">

	<h2 class="title textcenter" style="font-size: 40px; color: #38215C">How To Get Started</h2>

	<ul style="text-align:center;" >

		<li>

			<div class="col80">

				<img src="img/new/sign_up.png" width="60"> 

			</div>

			<div class="col80" style="margin-top: 28px; color: #38215C" >

				<h3>Sign up</h3>

				<p>Join and get you self-incorporated into our supportive team.</p>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col80">

				<img src="img/new/download_app.png" width="50"> 

			</div>

			<div class="col80 " style="margin-top: 15px; color: #38215C">

				<h3>Download app</h3>

				<p>Download the application and you're about to get ready to deliver food as soon as our team approve your account </p>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col80">

				<img src="img/new/start_earning.png" width="78"> 

			</div>

			<div class="col80" style="margin-top: 20px; color: #38215C">

				<h3>Start earning</h3>

				<p>Begin gaining with simply straightforward advances</p>

			</div>

			<div class="clear"></div>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>





<div class="section whythis" style="background-color: #EFA7A3; color: #38215C;"><div class="wdth">

	<div class="col50 left" >

		<h2 class="title" style="font-size: 45px; margin-bottom: 6%; margin-top: 8%;">Become a Rider</h2>

		<p >If you want to have a second source of income, you can start <br> working with us! Apply with the following form.</p>

	</div>

	<div class="col50 right quote" style="color: #38215C;">

		<ul>

			<li>
				<img src="img/new/avatar_1.png" style="vertical-align: middle; height: 80px; width: 80px; border-radius: 50%; background-color: #F4C951; margin-bottom: 15px;"> 
				<p class="a" style="font-size: 18px;">Saad</p><br>


				<p class="q"> Having a motorcycle and the desire to progress has increased my income without having to sacrifice my personal time, I can say that WEYDROP has changed my life! </p>


			</li>

			<li>

				<i class="fa fa-quote-left"></i>

				<p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>

				<p class="a">Maaz 26</p>

			</li>

			<li>

				<i class="fa fa-quote-left"></i>

				<p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>

				<p class="a">Zayan 26</p>

			</li>

		</ul>

	</div>

	<div class="clear"></div>

</div></div>

<div id ="need_section" class="howto wyn section" style="background-color: white; color: #38215C">
<div class="wdth">

	<h2 class="title textcenter">What You Need?</h2>

	<ul>

		<li>

			<div class="col20 left"><span class="digit" style="background-color: #51D6BD; border-style: none;">1</span></div>

			<div class="col80 left">

				<h3>A Valid License</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit" style="background-color: #E86942; border-style: none;">2</span></div>

			<div class="col80 left">

				<h3>A Smartphone</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit" style="background-color: #F4C951; border-style: none;">3</span></div>

			<div class="col80 left">

				<h3>A Working Vehicle</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit" style="background-color: #EFA7A3; border-style: none;">4</span></div>

			<div class="col80 left">

				<h3 style="white-space: nowrap;">Proof Of Work Eligibility</h3>

			</div>

			<div class="clear"></div>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>

<div id="join_section" class="threecol textcenter section" style="background:white;  color: #38215C">
<div class="wdth">

	<ul>

		<li>

			<img src="img/new/download_app.png" width="50"> 

			<h3>Technology</h3>

			<p>We are a digital company and for that reason your smartphone will be your main tool.</p>

		</li>

		<li>

			<img src="img/new/schedule.png" width="73"> 

			<h3>Schedule flexibility</h3>

			<p>You choose the moment you want to distribute, you just have to show yourself as available in the app and we know we are counting on you.</p>

		</li>

		<li>

			<img src="img/new/start_earning.png" width="85"> 

			<h3 style="margin-top: 20px;">Benefits</h3>

			<p>Being part of our team you will enjoy incentives and benefits for you. Tips are entirely yours.</p>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>

<div class="mobrow bgimage appBanner" style="background-color: white; padding-top: 18%; padding-bottom: 10%; background-image: url(img/new/order_background.png); background-position: top;">
	<div class="wdth">
		<!-- <div class="right col60 section"> -->
		<div class="col60 section" style="">
			<h2 class="title" style="color: #38215C; font-size: 55px; font-family: Montserrat; font-weight: 100;">Order your food</h2>
			<div style="margin-left: 8%; text-align: center;">
			<p class="appDescription" style="font-family: Montserrat; width: 350px; color: #38215C; font-size: 15px; padding-bottom: 5%">We bring yummy food to you. Choose a restaurant and send your order. We'll deliver that food to you in 30 minutes.</p>
			<p class="logos" style="width: 350px;">
				<a href="<?php echo FOOTER_ANDROID_URL; ?>" target="_blank"><img src="img/new/gplaystore.svg" alt="play store" /></a>
				<a href="<?php echo FOOTER_iOS_URL; ?>" target="_blank"><img src="img/new/appstore.svg" alt="apple store" /></a>
			</p>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>


<?php //reset password

if(isset($_GET['reset']) && !empty($_POST['emailaddr'])) {

		

		$email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);

		

		$headers = array(

			"Accept: application/json",

			"Content-Type: application/json"

		);



		$data = array(

			"email" => $email,

			"role" => "rider"

		);



		$ch = curl_init( $baseurl.'/resetPassword' );



		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



		$return = curl_exec($ch);



		$json_data = json_decode($return, true);

	    var_dump($json_data);



		$curl_error = curl_error($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);



		//echo $json_data['code'];

		//die;



		if($json_data['code'] !== 200){

			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";

			@header("Location: index.php?action=error");

				echo "<script>window.location='index.php?action=error'</script>";



		} else {

			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";

			@header("Location: index.php?p=action=success");

				echo "<script>window.location='index.php?action=success'</script>";

		}



		curl_close($ch);

}

//remove resetpass = end ?>

<?php require_once("footer.php"); ?>