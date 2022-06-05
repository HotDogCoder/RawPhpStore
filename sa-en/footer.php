<link rel="stylesheet" href="assets/css/intlTelInput.css?<?php echo time(); ?>">
<div class="clear"></div>
<footer class="sitefooter" style="background-color: #7954B5;">
    <div class="wdth" style="overflow:hidden;">
        <div class="left" style="width: 710px; padding: 1.5%">
            <span style="float: left;"><?php echo STATIC_FOOTER; ?></span>
        </div>
		<div class="right" style="display: inline; padding-top: 0.7%; display: flex; flex-direction: row;  align-items: center;">
            <a href="terms.php" style="margin-left: 15px; color: white;"><span>Terms & Conditions</span></a>
            <a href="privacy.php" style="margin-left: 15px; color: white;"><span>Privacy Policy</span></a>
            <a href="about.php" style="margin-left: 15px; margin-right: 15px; color: white;"><span>Contact us</span></a>
			<a href="https://www.facebook.com/Weydrop/">
				<img src="assets/img/new/instagram_icon.png" alt="instagram" style="width:35px;height:35px;margin-right: 5px;">
			</a>  
			<a href="https://www.instagram.com/weydrop/">
				<img src="assets/img/new/facebook_icon.png" alt="facebook" style="width:35px;height:35px;">
			</a> 
		</div>
    </div>
</footer>
<div class="popup" id="page_content">
    <div class="popup_container"><a href="javascript:;" onClick="javascript:jQuery('#page_content').hide();" id="close">&times;</a>
        <div id="page_content_sec">&nbsp;</div>
    </div>
</div>
    

