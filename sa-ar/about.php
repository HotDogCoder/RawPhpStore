<?php require_once("header.php");?>
<?php 
if(isset($_GET['sendmail'])) {
    $email = $_GET['email'];
    $name = $_GET['name'];
    $phone = $_GET['phone'];
    $message = $_GET['message'];

	$data['email'] = $email;
	$data['name'] = $name;
	$data['phone'] = $phone;
	$data['message'] = $message;
	
	$endpoint = "/sendContactUsEmail";


    $json_data = curl_request(json_encode($data), $endpoint, $baseurl);

// 	if($json_data['code']=="202")
// 	{
        
// 		echo"<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'><img src='assets/img/nocart.png' alt='place order' style='width: 180px; margin-top:60px;''> <br/><div style='margin: 20px 0 0 0; padding: 0; font-size: 20px; font-weight: 300;'>Address is different from the restaurant location, Plase check your Delivery Address</div><br> <a href='#' onClick='window.location.reload(true)'>Reload</a>";
// 		die();
// 	}
		
// 	echo "<div style='text-align: center;font-size: 20px;padding: 40px 0 20px 0;'><img src='assets/img/nocart.png' alt='place order' style='width: 180px; margin-top:60px;''> <br/><div style='margin: 20px 0 0 0; padding: 0; font-size: 20px; font-weight: 300;'>Your order has been placed successfully</div>";
	
	echo $json_data;
	die();
}

?>
<div style="width: 100%; height: 7vh; background-color: #2cc900;  position: relative; display: none;" id="message_sent">
    <div style="margin: 0;  position: absolute;  top: 50%; left: 47%; -ms-transform: translateY(-50%);  transform: translateY(-50%); color: white;">
        Message Sent!
    </div>
</div>
<div class="contact_container" style="direction: ltr;">
    <div class="contact_row col75"  style="margin: auto; margin-top: 5%; margin-bottom: 5%; height: 700px;">
        <h1 style="font-size: 35px; margin: auto; text-align: center; color: #38215C; margin-bottom: 5%;">تواصل معنا</h1>
        <div class="col50 right">
            <h1 style="font-size: 40px; margin: auto; text-align: center; color: #38215C;">الأسئلة المتداولة</h1>
            <p class="accordion">ماهي آلية الطلب ؟</p>
            <div class="panel">بكل بساطة عن طريق تحميل تطبيق وي دروب من المتجر الخاص بهاتفك المحمول وأنشاء حساب بإضافة المعلومات الخاصة بك حيث أن التطبيق يقوم بربط موقعك لمساعدتك فب العثور علي ميع المطاعم التي تخدم منطقتك للإستمتاع بطلبكم حين الإنتهاء من خدمة التوصيل</div>
            
            <p class="accordion">كيف يتم تسليم الطلب ؟</p>
            <div class="panel">بمجرد إنشاء طلبك يقوم التطبيق بإرسال طلبكم آليا إلى المطعم للبدء بالإعداد ويتم تعيين مندوب للذهاب إلى المطعم وإستلام الطلب وإحضارة إلى موقع التسليم الخاص بك</div>
            
            <p class="accordion">هل يمكنني الطلب من أكثر من مطعم في وقت واحد ؟</p>
            <div class="panel">نعم يمكن ذلك يمكنكم إنشاء أكثر من طلب في نفس الوقت وسيتم تعيين سائق لكل طلب.</div>
            
            <p class="accordion">هل يمكنني إستلام طلبي شخصيا من المطعم ؟</p>
            <div class="panel">نعم يمكنك ذلك، وذلك بإنشاء الطلب عبر التطبيق وإختيار خدمة الإستلام من المطعم.</div>             
            
            <p class="accordion">هل يمكنني الدفع نقدا ؟</p>
            <div class="panel">نعم، الدفع عن طريق البطاقة أو الدفع النقدي متوفر لنتيح لكم بإفضل تجربة ممكن.</div>             
        </div>    
        <div class="col50 left">
            <p style="font-size: 20px; direction: rtl; float: left; margin-left: 5%;">للاستفسارات والمزيد من المعلومات، يرجى ترك رسالة وسنقوم بالتواصل معك في أقرب وقت ممكن</p>
            <div style="float: right" class="col80">
                <input class="col100" type="text" id="name" name="name" placeholder="اسمك" style="direction: rtl;"><br><br>
                <input class="col100" type="text" id="email" name="email" placeholder="بريدك الالكتروني" style="direction: rtl;"><br><br>
                <input class="col100" type="text" id="phone" name="phone" placeholder="رقم هاتفك" style="direction: rtl;"><br><br>
                <textarea class="col100" type="text" id="message" name="message" rows="5" placeholder="اكتب هنا" style="height: auto; resize: vertical; direction: rtl;"></textarea>
        		<div class="radiobtn" style="width: 100px; margin-right: auto; margin-top: 3%;">
        			<label for="orderNow" style="background: #E86942; border-radius: 10px; font-weight: bold; padding: 5px!important;" onclick="sendMail()">أرسل</label>
        		</div>
            </div>
        </div>
    </div>    
    <div id="location_row" class="col100" >
        <div class="col55 right" style="height: 100vh; margin-top: 10%;">
            <div id="location_page_map" style="width: 100%; height: 100vh;"></div>
        </div>        
        <div class="col45 left">
            <div class="col90 right" style="margin-top: 30%; direction: rtl;">
                <h1 style="font-size: 40px; color: #38215C;">موقعنا</h1>
                <p class="col80" style="font-size: 20px;">تواصل معنا من خلال موقعنا على الخريطة أو عبر قنوات التواصل الاجتماعي أو اتصل بنا مباشرة. </p>
                <div>
                    <img src="assets/img/new/location.svg" width="40" height="50" style="display: inline-block; margin-bottom: 2%;">
                    <div style="display: inline-block; margin-right: 25px; font-size: 20px;">
                        <span>15th St, Al Janubiyah</span><br>
                        <span>Al Khobar 34621</span><br>
                        <span>Kingdom of Saudi Arabia</span>
                    </div>
                </div>                
                <div style="margin-top: 8%; display: flex;">
                    <img src="assets/img/new/mobile.svg" width="30" height="35" style="display: inline-block;">
                    <div style="display: inline-block; margin-right: 20px; height: 35px; ">
                        <!--<span style="line-height: 35px; font-size: 20px;">920010932</span>-->
                        <span style="line-height: 35px; font-size: 20px;"></span>
                    </div>                    
                    <img src="assets/img/new/at-icon.svg" width="35" height="35" style="display: inline-block; margin-right: 50px;">
                    <div style="display: inline-block; margin-right: 20px; height: 35px; ">
                        <span style="line-height: 35px; font-size: 20px;">info@weydrop.com</span>
                    </div>
                </div>
                <div style="margin-top: 8%; margin-right: 15%;">
                    <a href="<?php echo FOOTER_ANDROID_URL; ?>" target="_blank"><img src="assets/img/gplaystore.svg" alt="play store"  style="display: inline-block;"/></a>
                    <a href="<?php echo FOOTER_iOS_URL; ?>" target="_blank"><img src="assets/img/appstore.svg" alt="apple store"  style="display: inline-block; margin-right: 50px;"/></a>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
