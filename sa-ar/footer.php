<div class="clear"></div>
<footer class="sitefooter" style="background-color: #7954B5;">
    <div class="wdth" style="overflow:hidden;">
        <div class="left" style="width: 710px; padding: 1.5%">
            <span style="float: left;"><?php echo STATIC_FOOTER; ?></span>
        </div>
		<div class="right" style="display: inline; padding-top: 0.7%; display: flex; flex-direction: row;  align-items: center;">
			<a href="https://www.facebook.com/Weydrop/">
				<img src="assets/img/new/facebook_icon.png" alt="facebook" style="width:35px;height:35px;margin-left: 5px;">
			</a>  
			<a href="https://www.instagram.com/weydrop/">
				<img src="assets/img/new/instagram_icon.png" alt="instagram" style="width:35px;height:35px;">
			</a> 
            <a href="about.php" style="margin-right: 15px; color: white;"><span>Contact us</span></a>
            <a href="privacy.php" style="margin-right: 15px; color: white;"><span>Privacy Policy</span></a>
            <a href="terms.php" style="margin-right: 15px; color: white;"><span>Terms & Conditions</span></a>

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
                    <div class="right col50 bgimage leftside" style="border-radius: 0px 25px 25px 0px;">
                        <div class="leftContent" style="color: #38215C; text-align: center; margin-top: 28%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none;">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="left col50 rightside">
                        <div class="header" style="float: right; margin-top: 2%;"><?php echo POPUP_TITLE_LOGIN; ?></div>
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
                                
                                if(FACEBOOK_LOGIN_ENABLE!="false" || GOOGLE_LOGIN_ENABLE!="false")
                                {
                                     ?>
                                        <span class="divider">OR</span>
                                     <?php
                                }
                            ?>
                           
                            
                            <form action="index.php?log=in" id="loginfrm" method="post">
                                <p>
                                    <input type="email" name="eml" id="eml" required/>
                                    <label alt="البريد الإلكتروني" placeholder="البريد الإلكتروني"></label>
                                </p>
                                <p>
                                    <input type="password" name="pswd" id="pswd" required/>
                                    <label alt="كلمه السر" placeholder="كلمه السر"></label>
                                    <a href="javascript:;" onClick="popup('forget')" class="forgetlink" style="margin-right: 80%;">هل نسيت؟</a></p>
                                <input type="hidden" name="returnlink" value="<?php echo $actual_link = " http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI] "; ?>">
                                <p>
                                    <button type="submit">تسجيل الدخول</button>
                                </p>
                            </form>
                        </div>
                        <div class="footer" style="border-radius: 0px 0px 0px 25px;"> جديد على Weydrop؟ <a href="javascript:;" onClick="popup('signup')">أفتح حساب الأن!</a></div>
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
                    <div class="right col50 bgimage leftside" style="border-radius: 0px 25px 25px 0px;">
                        <div class="leftContent" style="color: #38215C; text-align: center; margin-top: 28%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C;"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="left col50 rightside" style="border-radius: 25px;">
                        <div class="header" style="float: right; margin-top: 3%; border-bottom: 0;"> <?php echo POPUP_TITLE_SIGNUP; ?></div>
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
                                
                                if(FACEBOOK_LOGIN_ENABLE!="false" || GOOGLE_LOGIN_ENABLE!="false")
                                {
                                     ?>
                                        <span class="divider">OR</span>
                                     <?php
                                }
                            ?>
                            
                            
                            

                            <form action="index.php?reg=ok" id="registerfrm" method="post">
                                <div class="signup_step1">
                                    <div class="col50 left" style="margin-top: 7.4%;">
                                        <p>
                                            <input type="text" name="firstname" id="firstname" required/>
                                            <label alt="الاسم الاول" placeholder="الاسم الاول"></label>
                                        </p>
                                    </div>
                                    <div class="col50 right">
                                        <p>
                                            <input type="text" name="lastname" id="lastname" required/>
                                            <label alt="اسم العائلة" placeholder="اسم العائلة"></label>
                                        </p>
                                    </div>
                                    <div class="clear"></div>
                                    <p>
                                        <input type="email" name="emailaddr" id="emailaddr" required/>
                                        <label alt="البريد الإلكتروني" placeholder="البريد الإلكتروني"></label>
                                    </p>
                                    <p>
                                        <input type="password" name="paswd" id="paswd" required/>
                                        <label alt="كلمه السر" placeholder="كلمه السر">
                                    </p>
                                    <p>
                                        <button type="button" onClick="nextsttep()">الخطوة التالية</button>
                                    </p>
                                </div>
                                <div class="signup_step2">
                                    <p>
                                        <!-- <input type="text" name="phne" id="phne" data-inputmask="'alias': 'phone'" required/> -->
                                        <input type="text" name="phne" id="phne" required/>
                                        <label alt="Pرقم الهاتف" placeholder="رقم الهاتف"></label>
                                    </p>
                                    <p>
                                        <button type="button" onClick="sendmeacode(document.getElementById('phne').value)">أرسل لي رمز
                                        </button>
                                    </p>
                                </div>
                                <div class="signup_step3">
                                    <p>
                                        <input type="text" name="confirmation_code" id="confirmation_code" max="4" required/>
                                        <label alt="شيفرة التأكيد" placeholder="4 أرقام الرمز"></label>
                                    </p>
                                    <p>
                                        <button type="button" onClick="verifyphone(document.getElementById('phne').value, document.getElementById('confirmation_code').value)">
                                            تحقق من الهاتف
                                        </button>
                                    </p>
                                    <p>إذا لم تتلق ،<a class="anchor" href="javascript:;" onClick="sendmeacode(document.getElementById('phne').value)">إعادة إرسال</a></p>
                                </div>
                                <div class="signup_step4">
                                    <p>التحقق من الهاتف ، يرجى الآن إرسال المعلومات لتسجيل نفسك!</p>
                                    <p>
                                        <button id="register_button" type="submit">أفتح حساب الأن</button>
                                    </p>
                                </div>
                            </form>
                        </div>
                        <div class="footer"  style="border-radius: 0px 0px 0px 25px;"> هل لديك حساب؟  <a href="javascript:;" onClick="popup('login')">تسجيل الدخول!</a></div>
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
                    <div class="right col50 bgimage leftside" style="border-radius: 0px 25px 25px 0px;">
                        <div class="leftContent"  style="color: #38215C; text-align: center; margin-top: 28%">
                            <h1 style="color: #38215C; font-weight: bold ; font-size: 50px; text-align: center"><?php echo POPUP_TITLE_WElCOME_H1; ?></h1>
                            <h3 style="color: #38215C;"><?php echo POPUP_TITLE_WElCOME_H3; ?></h3>
                            <ul style="list-style-type:none">
                                <li><?php echo POPUP_TITLE_WElCOME_LI_1; ?></li>
                                <li><?php echo POPUP_TITLE_WElCOME_LI_2; ?></li>
                                <!-- <li><?php echo POPUP_TITLE_WElCOME_LI_3; ?></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="left col50 rightside" style="border-radius: 0px; border-bottom: 0;">
                        <div class="header" style="float: right; margin-top: 3%;"><?php echo POPUP_TITLE_FORGET; ?></div>
                        <div class="form">
                            <br><br>
                            <div id="forgetfrm">
                                <p>
                                    <input type="email" name="forgetEmail" id="forgetEmail" required/>
                                    <label alt="البريد الإلكتروني" placeholder="البريد الإلكتروني"></label>
                                </p>
                                <p>
                                    <button type="submit" onclick="forgetPassword();">استردها</button>
                                </p>
                            </div>
                            <br><br>
                        </div>
                        <div class="footer"  style="border-radius: 0px 0px 0px 25px;"> هل لديك حساب؟<a href="javascript:;" onClick="popup('login')">تسجيل الدخول!</a></div>
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




</body>
</html>