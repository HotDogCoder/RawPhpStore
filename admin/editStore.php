<style>

.time_slot li.selected {
    background-color: #007bff;
    color: #fff;
}
.time_slot li {
    display: inline-block;
    background-color: #f2f2f2;
    padding: 5px 10px;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
}

.time_slot li.selected {
    background-color: #E77830;
    color: #fff;
}
</style>
<?php
if (isset($_SESSION[PRE_FIX.'sessionTokon'])) {
    if (isset($_GET['action'])) {

         if ($_GET['action'] == "addlogoRestaurant") 
         {
			$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		    $url = $baseurl . 'addRestaurantImage';
			$image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
			$image = base64_encode($image_base);
			
			$data = array(
                "image" => array("file_data" => $image),
                'id'=>$id,
            );
            
            $json_data = @curl_request($data, $url);

            $json_return = $json_data['msg'];
            $json_code = $json_data['code'];



            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=success'</script>";
            }


        }
         
        if ($_GET['action'] == "updateDeliveryTime") 
        {
            $booking_available =  $_POST['booking_available'];
            $restaurant_id     = $_GET['id'];
            $bookingUrl        = $baseurl . 'bookingStatusUpdate';

            $arrayB = array(
                'restaurant_id'     => $restaurant_id,
                'booking_available'        => $booking_available?$booking_available:0
            );
            
            $jsonBooking = @curl_request($arrayB, $bookingUrl);
           
            for ($j = 0; $j < 7; $j++) {
                $delivery_book_id[]         = $_POST['booking_time_day_id'][$j];
                $booking_day_status[]       = $_POST['booking_day_status_'.$j];
                $booking_time_ids[]         = $_POST['booking_time_ids'][$j];
    
            }
            
// 			$delivery_book_id       = htmlspecialchars($_POST['booking_time_day_id'], ENT_QUOTES);
		    
// 			$day_status             = htmlspecialchars($_POST['booking_day_status'], ENT_QUOTES);
//             $booking_time_id        = htmlspecialchars($_POST['booking_time_ids'], ENT_QUOTES);

		    $url                    = $baseurl . 'updateDeliveryTime';
            
            $i = 0;
            foreach($delivery_book_id as $booking_id)
            {
                $delivery_id           = $booking_id;
                $day_status                 = $booking_day_status[$i]?$booking_day_status[$i]:'0';
                $booking_time_id            = $booking_time_ids[$i];
                
                
                $data = array(
                    "delivery_book_id"  => $delivery_id,
                    'restaurant_id'     => $restaurant_id,
                    'day_status'        => $day_status,
                    'booking_time_id'   => $booking_time_id
                );
                
                $json_data = @curl_request($data, $url);
                // echo '<pre>';
                // print_r($json_data);
                // echo '</pre>';
                // exit;
                $i++;
            }

// 			$data = array(
//                 "delivery_book_id"  => $delivery_book_id,
//                 'restaurant_id'     => $restaurant_id,
//                 'day_status'        => $booking_day_status,
//                 'booking_time_id'   => $booking_time_ids
//             );
            
//             $json_data = @curl_request($data, $url);

            $json_code      = $json_data['code'];
            

            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$restaurant_id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$restaurant_id."&action=success'</script>";
            }


        }
        
        if ($_GET['action'] == "addRestaurantCoverImage") 
         {
			$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		    $url = $baseurl . 'addRestaurantCoverImage';
			$image_base = file_get_contents($_FILES['cover_image']['tmp_name']);
			$image = base64_encode($image_base);
			$data = array(
                "cover_image" => array("file_data" => $image),
                'id'=>$id,
            );
            
            
            $json_data = @curl_request($data, $url);

            $json_return = $json_data['msg'];
            $json_code = $json_data['code'];



            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=success'</script>";
            }


        }



        if ($_GET['action'] == "addRestaurantcatagoryImage") 
         {
            $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
            $url = $baseurl . 'addRestaurantcatagoryImage';
            $image_base = file_get_contents($_FILES['catagory_image']['tmp_name']);
            $image = base64_encode($image_base);
            $data = array(
                "catagory_image" => array("file_data" => $image),
                'id'=>$id,
            );
            
            
            $json_data = @curl_request($data, $url);
            // print_r($json_data);die();
            $json_return = $json_data['msg'];
            $json_code = $json_data['code'];



            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=success'</script>";
            }


        }
       
        if ($_GET['action'] == "resturnettiming") {
            $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
            for ($i = 0; $i < 7; $i++) {
                $day[$i] = $_POST['day'][$i];
                $opening_time[$i] = $_POST['opening_time'][$i];
                $closing_time[$i] = $_POST['closing_time'][$i];


                $restaurant_timings_details[] = array('day' => $day[$i],'opening_time' => $opening_time[$i], 'closing_time' => $closing_time[$i]);
            }


            $url = $baseurl . 'addRestaurantTiming';

            $data = array(
                "restaurant_timing" => $restaurant_timings_details,
                'id'=>$id,
            );


            $json_data = @curl_request($data, $url);

            $json_return = $json_data['msg'];
            $json_code = $json_data['code'];



            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=success'</script>";
            }


        }
          if ($_GET['action'] == "addtoresturentdata") {
            
            $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
            $searchReplaceArray = array(
                '(' => '',
                ')' => '',
                '-' => '',
                '_' => '',
                ' ' => ''
            );
            $name = $_POST['name'];
            $currency_id = $_POST['currency_id'];//
           // $speciality = $_POST['Catagory'];//
            $categories = $_POST['category_id'];//
            $min_order_price = $_POST['min_order_price'];
            $delivery_free_range = $_POST['delivery_free_range'];
            $preparation_time = $_POST['preparation_time'];
            $tax_free = $_POST['tax_free'];
            $tax_id = $_POST['tax_id'];//
            $slogan = $_POST['slogan'];
            $about = $_POST['about'];
            $phone = str_replace(array_keys($searchReplaceArray), array_values($searchReplaceArray), $_POST['phone']);
            $timezone ="-05:00"; //$_POST['timezone'];
            $state = $_POST['state'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $zip = $_POST['zip'];
            $lat = $_POST['lat'];
            $long = $_POST['long'];
            $admin_commission=$_POST['admin_commission'];
           
            $data = array(
                'id'=>$id,
                "name" => $name,
                "slogan" => $slogan,
                "about" => $about,
                "min_order_price" => $min_order_price,//
                "delivery_free_range" => $delivery_free_range,//
                "tax_free" => $tax_free,//
                "phone" => $phone,
                "timezone" => $timezone,
                "city" => $city,
                "state" => $state,
                "country" => $country,
                "notes"=> "",
                "zip" => $zip,
                "lat" => $lat,
                "long" => $long,
                "currency_id" => $currency_id,//
                "tax_id" => $tax_id,//
                "type" => "store",
                //"Catagory" => $Catagory,//
                "categories" => $categories,
                "admin_commission" => $admin_commission,
                "preparation_time" => $preparation_time,//
            );

            $url = $baseurl . 'addrestaurant';
            $json_data = @curl_request($data, $url);
            $json_return = $json_data['msg'];
            $json_code = $json_data['code'];

            if ($json_code !== 200) {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=editStore&id=".$id."&action=success'</script>";
            }
        }
        if($_GET['action'] == "deletecoupon" && !empty($_GET['coupon_id'])) {
        		$user_id = $_GET['id'];
        		$coupon_id = $_GET['coupon_id'];
        
        		$data = array(
        			"user_id" => $user_id,
        			"coupon_id" => $coupon_id
        		);
        		
	            $url = $baseurl . 'deleteRestaurantCoupon';
                $json_data = @curl_request($data, $url);
            
                $json_return = $json_data['msg'];
                $json_code = $json_data['code'];

        		if($json_data['code'] !== 200){
        			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
        			@header("Location: dashboard.php?p=editStore&id=".$user_id."&action=error");
        				echo "<script>window.location='dashboard.php?p=editStore&id=".$user_id."&action=error'</script>";
        
        		} else {
        			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
        			@header("Location: dashboard.php?p=editStore&id=".$user_id."&action=success");
        				echo "<script>window.location='dashboard.php?p=editStore&id=".$user_id."&action=success'</script>";
        		}
        
            }
            if($_GET['action'] == "addCoupon") {
            	$coupon_code = $_POST['coupon_code'];
            	$user_id = $_POST['user_id'];
            	$discount = $_POST['discount'];
            	$expire_date = $_POST['expire_date'];
            	$limit =  $_POST['limit'];
            	$type =  $_POST['type'];
            	
                $url=$baseurl . 'addRestaurantCoupon';
                
            	$data = array(
            		"coupon_code" => $coupon_code,
            		"user_id" => $user_id,
            		"discount" => $discount,
            		"expire_date" => $expire_date,
            		"limit_users" => $limit,
            		"type"=>$type
            	);
                
                $json_return=@curl_request($data,$url);
                
                // print_r($json_return);
        		if($json_data['code'] !== 200){
        			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
        			@header("Location: dashboard.php?p=editStore&id=".$user_id."&action=error");
        				echo "<script>window.location='dashboard.php?p=editStore&id=".$user_id."&action=error'</script>";
        
        		} else {
        			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
        			@header("Location: dashboard.php?p=editStore&id=".$user_id."&action=success");
        				echo "<script>window.location='dashboard.php?p=editStore&id=".$user_id."&action=success'</script>";
        		}
        
            }
        

    }


     $b_url=$baseurl . 'bookingTimeList';
    $b_data = [];
    
    $bookingTime=@curl_request($b_data,$b_url);
    if ($bookingTime['code'] == 200) {
        $allBookingTime = $bookingTime['msg'];
    }
    
    $id=$_GET['id'];

    $url=$baseurl . 'showRestaurantDetail';
    $data = array(
        "id" => $id
    );

    $json_data=@curl_request($data,$url);
    // print_r($json_data);die();

    if ($json_data['code'] == 200) {
        $alldata = $json_data['msg'];
    }


    ?>

    <div class="qr-content">
    <div class="qr-page-content">
        <div class="qr-page zeropadding">
            <div class="qr-content-area">
                <div class="qr-row">
                    <div class="qr-el">

                        <div class="page-title">
                            <h2>Edit Store</h2>
                            <div class="head-area">
                            </div>
                        </div>


                        <!--start of datatable here-->
