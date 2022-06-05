<?php
require_once("config.php");
require_once("functions.php");
$pgname = explode(".php", basename($_SERVER['PHP_SELF']));
if($pgname[0] == "index") 
{
    $pagename="home";
} 
else 
{
    $pagename= $pgname[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="rtl" xmlns="http://www.w3.org/1999/xhtml" <?php if(isset($_GET['city'])){echo "style=\"height: 100%; min-height: 100vh!important; margin: 0; padding: 0\"";} ?>>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    
    <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <title><?php echo HEADER_TITLE_TAG; ?> </title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/font-awesome.css" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@500&display=swap" rel="stylesheet"> 
    <link rel="shortcut icon" href="assets/img/new/fav.png">
    <script src="assets/js/jquery-1.12.4.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/jquery.validate.min_dead.js"></script>
    <script src="assets/js/jquery.inputmask.bundle.js"></script>
    <script src="assets/js/inputmask.numeric.extensions_dead.js"></script>
    <script src="assets/js/phone_dead.js"></script>
    <script src="assets/js/pagination.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapgoogleapi ?>&libraries=places"></script>
    <script src="assets/js/locationpicker.jquery.js"></script>
    <link rel="stylesheet" href="assets/css/styles.min.css" />
    <script src="assets/css/rangeslider-js.min.js"></script>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="<?php echo $googleappid;?>">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>		
		var locationStored;
	
        var options = {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        }	
        
        function getLocation(){			
            navigator.geolocation.getCurrentPosition(success, error, options);
        }
	
        function success(pos){
        var currentLoc = pos.coords;
		
		console.log('geolocation success! ' + currentLoc.latitude + ',' + currentLoc.longitude);
		
		$('#address_lat_geo').val(currentLoc.latitude);
		$('#address_long_geo').val(currentLoc.longitude);
		locationStored = currentLoc;
		
		
		$('#us2').locationpicker({
			location: { latitude: currentLoc.latitude, longitude: currentLoc.longitude },
			radius: 5,
			scrollwheel: true,
		    enableAutocomplete: true,
			inputBinding: {
				latitudeInput: $('#address_lat'),
				longitudeInput: $('#address_long'),
				locationNameInput: $('#address_autocomplete')
			},			
			onchanged: function (currentLocation, radius, isMarkerDropped) {
				var addressComponents = $(this).locationpicker('map').location.addressComponents;
				//updateControls(addressComponents);
				updateAddressBoxes(currentLocation.latitude, currentLocation.longitude);
			},
			oninitialized: function(component) {
				var addressComponents = $(component).locationpicker('map').location.addressComponents;
				updateAddressBoxes(currentLoc.latitude, currentLoc.longitude);
				document.getElementById('address_autocomplete').style.display = 'block';
				document.getElementById('geo_button').style.display = 'block';
			}			
		});		 
        }
		
		function updateAddressBoxes(latitude, longitude){
		var mapskey = <?php echo "'".$mapgoogleapi."';"; ?>
		
		var mapsApiURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&key='+mapskey;

		fetch(mapsApiURL)
		.then((response) => response.json())
		.then((data) => {
			$('#address_house').val(getApartement(data));
			$('#address_street').val(getStreet(data));
			$('#address_city').val(getCity(data));
		})
		.catch( (error) =>
			console.log(error)
		);
	
		}

		function getApartement(data){
		    var i;
			for(i=0; i < data['results'][0]['address_components'].length; i++){
				if(data['results'][0]['address_components'][i]['types'].includes('street_number')){
					return data['results'][0]['address_components'][i]['long_name'];
				}				    
			}
			
			return '';
		}
		
		function getStreet(data){
		    var street = '';
		    var neigh = '';
		    var i, j;
			for(i=0; i < data['results'][0]['address_components'].length; i++){
				if(data['results'][0]['address_components'][i]['types'].includes('route')){
					street = data['results'][0]['address_components'][i]['long_name'];
				}				    
			}
			
			
			for(j=0; j < data['results'].length; j++){
    			for(i=0; i < data['results'][j]['address_components'].length; i++){
    				if(data['results'][j]['address_components'][i]['types'].includes('sublocality')){
    					neigh = data['results'][j]['address_components'][i]['long_name'];
    				}				    
    			}
			}
			
			if(street != '' && neigh != ''){
			    return street+', '+neigh;
			}
			
			if(street != '' && neigh == ''){
			    return street;
			}
			
			if(street == '' && neigh != ''){
			    return neigh;
			}

			return '';
		}
		
		function getCity(data){
		    var i;
			for(i=0; i < data['results'][0]['address_components'].length; i++){
				if(data['results'][0]['address_components'][i]['types'].includes('locality')){
					return data['results'][0]['address_components'][i]['long_name'];
				}				    
			}
			
			return '';
		}

        function error(err){
            console.warn(`ERROR(${err.code}): ${err.message}`);
			$('#us2').locationpicker({
				location: { latitude: 26.432040, longitude: 50.077419 },
				radius: 5,
				scrollwheel: true,
			    enableAutocomplete: true,
				inputBinding: {
					latitudeInput: $('#address_lat'),
					longitudeInput: $('#address_long'),
					locationNameInput: $('#address_autocomplete')
				},
				onchanged: function (currentLocation, radius, isMarkerDropped) {
					var addressComponents = $(this).locationpicker('map').location.addressComponents;
					updateAddressBoxes(currentLocation.latitude, currentLocation.longitude);
				},			
				oninitialized: function(component) {
					var addressComponents = $(component).locationpicker('map').location.addressComponents;
					updateAddressBoxes(26.432040, 50.077419);
					document.getElementById('address_autocomplete').style.display = 'block';
					document.getElementById('geo_button').style.display = 'block';
				}			
			});					
        }
		
		function showMenuSection(id){
			var menuList = document.getElementById('menuListRight').children; 
			for(i=0; i < menuList.length; i++){
				menuList[i].style.display = "none";
				if(menuList[i].getAttribute('id') == id){
					menuList[i].style.display = "inherit";
				}
			}
		}

		function highlightSection(id){
			var menuList = document.getElementById('menuListLeft').children;
			for(i=1; i < menuList.length; i++){
				menuList[i].classList.remove("highlightMenu");
				menuList[i].style.color = "grey";
				if(menuList[i].getAttribute('id') == id){
					menuList[i].classList.add("highlightMenu");
					menuList[i].style.color = "black";
				}
			}
		}
		
		window.addEventListener("DOMContentLoaded", selectFirst, false);
		
		function selectFirst(){
			showMenuSectionFirst();
			highlightSectionFirst();
		}
		
		function showMenuSectionFirst(){
			var menuList = document.getElementById('menuListRight').children;
			for(i=0; i < menuList.length; i++){
				menuList[i].style.display = "none";
			}
			menuList[0].style.display = "inherit";
		}
		
		function highlightSectionFirst(){
			var menuList = document.getElementById('menuListLeft').children;
			for(i=1; i < menuList.length; i++){
				menuList[i].classList.remove("highlightMenu");		
			}
			menuList[1].classList.add("highlightMenu");		
			menuList[1].style.color = "black";;		
		}
		
		function orderNow(){
			var ordertype = document.getElementById("ordertype").value;
			
			if(ordertype == "0"){
				orderformat();
			} else {
				if(document.getElementById("addrsid").value == '' || document.getElementById("addrsid").value == 0){
					popup('ordernow'); 
					getLocation();
				} else {
					orderformat();
				}
			}
		}
		
		function deliverHere(){
			if(addAddress() == 1){
				//setTimeout(orderformat(), 2500);
				setTimeout(function() {
					//orderformat();
					var apart = document.getElementById("address_house").value;
					var street = document.getElementById("address_street").value;
					var city = document.getElementById("address_city").value;
					
					var address = apart + ", " + street + ", " + city;
					
					document.getElementById('address_cart').innerHTML = address;
					document.getElementById("address_cart_container").style.display = "block";
					jQuery( '#ordernowpopup' ).hide();
					calculateDeliveryFee();
				}, 500);				
			} else {
				alert('error adding address');
			}
		}
		
		function addAddress(){
			var apartment = document.getElementById("address_house").value;
			var street = document.getElementById("address_street").value;
			var city = document.getElementById("address_city").value;
			var state = 'Eastern Province';
			var zip = '111';
			var country = 'Saudi Arabia';
			var instruction = 'x';
			var latitude = document.getElementById("address_lat").value;
			var longitude = document.getElementById("address_long").value;
			
			if(apartment == '' || street == '' || city == ''){
				return 0;
			}
			
			var input = {
				aprt: apartment,
				str: street,
				cty: city,
				stt: state,
				zp: zip,
				cntry: country,
				ins: instruction,
				lat: latitude,
				lng: longitude
			}
			
			console.log(input);
			
			$.post("dashboard.php?p=address&action=add_address_new",
			input, 
			function(data, status){
				console.log("addAddress() Status: " + status);
				var res = data.split("<");
				console.log(data);
				if(res[0] == 'error'){
					var success = false;
				} else {
					console.log('addrsid: '+res[0]);
					$('#addrsid').val(res[0]);
					var success = true;
				}
			}
			);
			
			if(success){
				return 1;
			}
			return 0;
		}
		
		function geoLocateClick(){
			$('#us2').locationpicker({
				location: { latitude: locationStored.latitude, longitude: locationStored.longitude },
				radius: 0
			});					
			
		}
		
		<?php
		if(isset($_GET['id'])){ ?>
		
		function calculateDeliveryFee(){
		    var userlat = $('#address_lat').val();
		    var userlong = $('#address_long').val();
            var id = <?php echo $_GET['id'].';'; ?>
            var fee = $('#delivery_fee_per_km').val();
		    
            var url = "https://www.weydrop.com/mobileapp_api/publicSite/calculateDeliveryFee";
            var data = {"lat": userlat, "long": userlong, "id": id, "fee": fee};
            
            console.log(data);
            
    		fetch(url, {
	            method: 'POST', // *GET, POST, PUT, DELETE, etc.
                headers: {
                        'Content-Type': 'application/json'
                    
                },
                body: JSON.stringify(data)
    		})
    		.then((response) => response.json())
    		.then((data) => {
                $('#delivery_fee').val(data);
                $('#deliveryfeeinvoice').text(data);
                totalCalculation();
    		})
    		.catch( (error) =>
    			console.log(error)
    		);

		}
		
		<?php } ?>

		
        var searchTerm;
        var selectedSpecialty;

		function search(){
		    searchTerm = $('#header-search').val();
		    
		    if(searchTerm === ''){
                highlightSpecialty('All');
		    } else {
    		    $('#restaurants_list').find('a').each(function(){
    		        if($(this).is(":visible")){
                        if($(this).attr('name').toLowerCase().includes(searchTerm)){
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
    		        }
                });    
		    }
		}

        function filterMinimumOrder(amount){
            if(searchTerm === ''){
                
            } else {
                search();
            }
            filterSpecialty(selectedSpecialty);
            
		    $('#restaurants_list').find('a').each(function(){
		        var minimum = $(this).data('minimum');
		        
		        if($(this).is(":visible")){
                    if(parseInt(minimum) <= parseInt(amount)){
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
		        }
            });
        }
        
        function changeMinimumOrder(amount){
            $('#minimum_order').text(amount);
        }

		function highlightSpecialty(id){
			var menuList = document.getElementById('menuListLeft').children;
			
			for(i=1; i < menuList.length; i++){
				if(menuList[i].getAttribute('id') == id){
		            menuList[i].classList.add("highlightMenu");
		            menuList[i].style.color = 'black';
				} else {
				    menuList[i].classList.remove("highlightMenu");
		            menuList[i].style.color = 'grey';
				}
			}
			
			$("#header-search").val("");
			
            selectedSpecialty = id;
			filterSpecialty(id);
		}
		
        function filterSpecialty(id){
            if(id == "All"){
                $('#restaurants_list').find('a').each(function(){
                    $(this).show();
                }); 
            } else {
       		    $('#restaurants_list').find('a').each(function(){
                    if($(this).data('specialty').includes(id)){
                        $(this).show();
                    } else  {
                        $(this).hide();
                    }
                });
            }
        }
        
        function sortByDistance(){
            navigator.geolocation.getCurrentPosition(success_sort, error_sort, options);
        }
        
        function success_sort(pos){
        var currentLoc = pos.coords;
        
        setUrlAddress(currentLoc.latitude, currentLoc.longitude)
        }
        
		function setUrlAddress(latitude, longitude){
    		var mapskey = <?php echo "'".$mapgoogleapi."';"; ?>
    		
    		var mapsApiURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&key='+mapskey;
    		
    		fetch(mapsApiURL)
    		.then((response) => response.json())
    		.then((data) => {
    			city = getCity(data);
                var url = "https://www.weydrop.com/sa-ar/search.php?address="+city+",+Saudi Arabia"+"&lat="+latitude+"&lng="+longitude+"&city="+city;
    		    window.location.replace(url);
    		})
    		.catch( (error) =>
    			console.log(error)
    		);
		}
		
        function error_sort(err){
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }
    </script>
</head>

<body <?php if($_GET['p'] == "address"){echo "onload=\"getLocation()\"";} ?> class="<?php echo $pagename; ?>" <?php if(isset($_GET['city'])){echo "style=\"height: 100%; min-height: 100vh!important; margin: 0; padding: 0\"";} ?>>
    
    <div id="preloader" align="center">
        <div id="loading">
            <img src="assets/img/loader.gif" alt="Loading.." />
        </div>
    </div>

<?php 
    if (isset($_SESSION['id'])) 
    { 
        ?>
            <div id="mySidenav" class="sidenav">
                <ul>
                    <a>
                        <span class="myacc">My Account</span> 
                        <span class="nameontop"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="dashboard.php?p=order">طلباتي <span class="blok">عرض الطلب وتفاصيله</span></a></li>
                        <li><a href="dashboard.php?p=account">حسابي <span class="blok">عرض أو تحرير تفاصيل حسابك</span></a></li>
                        <li><a href="dashboard.php?p=changepassword">غير كلمة السر <span class="blok">قم بتغيير كلمة مرور حسابك</span></a></li>
                        <li><a href="dashboard.php?p=payment">طرق الدفع <span class="blok">عرض أو تحرير طرق الدفع الخاصة بك</span></a></li>
                        <li class="logout"><a href="index.php?log=out">تسجيل خروج</a></li>    
                    </ul>
                </ul>
            </div>
            <!-- toggle menu -->
        <?php 
    } 
    else 
    { 
        ?>
            <div id="mySidenav" class="sidenav">
                <ul>
                    <li>
                        <span style="color: #38215C" onClick="popup('login')">تسجيل الدخول / إشترك</span>
                    </li>
                </ul>
            </div>
        <?php 
    }
?>
<header class="siteheader" style="background: <?php if(isset($_GET["id"]) || isset($_GET["city"])){echo "#F4C951";} else {echo "white";}?>; background-image: url(assets/img/new/<?php if(isset($_GET["id"]) || isset($_GET["city"])){echo "topbar_white.png";} else {echo "topbar_yellow.png";}?>); background-size: contain; background-repeat:no-repeat; background-position: right;">
    <div class="wdth" style="margin-left: 2%; margin-right: 2%;">
        <div class="right" style="padding-right: 0; margin-left: 0;">
            <a href="index.php"><img src="<?php echo HEADER_LOGO; ?>" alt="logo" style="height: 30px;"/></a>
        </div>
        <div class="left navbar" style="padding-left: 5%;">
           <span class="menu-icon opensidemenu" id="opensidemenu" onClick="openNav()">
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </span>
            <ul class="nav-menu dnav">
                <?php 
                    if (isset($_SESSION['id'])) 
                    { 
                        if (isset($_GET['p'])) 
                        {
                            $active="";
                            if ($_GET['p'] == "order") 
                            {
                                $order='class="active"';
                            }
                            else
                            if ($_GET['p'] == "account") 
                            {
                                $account='class="active"';
                            }
                            else
                            if ($_GET['p'] == "changepassword") 
                            {
                                $changepassword='class="active"';
                            }
                            else
                            if ($_GET['p'] == "address") 
                            {
                                $address='class="active"';
                            }
                            else
                            if ($_GET['p'] == "payment") 
                            {
                                $payment='class="active"';
                            }
                            
                        }
                        
                        ?>
                            <li class="lastmenu"><a><span class="myacc" style="color: #38215C; display:none;">Account</span>
                                <span class="nameontop" style="color: #38215C; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['name']); ?> <i class="fa fa-caret-down"></i></span></a>
                                <ul class="submenu">
                                    <li <?php echo $order;?>><a href="dashboard.php?p=order">طلباتي <span class="blok">عرض الطلب وتفاصيله</span></a></li>
                                    <li <?php echo $account;?>><a href="dashboard.php?p=account">حسابي<span class="blok">عرض أو تحرير تفاصيل حسابك</span></a></li>
                                    <li <?php echo $changepassword;?>><a href="dashboard.php?p=changepassword">غير كلمة السر <span class="blok">قم بتغيير كلمة مرور حسابك</span></a></li>
                                    
                                    <li <?php echo $payment;?>><a href="dashboard.php?p=payment">طرق الدفع <span class="blok">عرض أو تحرير طرق الدفع الخاصة بك</span></a></li>
                                    <li class="logout"><a href="index.php?log=out">تسجيل خروج</a></li>
                                </ul>
                            </li>
                        <?php 
                    } 
                    else 
                    { 
                        ?>
                        <li style="position: absolute; top: 35%; left: 3%; width: 180px; font-size: 13px;">
                            <span style="color: #38215C; cursor: pointer;" onClick="popup('login')">
                                تسجيل الدخول / 
                            </span>                            
                            <span style="color: #38215C; cursor: pointer;" onClick="location.replace('https://www.weydrop.com/sa-en/')">
                                English               
                            </span>
                        </li>
                        <?php 
                    } 
                ?>
            </ul>
        </div>
        <?php
         if (isset($_SESSION['id'])) 
                { ?>
        <span class="left" style="margin: 0.7% 0 0 2%; color: #38215C; cursor: pointer;" onClick="location.replace('https://www.weydrop.com/sa-en/')">English</span>
        <?php } ?>
        <div class="clear"></div>
    </div>
</header>