/* Style the element that is used to open and close the accordion class */
p.accordion {
    background-color: white;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    text-align: right;
    border: none;
    outline: none;
    transition: 0.4s;
    margin-bottom:10px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

/* Add a background color to the accordion if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
p.accordion.active{
    background-color: white;
    border-bottom: 1px solid rgba(0,0,0,0);
}

/*p.accordion:hover {*/
/*    background-color: white;*/
/*    border-bottom: 1px solid rgba(0,0,0,0.1);*/
/*}*/

/* Unicode character for "plus" sign (+) */
p.accordion:after {
    /*content: '\2795'; */
    content: '+'; 
    font-size: 25px;
    color: #38215C;
    margin-left: 5px;
    margin-right: 10px;
    vertical-align: center;
}

/* Unicode character for "minus" sign (-) */
p.accordion.active:after {
    content: '+'; 
    font-size: 25px;
    color: #38215C;
    margin-left: 5px;
    margin-right: 10px;
    vertical-align: center;
}

/* Style the element that is used for the panel class */

div.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: 0.4s ease-in-out;
    opacity: 0;
    padding-bottom:10px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    text-align: right;
}

div.panel.show {
    opacity: 1;
    max-height: 500px; /* Whatever you like, as long as its more than the height of the content (on all screen sizes) */
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function(event) { 


var acc = document.getElementsByClassName("accordion");
var panel = document.getElementsByClassName('panel');

for (var i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
        var setClasses = !this.classList.contains('active');
        setClass(acc, 'active', 'remove');
        setClass(panel, 'show', 'remove');

        if (setClasses) {
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
        }
    }
}

function setClass(els, className, fnName) {
    for (var i = 0; i < els.length; i++) {
        els[i].classList[fnName](className);
    }
}

});
</script>
<script>
    // Initialize and add the map
    function initMap() {
      // The location of Uluru
      var location = {lat: 26.278327, lng: 50.199621};
      // The map, centered at Uluru
      var map = new google.maps.Map(
          document.getElementById('location_page_map'), {zoom: 15, center: location});
      // The marker, positioned at Uluru
      var marker = new google.maps.Marker({position: location, map: map});
    }
    
    function sendMail() {
	
	var name =document.getElementById("name").value;
	var email =document.getElementById("email").value;
	var phone =document.getElementById("phone").value;
	var message =document.getElementById("message").value;

	//var data = $(ordernow).serialize();
	$('#preloader').css("display", "block");
	jQuery.ajax({
		type: "POST",
		url: "about.php?sendmail=ok&name="+name+"&email="+email+"&phone="+phone+"&message="+message,
		data: "data",
		dataType: "text",
		success: function(response){
		    $('#message_sent').show();
			$('#preloader').css("display", "none");
		}
	});
}
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapgoogleapi; ?>&callback=initMap">
</script>


<?php require_once("footer.php"); ?>