<?php
    $url=$baseurl . 'showCountries';

    $data = "";

    $country=@curl_request($data,$url);

    //get all currency
    $url=$baseurl . 'showCurrencies';
    $data = [];

    $currency=@curl_request($data,$url);

    //get all tax
    $url=$baseurl . 'showAllTaxes';
    $data = [];

    $tax=@curl_request($data,$url);

    $url=$baseurl . 'showCategoriesStore';
    $data = [];

    $category=@curl_request($data,$url);

     $url=$baseurl . 'getRestaurantCategories';
    $data = ['restaurant_id' => $id];

    $rest_categories=@curl_request($data,$url);
    $restcategories = [];
    foreach($rest_categories['msg'] as $cat)
    {
        array_push($restcategories, $cat['RestaurantCategory']['category_id']);
    }

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center"></h2>

        <div>

            <form id="logoform" action="dashboard.php?p=editStore&id=<?php echo $id ?>&action=addlogoRestaurant" method="post" enctype="multipart/form-data">
                <div class="qr-el qr-el-3" style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                    <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                        <img src="<?php echo $imagebaseurl . $alldata[0]['Restaurant']['image']; ?>" style="width: 38px; opacity: 1">
                        <div class="uploadText" style="font-size: 12px;">
                            <span style="color:#F69518;">Upload Logo</span><br>
                            Size 150x150
                        </div>
                    </label>
                    <input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" >
                    <input name="upload_image" class="hidden" id="uploadFile" type="file" onchange="return Upload_image_desktop()" required="required">
                </div>
            </form>
            
            <form id="coverform" action="dashboard.php?p=editStore&id=<?php echo $id ?>&action=addRestaurantCoverImage" method="post" enctype="multipart/form-data">
            <div class="qr-el qr-el-3" style="min-height: auto; float:right; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFileCover" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="<?php echo $imagebaseurl . $alldata[0]['Restaurant']['cover_image']; ?>" style="width: 38px; opacity: 1">
                    <div class="uploadText" style="font-size: 12px;">
                        <span style="color:#F69518;">Upload Cover</span><br>
                        Size no more than 900x270
                    </div>
                </label>
                <input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" >
                <input name="cover_image" class="hidden" id="uploadFileCover" type="file" onchange="return Upload_image_desktopCover()" required="required">

            </div>
            
            </form>

            <!--<form id="catagoryform" action="dashboard.php?p=editStore&id=<?php echo $id ?>&action=addRestaurantcatagoryImage" method="post" enctype="multipart/form-data">
            <div class="qr-el qr-el-3" style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFileCatagoryImage" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    
                    <img src="<?php echo $imagebaseurl . $alldata[0]['Restaurant']['catagory_image']; ?>" style="width: 38px; opacity: 1">
                    <div class="uploadText" style="font-size: 12px;">
                        <span style="color:#F69518;">Upload Catagory</span><br>
                        Size no more than 256x256
                    </div>
                </label>
                <input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" >
                <input name="catagory_image" class="hidden" id="uploadFileCatagoryImage" type="file" onchange="return Upload_image_catagoryImage()" required="required">

            </div>
            
            </form>-->
            <div style="clear:both;"></div>
            
            <form id="logoform" accept-charset="utf-8" action="dashboard.php?p=editStore&id=<?php echo $id ?>&action=addtoresturentdata" method="post" enctype="multipart/form-data">

            <div class="half_width float_left">
                <label class="field_title">Store Name</label>
                <input name="name" value="<?php echo $alldata[0]['Restaurant']['name'] ?>" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Slogan</label>
                <input name="slogan" value="<?php echo $alldata[0]['Restaurant']['slogan'] ?>" type="text" required>
            </div>

            <div class="full_width clear_both">
                <label class="field_title">About</label>
                <textarea name="about"  type="text" required><?php echo $alldata[0]['Restaurant']['about'] ?></textarea>
            </div>

           <!--  <div class="half_width float_left">
                <label class="field_title">Category</label>
                <input name="speciality"  value="<?php echo $alldata[0]['Restaurant']['speciality'] ?>" type="text" required>
            </div> -->

            <div class="half_width float_left">
                <label class="field_title">Categories</label>
                <select name="category_id[]" class="form-control" required="" multiple="multiple" style="height: 110px;">
                    <option value="">Select Categories</option>

                    <?php  foreach( $category['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Category']['id']; ?>" <?php if (in_array($val['Category']['id'],$restcategories)){ ?>selected="selected"<?php } ?>><?php echo $val['Category']['category']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_right" style="height: 132px;">
                <label class="field_title">Phone</label>
                <input name="phone" value="<?php echo $alldata[0]['Restaurant']['phone'] ?>" type="text" required>
            </div>

            <div class="full_width">
               <label class="field_title">Admin Commission (%)</label>
                <input name="admin_commission" value="<?php echo $alldata[0]['Restaurant']['admin_commission'] ?>" type="text" required>
            </div>
            
            <div class="full_width">
                <label class="field_title">Country</label>
                <select name="country"  class="form-control" required="">
                    <option value="">Select Country</option>
                    <?php foreach( $country['msg']['countries'] as $str => $val ): ?>

                        <option <?php if($alldata[0]['RestaurantLocation']['country'] == $val['Currency']['country'] ){echo "selected='selected'";} ?> value="<?php echo $val['Currency']['country']; ?>"><?php echo $val['Currency']['country']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>
            <div class="half_width float_right" style="display: none;">
                <label class="field_title">State</label>
                <select name="state"   class="form-control">
                    <option value="">Select State</option>
                    
                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option <?php if($alldata[0]['RestaurantLocation']['state'] == $val['Tax']['state']){echo "selected='selected'";} ?> value="<?php echo $val['Tax']['state']; ?>"><?php echo $val['Tax']['state']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

			<div class="half_width float_left">
                <label class="field_title">City</label>
                <select name="city" class="form-control" required="">
                    <option value="">Select City</option>
					<option <?php if($alldata[0]['RestaurantLocation']['city'] == "Dammam" ){echo "selected='selected'";} ?> value="Dammam">Dammam</option>
					<option <?php if($alldata[0]['RestaurantLocation']['city'] == "Dhahran" ){echo "selected='selected'";} ?> value="Dhahran">Dhahran</option>
					<option <?php if($alldata[0]['RestaurantLocation']['city'] == "Khobar" ){echo "selected='selected'";} ?> value="Khobar">Khobar</option>
               </select>
            </div>
            
            <input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" >
            <div class="half_width float_right">
                <label class="field_title">Tax</label>
                <select name="tax_id" class="form-control" required="">
                    <option value="">Select Tax</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option <?php if($alldata[0]['Tax']['id'] == $val['Tax']['id'] ){echo "selected='selected'";} ?> value="<?php echo $val['Tax']['id']; ?>"><?php echo $val['Tax']['tax']; ?> %</option>

                    <?php endforeach; ?>
               </select>
            </div>
            
            <div class="half_width float_left">
                <label class="field_title">Zip Code</label>
                <input name="zip" value="<?php echo $alldata[0]['RestaurantLocation']['zip'] ?>" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Locatino Lat</label>
                <input name="lat" value="<?php echo $alldata[0]['RestaurantLocation']['lat'] ?>" type="text" required>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Location Long</label>
                <input name="long" value="<?php echo $alldata[0]['RestaurantLocation']['long'] ?>" type="text" required>
            </div>
            
            <div class="half_width float_right">
                <label class="field_title">Currency</label>
                <select name="currency_id" class="form-control" required="">
                    <option value="<?php echo $currency['msg'][0]['Currency']['id'] ?>"><?php echo $currency['msg'][0]['Currency']['currency'] ?></option>

                </select>

            </div>
            <div class="half_width float_left">
                <label class="field_title">Minimum Order Price</label>
                <select name="min_order_price" class="form-control" required="">
                    <option value="">Select Amount</option>

                    <?php

                        for($i = 1; $i<=999; $i++) {
                            ?>
                                <option <?php if($alldata[0]['Restaurant']['min_order_price'] == $i ){echo "selected='selected'";} ?> value="<?php echo $i; ?>"><?php echo $i.' '; echo $currency['msg'][0]['Currency']['symbol']; ?></option>
                            <?php
                        }

                    ?>

               </select>

            </div>



            <div class="half_width float_right">
                <label class="field_title">Free Delivery Range</label>
                <select name="delivery_free_range"  class="form-control" required="">
                    <option value="">Select KM Range</option>
                    <option <?php if($alldata[0]['Restaurant']['delivery_free_range'] == 0 ){echo "selected='selected'";} ?> value="0>">Non Free Delivery</option>
                    <?php

                        for($i = 1; $i<=49; $i++) {
                            ?>
                                <option <?php if($alldata[0]['Restaurant']['delivery_free_range'] == $i ){echo "selected='selected'";} ?> value="<?php echo $i; ?>"><?php echo $i; ?> KM</option>
                            <?php
                        }

                    ?>

               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">AVG Food Prepation Time</label>
                <select name="preparation_time"   class="form-control" required="">
                    <option value="">Select Minutes</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "5" ){echo "selected='selected'";} ?> value="5">5 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "10" ){echo "selected='selected'";} ?> value="10">10 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "15" ){echo "selected='selected'";} ?> value="15">15 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "20" ){echo "selected='selected'";} ?> value="20">20 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "25" ){echo "selected='selected'";} ?> value="25">25 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "30" ){echo "selected='selected'";} ?> value="30">30 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "35" ){echo "selected='selected'";} ?> value="35">35 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "40" ){echo "selected='selected'";} ?> value="40">40 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "45" ){echo "selected='selected'";} ?> value="45">45 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "50" ){echo "selected='selected'";} ?> value="50">50 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "55" ){echo "selected='selected'";} ?> value="55">55 min</option>
                    <option <?php if($alldata[0]['Restaurant']['preparation_time'] == "60" ){echo "selected='selected'";} ?> value="60">60 min</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Tax implementation</label>
                <select name="tax_free"   class="form-control" required="">
                    <option <?php if($alldata[0]['Restaurant']['tax_free'] == "1" ){echo "selected='selected'";} ?> value="1">No Tax will implement</option>
                    <option <?php if($alldata[0]['Restaurant']['tax_free'] == "0" ){echo "selected='selected'";} ?>  value="0">Tax % will implement</option>
                </select>
            </div>
            <div class="clear_both"></div>
            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                    Submit
                </button>
            </div>
            </form>
            





            <h3 style="font-weight: 300;" align="center">Store Open/Close Timing</h3>
            <br>
            <form action="dashboard.php?p=editStore&action=resturnettiming" method="post" enctype="multipart/form-data">

            <div class="full_width">
                <input name="day[]" type="text" value="Sunday" style="background: #e1e1e11a;" readonly>
            </div>
                    <input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" >
            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][0]['opening_time'], 0, 2);

                    ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
               
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][0]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Monday" style="background: #e1e1e11a;" readonly >
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][1]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][1]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Tuesday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">

                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][2]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][2]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="wednesday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][3]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][3]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Thursday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][4]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][4]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Friday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][5]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][5]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Saturday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][6]['opening_time'], 0, 2);

                ?>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <?php
                $result = substr($alldata[0]['RestaurantTiming'][6]['closing_time'], 0, 2);

                ?>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=22; $i++) 
                        {
                            
                            ?>
                                <option  value="<?php echo $i; ?>:00:00" <?php if($i==$result){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }
                    ?>
                    <option value="23:59:00" <?php if($result=="23"){echo "selected";} ?> >23:59:00</option>
               </select>
            </div>
            <div class="clear_both"></div>



            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>

        </form>
        
        <h3 style="font-weight: 300;" align="center">Booking Availablity Information</h3>
              <?php

            $data = array(
                "restaurant_id" => $id
            );
            
            $url = $baseurl . 'getAllResBookingTime';

            $json_data = @curl_request($data, $url);
            
           
            if($json_data['code'] == 200)
            {
                // $msg = $json_data['msg'];
                // echo "<pre>";
                // print_r($msg);
                // echo "</pre>";
                 $headings = $json_data['msg'];
             //    print_r($headings);
            }
		
       
                
            ?>
         
            <form action="dashboard.php?p=editStore&id=<?php echo $id ?>&action=updateDeliveryTime" method="post">
            
           
            <table class="table table-bordered">
                <tr>
                    <td style="width: 16%">Booking Available</td>
                    <td style="width: 2%">:</td>
                    <td style="text-align: left"><input type="checkbox" name="booking_available" value="1" <?php echo $alldata[0]['Restaurant']['booking_available']==1?"checked":"";?>> Yes</td>
                </tr>
            </table>
            <br/>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Availablity</th>
                        <th>Booking Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $i = 0;
                    foreach($headings as $head){
                ?>
                    <tr>
                        <th scope="row"><?php echo ucwords($head['DeliveryBookTime']['booking_day']);?></th>
                        <td>
                            <input type="hidden" name="booking_time_day_id[]" value="<?php echo $head['DeliveryBookTime']['id'];?>">
                            <input type="checkbox" name="booking_day_status_<?php echo $i;?>" value="1" <?php echo $head['DeliveryBookTime']['day_status']==1?"checked":"";?>>
                            
                        </td>

                        <td>
                            <input type="hidden" id="booking_time_ids_<?php echo strtolower($head['DeliveryBookTime']['booking_day']);?>" value="<?php echo $head['DeliveryBookTime']['booking_time_id'];?>" name="booking_time_ids[]">
                            <?php 
                                $explode = explode(",",$head['DeliveryBookTime']['booking_time_id']);
                            ?>
                            <ul class="time_slot"  id="time_slot_<?php echo $head['DeliveryBookTime']['booking_day'];?>">
                                <?php
                                    foreach($allBookingTime as $b_time)
                                    {
                                ?>
                                    <li data-id="<?php echo $b_time['BookingTime']['id'];?>" data-day="<?php echo $head['DeliveryBookTime']['booking_day'];?>" <?php if (in_array($b_time['BookingTime']['id'], $explode)){ echo "class='selected'";}?>><?php echo $b_time['BookingTime']['booking_time'];?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </td>
                    </tr>
                
                <?php
                $i++;
                } 
                ?>
                </tbody>
            </table>
<br/>
            <div class="clear_both"></div>
            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>
        </form>
        
        
        <form>
              <h3 style="font-weight: 300;" align="center">Coupon Codes</h3>
            <div class="right" style="margin-bottom: 2%;">
	            <a href="javascript:;" onClick="addCoupon();" class="filtericon"><i class="fa fa-plus"></i> <span>Add Coupon</span></a>
            </div>

    	<?php 
		$user_id = $_GET['id'];

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"user_id" => $user_id
		);
        
        $url = $baseurl . 'showRestaurantCoupons';
        $json_data = @curl_request($data, $url);
        
		if($json_data['code'] == "201" || $json_data['code'] == "202")
		{
			    ?>
	                <div class="textcenter nothingelse" style="	text-align: center; margin: 40px 0;">
                        <img src="img/nomenu.png" style="display: inline-block;" alt="" />
                        <h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
    				</div>
				<?php

		} else {
			//echo "<div class='alert alert-success'>Successfully payment method updated..</div>";
			//@header("Location: dashboard.php?p=payment");
			echo '<div class="paymentlist couponlist" style="margin-left: 3%;margin-right: 1%;">';
			foreach( $json_data['msg'] as $stttr => $vaaal ) {
				//var_dump($vaaal);
				$cc_id = $vaaal['RestaurantCoupon']['id']; 
				?>
				<div class="cl3 left col30" style="margin-right: 10px; width: 30%; heigth: 50px; display: inline-block;">
				    
					<div class="itm" style="background: #F9F9F9; padding: 15px;	border-radius: 3px;	margin-bottom: 10px;	position: relative;	line-height: 1.5em;	border: 1px solid #ddd; font-size: 14px;"> 
						<div class="cardnm left" style="width: 55%; display: inline-block;"><strong>Code:</strong> <span style="font-weight: bold;"><?php echo $vaaal['RestaurantCoupon']['coupon_code']; ?></span>
    						<br> 
    						<strong>Expiry:</strong> <span style="font-weight: bold;"><?php echo $vaaal['RestaurantCoupon']['expire_date']; ?> </span><br>
    						<strong>Limit:</strong> <span style="font-weight: bold;"><?php echo $vaaal['RestaurantCoupon']['limit_users']; ?></span> <br>
    						<strong>Plateform:</strong> <span style="font-weight: bold;"><?php echo $vaaal['RestaurantCoupon']['type']; ?></span>
						</div>
						<div class="cardtype right col40 textright" style="width: 35%; float: right; display: inline-block;"><strong>Discount</strong><br> <span style="font-weight: bold;"><?php echo $vaaal['RestaurantCoupon']['discount']; ?> %</span></div> 
						<div class="clear" style="margin: 0;"></div>
						
						<div style="color: red;text-align: right;font-size: 13px;">
						    <a href="dashboard.php?p=editStore&action=deletecoupon&coupon_id=<?php echo $vaaal['RestaurantCoupon']['id']; ?>&id=<?php echo $user_id; ?>">
						        <span class="fa fa-trash"></span> Delete
						    </a>
						</div>
					</div>
					
				</div>
				<?php
			} //
			echo '<div class="clear"></div></div>';
		}
	?>
        </form>
        
        
        
        </div>
    </div>





                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
      $(document).ready(function () {
        $('#table_view').DataTable({
            'pageLength': 100
          }
        )
        $('#table_view2').DataTable({
            'pageLength': 35
          }
        )
      })

      function viewRestaurant (id) {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        xmlhttp.open('GET', 'ajex-events.php?action=viewRestaurant&id=' + id)
        xmlhttp.send()
      }

      function addRestaurant () {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        xmlhttp.open('GET', 'ajex-events.php?action=addRestaurant')
        xmlhttp.send()
      }

      function Upload_image_desktop () {

        var fileUpload = document.getElementById('uploadFile')

        var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$')
        if (regex.test(fileUpload.value.toLowerCase())) {

          if (typeof (fileUpload.files) != 'undefined') {

            var reader = new FileReader()

            reader.readAsDataURL(fileUpload.files[0])
            reader.onload = function (e) {

              var image = new Image()

              image.src = e.target.result

              image.onload = function () {
                var height = this.height
                var width = this.width

                if (height == 150 && width == 150) {

                  document.getElementById("logoform").submit();

                } else {

                  alert('Size 150x150')
                  return false
                }
              }

            }
          } else {
            alert('This browser does not support HTML5.')
            return false
          }
        } else {
          alert('Please select a valid Image file.')
          return false
        }
      }

      function Upload_image_desktopCover () {

        var fileUpload = document.getElementById('uploadFileCover')

        var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$')
        if (regex.test(fileUpload.value.toLowerCase())) {

          if (typeof (fileUpload.files) != 'undefined') {

            var reader = new FileReader()

            reader.readAsDataURL(fileUpload.files[0])
            reader.onload = function (e) {

              var image = new Image()

              image.src = e.target.result

              image.onload = function () {
                var height = this.height
                var width = this.width

                if (height == 270 && width == 900) {

                  document.getElementById("coverform").submit();

                } else {

                  alert('Size no more than 900x270')
                  return false
                }
              }

            }
          } else {
            alert('This browser does not support HTML5.')
            return false
          }
        } else {
          alert('Please select a valid Image file.')
          return false
        }
      }

      function Upload_image_catagoryImage () {

        var fileUpload = document.getElementById('uploadFileCatagoryImage')

        var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.ico)$')
        if (regex.test(fileUpload.value.toLowerCase())) {

          if (typeof (fileUpload.files) != 'undefined') {

            var reader = new FileReader()

            reader.readAsDataURL(fileUpload.files[0])
            reader.onload = function (e) {

              var image = new Image()

              image.src = e.target.result

              image.onload = function () {
                var height = this.height
                var width = this.width
                  alert(height)
                  alert(width)

                if (height <= 600 && width <= 600) {

                  document.getElementById("catagoryform").submit();

                } else {

                  alert('Size no more than 600x600')
                  return false
                }
              }

            }
          } else {
            alert('This browser does not support HTML5.')
            return false
          }
        } else {
          alert('Please select a valid Image file.')
          return false
        }
      }
      
    function addCoupon() {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        xmlhttp.open('GET', 'ajex-events.php?action=showAddCoupon&id=<?php echo $_GET['id']; ?>')
        xmlhttp.send()
        
        
      }
        $('li').click(function() {  
          
            var data_day = $(this).attr("data-day");
            $(this).toggleClass("selected");
            
           
            var time_slot_id = "time_slot_"+data_day;
            var dataselected = [];

            $("#"+time_slot_id+" li.selected").each(function() {
                dataselected.push($(this).attr("data-id"));
            });
            
            var r = dataselected.join(",");  
            $("#booking_time_ids_"+data_day).val(r);
        });

        
    </script>
    <?php

} else {

    @header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;

} ?>