<?php if (!isset($_SESSION['id'])) 
{
?>
    <div class="popup" id="loginpopup">
        <div class="popup_container"><a style="margin: 1%;" href="javascript:;" onClick="javascript:jQuery('#loginpopup').hide();" id="close">&times;</a>
            <div id="page_content_sec" style="border-radius: 25px;">
                <div class="login_form">
                    <div class="left col50 bgimage leftside" style="border-radius: 25px 0px 0px 25px;">
                        <div class="leftContent" style="color: #38215C; text-align: center; margin-top: 35%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none;">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="right col50 rightside">
                        <div class="header"><?php echo POPUP_TITLE_LOGIN; ?></div>
                        <div class="form">
                            
                            <?php
                                if(FACEBOOK_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div class="facebookbtn">
                                            <div class="fb-login-button" scope="public_profile,email" onlogin="checkLoginState();" data-width="335" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>
                                        </div>
                                    <?php
                                }
                                
                                if(GOOGLE_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div class="g-signin2" data-onsuccess="onSignIn1"></div>
                                    <?php 
                                }

                                if(PHONE_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div style="height: 36px;margin-bottom: 20px;" id="phonelogin_button"><button type="button" onclick="showPhoneLoginForm();">Login with Phone</button></div>
                                        <div style="height: 36px;margin-bottom: 20px;display:none;" id="emaillogin_button"><button type="button" onclick="showEmailLoginForm();">Login with Email</button></div>
                                    <?php 
                                }
                                
                                if(FACEBOOK_LOGIN_ENABLE!="false" || GOOGLE_LOGIN_ENABLE!="false")
                                {
                                     ?>
                                        <span class="divider">OR</span>
                                     <?php
                                }
                            ?>
                           
                            
                            

                                <div class="login_step1">
                                    <form action="index.php?log=in" id="loginfrm" method="post">
                                <p>
                                    <input type="email" name="eml" id="eml" required/>
                                    <label alt="Email Address" placeholder="Email Address"></label>
                                </p>
                                <p>
                                    <input type="password" name="pswd" id="pswd" required/>
                                    <label alt="Password" placeholder="Password"></label>
                                    <a href="javascript:;" onClick="popup('forget')" class="forgetlink">Forget?</a></p>
                                    <input type="hidden" name="returnlink" value="<?php echo $actual_link = " http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI] "; ?>">
                                    <p>
                                        <button type="submit">Login</button>
                                    </p>
                                    </form>     
                                </div>
                                <form action="index.php?log=in" id="loginfrm1" method="post">
                                <div class="login_step2" style="display: none;">
                                    <p>
                                        <!-- <input type="text" name="phne" id="phne" data-inputmask="'alias': 'phone'" required/> -->
                                        <input type="text" name="phne" id="phne_login" required/>
                                        <label alt="Phone Number" placeholder="Phone Number"></label>
                                        <input type="hidden" name="phne_login_code" />
                                    </p>
                                    <p>
                                        <button type="button" class="btnloginsendcode" >Send me a Code
                                        </button>
                                    </p>
                                </div>
                                <div class="login_step3" style="display: none;">
                                    <p>
                                        <input type="text" name="confirmation_code" id="confirmation_code_login" max="4" required/>
                                        <label alt="Verification Code" placeholder="4 Digit Code"></label>
                                    </p>
                                    <p>
                                        <button type="button" class="btnloginverifycode" >
                                            Verify Phone
                                        </button>
                                    </p>
                                    <p>If not received, <a class="anchor btnloginsendcode" href="javascript:;" >resend</a></p>
                                </div>
                                <div class="login_step4" style="display: none;">
                                    <p>Phone Verified, now please submit information to login yourself!</p>
                                    
                                </div>
                                
                                
                            </form>
                        </div>
                        <div class="footer" style="border-radius: 0px 0px 25px 0px;"> New to Weydrop? <a href="javascript:;" onClick="popup('signup')">Sign up!</a></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup" id="registerpopup">
        <div class="popup_container"><a style="margin: 1%;" href="javascript:;" onClick="javascript:jQuery('#registerpopup').hide();" id="close">&times;</a>
            <div id="page_content_sec">
                <div class="login_form">
                    <div class="left col50 bgimage leftside" style="border-radius: 25px 0px 0px 25px;">
                        <div class="leftContent" style="color: #38215C; text-align: center; margin-top: 35%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C;"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="right col50 rightside" style="border-radius: 25px;">
                        <div class="header"> <?php echo POPUP_TITLE_SIGNUP; ?></div>
                        <div class="form">
                            
                            
                            <?php
                                if(FACEBOOK_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div class="facebookbtn">
                                            <div class="fb-login-button" scope="public_profile,email" onlogin="checksignupState();" data-width="335" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>
                                        </div>
                                    <?php
                                }
                                
                                if(GOOGLE_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                                    <?php 
                                }

                                if(PHONE_LOGIN_ENABLE=="true")
                                {
                                    ?>
                                        <div style="height: 36px;margin-bottom: 20px;" id="phonesignup_button"><button type="button" onclick="showPhoneSignupForm();">Signup with Phone</button></div>
                                        <div style="height: 36px;margin-bottom: 20px;display: none;" id="emailsignup_button"><button type="button" onclick="showEmailSignupForm();">Signup with Email</button></div>
                                    <?php 
                                }
                                
                                if(FACEBOOK_LOGIN_ENABLE!="false" || GOOGLE_LOGIN_ENABLE!="false" || PHONE_LOGIN_ENABLE!="false")
                                {
                                     ?>
                                        <span class="divider">OR</span>
                                     <?php
                                }
                            ?>
                            
                            
                            

                            <form action="index.php?reg=ok" id="registerfrm" method="post">
                                <div class="signup_step1">
                                    <div id="signup_email_name">
                                        <div class="col50 left">
                                            <p>
                                                <input type="text" name="firstname" id="firstname" required/>
                                                <label alt="First Name" placeholder="First Name"></label>
                                            </p>
                                        </div>
                                        <div class="col50 right">
                                            <p>
                                                <input type="text" name="lastname" id="lastname" required/>
                                                <label alt="Last Name" placeholder="Last Name"></label>
                                            </p>
                                        </div>
                                        <input type="hidden" name="phone_signup" id="phone_signup" value="0" />
                                        <div class="clear"></div>
                                    </div>
                                    <p>
                                        <input type="email" name="emailaddr" id="emailaddr" required/>
                                        <label alt="Email Address" placeholder="Email Address"></label>
                                    </p>
                                    <p>
                                        <input type="password" name="paswd" id="paswd" required/>
                                        <label alt="Password" placeholder="Password"></label>
                                    </p>
                                    <p>
                                        <button type="button" onClick="nextsttep()">Next Step</button>
                                    </p>
                                </div>
                                <div class="signup_step2">
                                    <div id="signup_phone_name">
                                    </div>
                                    <p>
                                        <!-- <input type="text" name="phne" id="phne" data-inputmask="'alias': 'phone'" required/> -->
                                        
                                        <input type="text" name="phne" id="phne" required/>
                                        <label alt="Phone Number" placeholder="Phone Number"></label>
                                        <input type="hidden" name="phne_code" />
                                    </p>
                                    <p>
                                        <button type="button" class="btnsignupsendcode" >Send me a Code
                                        </button>
                                    </p>
                                </div>
                                <div class="signup_step3">
                                    <p>
                                        <input type="text" name="confirmation_code" id="confirmation_code" max="4" required/>
                                        <label alt="Verification Code" placeholder="4 Digit Code"></label>
                                    </p>
                                    <p>
                                        <button type="button" class="btnsignupverifycode" >
                                            Verify Phone
                                        </button>
                                    </p>
                                    <p>If not received, <a class="anchor btnsignupsendcode" href="javascript:;" >resend</a></p>
                                </div>
                                <div class="signup_step4">
                                    <p>Phone Verified, now please submit information to register yourself!</p>
                                    <p>
                                        <button id="register_button" type="submit">Register Now</button>
                                    </p>
                                </div>
                            </form>
                        </div>
                        <div class="footer"  style="border-radius: 0px 0px 25px 0px;"> Already have an account? <a href="javascript:;" onClick="popup('login')">Sign in!</a></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup" id="forgetpopup">
        <div class="popup_container"><a style="margin: 1%;" href="javascript:;" onClick="javascript:jQuery('#forgetpopup').hide();" id="close">&times;</a>
            <div id="page_content_sec">
                <div class="login_form">
                    <div class="left col50 bgimage leftside" style="border-radius: 25px 0px 0px 25px;">
                        <div class="leftContent"  style="color: #38215C; text-align: center; margin-top: 35%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C;"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="right col50 rightside" style="border-radius: 25px;">
                        <div class="header"><?php echo POPUP_TITLE_FORGET; ?></div>
                        <div class="form">
                            <br><br>
                            <div id="forgetfrm">
                                <p>
                                    <input type="email" name="forgetEmail" id="forgetEmail" required/>
                                    <label alt="Email Address" placeholder="Email Address"></label>
                                </p>
                                <p>
                                    <button type="submit" onclick="forgetPassword();">Recover It</button>
                                </p>
                            </div>
                            <br><br>
                        </div>
                        <div class="footer"  style="border-radius: 0px 0px 25px 0px;"> Already have an account? <a href="javascript:;" onClick="popup('login')">Sign in!</a></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <?php 
} 

