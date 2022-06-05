<?php 
require_once("header.php"); 

if(isset($_SESSION[PRE_FIX.'sessionTokon'])){ 
    echo "<script>window.location='dashboard.php?p=hotel_order&page=liveOrders'</script>";
    die;
} 

if( isset($_GET['restaurantRequest']) ) { //forget password

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
			"role" => "hotel",
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

<div class="landingimage courier bgimage" style="background-image: url(img/new/food_background.png); background-repeat: no-repeat; background-position: top; background-attachment: scroll;">
  
		<img src="img/new/yellow_top.png" style="position: absolute; top: 8%; right: 0%; height: 85%; width: 38%;">
  
  <div class="inner">
    <div class="wdth">
      <div class="topvh">
        <div class="col40 left" style="display: none;">
          <h1>Simple dummy title</h1>
          <h3>At foodomia, it's all about delivering what you desire for.</h3>
          <p style="display:none;"><a href="#" class="button">Learn More</a></p>
        </div>
        <?php if(!isset($_SESSION[PRE_FIX.'restaurant']) ){ ?>
        <div class="col40 right" style="margin: 4% 0 15% 0;">
          <div class="frm" style="border-radius: 25px;">
            <form action="index.php?reset=ok" method="post" id="forform" style="display: none;">
              <h2 class="title" style="color: #38215C;">Forgot your password?</h2>
              <p>
              <input type="text" name="emailaddr" required='' style="border-radius: 25px;">
              <label alt="Email" placeholder="Email" style="border-radius: 25px;">
              </p>
              <p>
                <input type="submit" class="button" value="Recover Password" style="border-radius: 25px; margin-bottom: 10px;">
              </p>
              <p class="byproceeding"> Already have an account? <a href="javascript:;" id="login">Login</a> </p>
            </form>
            <form action="?log=in" method="post" id="logform" style="display: none;">
              <h2 class="title" style="color: #38215C;">Login</h2>
              <p>
              <input type="text" name="eml" required="" style="border-radius: 25px;">
              <label alt="Email" placeholder="Email" style="border-radius: 25px;">
              </p>
              <p>
              <input type="password" name="pswd" required="" style="border-radius: 25px;">
              <label alt="Password" placeholder="Password" style="border-radius: 25px;">
              </p>
              <p>
                <input type="submit" class="button" value="Log In">
              </p>
              <p class="byproceeding"> Not have an account? <a href="javascript:;" id="register">Register</a> </p>
              <p class="byproceeding" style="margin-top: 5px;"> Forgot Password? <a href="javascript:;" id="forgot">Recover</a> </p>
            </form>
            <script>
				$(document).ready(function(){
					$("input#phne").inputmask();
				});
				</script>
            <form action="?restaurantRequest=ok" method="post" id="regform">
              <h2 class="title" style="color: #38215C;">Register Now</h2>

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

					<input type="submit" class="button" value="Get Started"  style="border-radius: 25px; margin-bottom: 25px;">

				</p>
              <p class="byproceeding"> Already have an account? <a href="javascript:;" id="login">Login</a> </p>
            </form>
          </div>
        </div>
        <?php } ?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
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

<div class="threecol section home1 secprimery" style="background-color: white;">

<div class="wdth">

	<h2 class="title textcenter" style="font-size: 40px; color: #38215C; margin-bottom: 5%;">Benefits to be part of our team!</h2>

	<ul style="text-align: center; margin: 0 0 3% 6%;" >

		<li style="width: 33%;">

			<div class="col80">

				<img src="img/new/increase_icon.png" width="65"> 

			</div>

			<div class="col80" style="margin-top: 28px; color: #38215C" >

				<h3>Increase your sales</h3>

				<p>Make new clients discover you and your clients enjoy an unforgettable service.</p>

			</div>

			<div class="clear"></div>

		</li>

		<li style="width: 33%;">

			<div class="col80">

				<img src="img/new/control_icon.png" width="78"> 

			</div>

			<div class="col80 " style="margin-top: 25px; color: #38215C">

				<h3>Control your deliveries</h3>

				<p>You can monitor the status of your deliveries and get feedback from your users. </p>

			</div>

			<div class="clear"></div>

		</li>

		<li style="width: 33%;">

			<div class="col80">

				<img src="img/new/know_icon.png" width="70"> 

			</div>

			<div class="col80" style="margin-top: 16px; color: #38215C">

				<h3>Know your clients</h3>

				<p>Get information from your users, in order to improve their experience and services.</p>

			</div>

			<div class="clear"></div>

		</li>

	</ul>

	<div class="clear"></div>

</div>
</div>

<div class="teamup bgimage" style="background-color: #EFA7A3; color: #38215C;">
  <div>
    <div class="wdth">
      <div class="col100  section" align="center">
        <h2 class="title" style="font-size: 40px; color: #38215C;">Improve Your<br><br><span>Sales!</span></h2>
        <p>Become a partner. Join to reach more customers and increase your sales.</p>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>


<div class="threecol section home1 white" style=" color: #38215C;">
  <div class="wdth">
    <div class="section-title">
      <h2 class="title" style="font-size: 40px;">Features</h2>
    </div>
    <ul>
      <li>
        <div class="work-fonts">
          <img src="img/new/live_icon.png" width="70"> 
        </div>
        <h4 class="title">Live tracking</h4>
        <p>Know where your order is at all times, from the restaurant to your doorstep. Like never before!</p>
      </li>
      <li>
        <div class="work-fonts">
          <img src="img/new/delivery_icon.png" width="95"> 
        </div>
        <h4 class="title" style="margin-top: 9%;">No delivery area restriction</h4>
        <p>Our superfast delivery for food delivered fresh & on time, anywhere at any place</p>
      </li>
      <li>
        <div class="work-fonts">
          <img src="img/new/increase_icon_2.png" width="60"> 
        </div>
        <h4 class="title">Increase your revenue</h4>
        <p>When your sustenance is offered in the app, new clients locate it What's more faithful clients might appreciate it All the more often.</p>
      </li>
    </ul>
    <ul class="clearb">
      <li>
        <div class="work-fonts">
          <img src="img/new/free_icon.png" width="70"> 
        </div>
        <h4 class="title" style="margin-top: 10%;">Free registration</h4>
        <p>Watch your revenue increase quickly. Maximize your kitchen's efficiency and get the business you're missing out on.</p>
      </li>
      <li>
        <div class="work-fonts">
          <img src="img/new/pick_icon.png" width="50"> 
        </div>
        <h4 class="title" style="margin-top: 7%;">Pick up location</h4>
        <p>We'll send orders to your kitchen. You concentrate on cooking great food. We take care of the rest.</p>
      </li>
      <li>
        <div class="work-fonts">
          <img src="img/new/get_icon.png" width="60"> 
        </div>
        <h4 class="title">Get weekly report</h4>
        <p>You choose your prep times, control the pace of your kitchen, and manage the details for every order.</p>
      </li>
    </ul>
    <div class="clear"></div>
  </div>
</div>
 
<div style="background-color: white; padding-top: 18%; padding-bottom: 15%; background-image: url(img/new/order_background.png); background-size: cover;">
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
			"role" => "hotel"
		);

		$ch = curl_init( $baseurl.'/resetPassword' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    ///var_dump($json_data);

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