if (isset($_GET['action'])) 
{
    if ($_GET['action'] == "error") 
    {   
        
        if( $_SESSION['error_msg']=="")
        {
            $error_msg="Some error, try again.. ";
        }
        else
        {
             $error_msg=$_SESSION['error_msg'];
        }
        ?>
            <div class='notification'>
                <div class='wdth'>
                    <div class='alert alert-error'><?php echo $error_msg;?></div>
                </div>
            </div>
        <?php
    }
    if ($_GET['action'] == "success") 
    {
        echo "<div class='notification'><div class='wdth'><div class='alert alert-success'>Saved successfully.. </div></div></div>";
    }
}

?>

<script src="assets/js/googlerender_dead.js?onload=renderButton" async defer></script>
<script>

var header = document.getElementById("time_slot");
var btns = header.getElementsByClassName("day");
	for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener("click", function() {
		var current = document.getElementsByClassName("selected");
		current[0].className = current[0].className.replace(" selected", "");
		this.className += " selected";
		});
	}
    window.fbAsyncInit = function() 
    {
        FB.init({
          appId      : '<?php echo FACEBOOK_APP_ID; ?>',
          xfbml      : true,
          version    : 'v6.0'
        });
        FB.AppEvents.logPageView();
    };
    
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    
    
</script>
<script src="assets/js/custom.js?<?php echo time(); ?>"></script>

<script src="assets/js/intlTelInput.js?<?php echo time(); ?>"></script>
<script>


jQuery('.time_slot lili').click(function() {  
         alert(1); 

	$(this).toggleClass("selected");
	
});

var input = document.querySelector("#phne");
var iti = window.intlTelInput(input, {
  onlyCountries: ["sa", "bh"],
  initialCountry: "sa",
  hiddenInput: "phne_code"
});


$('.btnsignupsendcode').click(function(){
    var phone = $('#phne').val();
    var country_code = "+"+iti.getSelectedCountryData().dialCode;
    $('input[name=phne_code]').val(country_code);
    sendmeacode(phone, country_code);
})

$('.btnsignupverifycode').click(function(){
    var phone = $('#phne').val();
    var country_code = "+"+iti.getSelectedCountryData().dialCode;
    var confirmation_code = $('#confirmation_code').val();
    verifyphone(phone, confirmation_code, country_code);
})


var input_login = document.querySelector("#phne_login");
var iti_login = window.intlTelInput(input_login, {
  onlyCountries: ["sa", "bh"],
  initialCountry: "sa",
  hiddenInput: "phne_login_code"
});


$('.btnloginsendcode').click(function(){
    var phone = $('#phne_login').val();
    var country_code = "+"+iti_login.getSelectedCountryData().dialCode;
    $('input[name=phne_login_code]').val(country_code);
    sendmeacodelogin(phone, country_code);
})

$('.btnloginverifycode').click(function(){
    var phone = $('#phne_login').val();
    var country_code = "+"+iti_login.getSelectedCountryData().dialCode;
    var confirmation_code = $('#confirmation_code_login').val();
    verifyphonelogin(phone, confirmation_code, country_code);
})

var owl = $('.owl-carousel');
    owl.owlCarousel({
        loop:false,
        nav:true,
        margin:10,
        autoplay: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },            
            960:{
                items:3
            },
            1200:{
                items:3
            }
        }
    });
    owl.on('mousewheel', '.owl-stage', function (e) {
        if (e.deltaY>0) {
            owl.trigger('next.owl');
        } else {
            owl.trigger('prev.owl');
        }
        e.preventDefault();
    });
</script>
</body>
</html>