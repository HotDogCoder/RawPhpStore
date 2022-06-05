<?php
include("config.php");

if (@$_GET['action'] == "addCurrency")
{

    $url=$baseurl . 'showCountries';

    $data = "";

    $json_data=@curl_request($data,$url);


    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Currency</h2>

        <form class="addcategory" action="?p=manageCurrency&action=addCurrency" method="post" >

            <div class="full_width">
                <label class="field_title">Currency Name</label>
                <input name="currency_name" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Country</label>
                <input name="country" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Currency Code</label>
                <input name="code" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Symbol</label>
                <input name="symbol" type="text" required>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $id; ?>">

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>


        </form>

    </div>
    <?php

}
else
if (@$_GET['action'] == "addCategoryO")
{

    $url=$baseurl . 'showCategories';

    $data = "";

    $json_data=@curl_request($data,$url);


    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Category</h2>
      
        <form class="addcategory" action="?p=manageCategory&action=addCategory" method="post" enctype="multipart/form-data" >

            <div class="full_width">
                <label class="field_title">Category Name</label>
                <input name="category_name" type="text" required>
            </div>
           <?php
           $categories = array();
            if($json_data['code'] == 200)
            {
                $categories = $json_data['msg'];
                //print_r($categories);
            }
           ?>
            <div class="full_width">
                <label class="field_title">Parent Category Name</label>
                <select name="parent_id" class="full_width">
                    <option value="">Select Parent Category</option>
                    <?php 
                            if(!empty($categories))
                            {
                                foreach($categories as $categorie)
                                {
                    ?>
                        <option value="<?php echo $categorie['Category']['id']?>"><?php echo $categorie['Category']['category']?></option>
                    <?php
                                }
                            }
                    ?>
                    
                   
                </select>
            </div>

            <div class="qr-el qr-el-3" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">Upload Logo</span><br>
                        Size 150x150
                    </div>
                </label>
                <input name="icon" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" required="required">
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>


        </form>

    </div>
    <?php

}else
if(@$_GET['q'] == "addCategory")
{
    $id=$_GET['id'];
    $level=$_GET['level'];
    
    ?>
        <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
        <input type="hidden" id="level" name="level" value="<?php echo $level;?>">
        <div class="qr-el" id="logoPreview" style="min-height: auto; float:left; box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.03);padding: 0px;margin: 0px !important;">
            <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 80px;">
                <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 27px;margin-top: 6px;">
                <div class="uploadText" style="font-size: 10px;" id="logouploadText">
                    <span style="color:#F69518;">Upload Image</span><br>
                    Size 120x120 (Optional)
                </div>
            </label>
            <input name="upload_image" class="" id="uploadFile" type="file" onchange="return UploadCategoryImage(this)" style="width: 100%; margin-top: 10px;font-size: 10px; display:none;" required="required">
        </div>
        <input type="hidden" id="imageData">
       
        <input type="text" name="category_name" id="category_name" placeholder="Category Name" style="width:100%; font-size:12px;background: transparent;border: 0px;border-bottom: solid 1px grey;padding: 6px 0;">
        <input type="button" value="Submit" onclick="submitAddNewCategory()" style="width:100%;border: 0px;font-size: 12px;padding: 6px 0;border-radius: 4px;margin-top: 10px;">
    <?php

}else
if(@$_GET['q'] == "editCategory")
{
    $category_id=$_GET['category_id'];
    
    // get single category
    $url=$baseurl . 'showCategoriesNew';
    $data = array(
        "category_id" => $category_id
    );
    $json_data=@curl_request($data,$url);
    
    $json_data=$json_data['msg'];
    
    $checkImageExist=checkImageExist($imagebaseurl.$json_data['Category']['image']);
    if($checkImageExist=="200")
    {
        $checkImageExist=$imagebaseurl.$json_data['Category']['image'];
    }
    else
    {
        $checkImageExist="frontend_public/uploads/noimage.jpg";
    }
    
    ?>
        <input type="hidden" id="id" name="id" value="<?php echo $category_id;?>">
        <input type="hidden" id="level" name="level" value="<?php echo $json_data['Category']['level'];?>">
        <div class="qr-el" id="logoPreview" style="min-height: auto; float:left; box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.03);padding: 0px;margin: 0px !important;">
            <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 80px; background:url('<?php echo $checkImageExist; ?>') center top / contain no-repeat;"></label>
            <input name="upload_image" class="" id="uploadFile" type="file" onchange="return UploadCategoryImage(this)" style="width: 100%; margin-top: 10px;font-size: 10px; display:none;" required="required">
        </div>
        <input type="hidden" id="imageData">
       
        <input type="text" name="category_name" id="category_name" placeholder="Category Name" value="<?php echo $json_data['Category']['category']; ?>" style="width:100%; font-size:12px;background: transparent;border: 0px;border-bottom: solid 1px grey;padding: 6px 0;">
        <input type="button" value="Submit" onclick="editCategory()" style="width:100%;border: 0px;font-size: 12px;padding: 6px 0;border-radius: 4px;margin-top: 10px;">
    <?php

}
else
if(@$_GET['q'] == "submitAddNewCategory")
{
    $id=$_POST['id'];
    $level=$_POST['level'];
    $image=$_POST['image'];
    $image = str_replace("data:image/jpeg;base64,","",$image);
    $image = str_replace("data:image/png;base64,","",$image);
    
    $category_name=$_POST['category_name'];
    
    $url=$baseurl . 'addCategoryNew';

    $data = array(
                    "name"=> $category_name,
                    "store_id"=> "0",
                    "level"=> $id,
                    "description"=> "",
                    "image"=>array("file_data" => $image)
                );

    $json_data=@curl_request($data,$url);
    $json_data=$json_data['msg'];
    
    //print_r($json_data);
    
    $checkImageExist=checkImageExist($imagebaseurl.$json_data['Category']['image']);
    if($checkImageExist=="200")
    {
        $checkImageExist=$imagebaseurl.$json_data['Category']['image'];
    }
    else
    {
        $checkImageExist="frontend_public/uploads/noimage.jpg";
    }
    
    ?>
        <div onclick="showCategory('<?php echo $json_data['Category']['id']; ?>','<?php echo $level;?>')" style="border: solid 1px #f5f5f5;padding:8px 8px;font-size: 13px;background: white;margin: 0 0 5px 0;">
            <img src="<?php echo $checkImageExist; ?>" style="width: 30px; height: 30px;border-radius: 100%;">
            <?php echo $json_data['Category']['category']; ?>
            
            <div style="float: right;">
                <span style="font-size: 12px;margin-right: 5px;">
                    <span class="far fa-edit"></span>
                </span>
                
                <span style="font-size: 12px;">
                    <span class="far fa-trash-alt"></span>
                </span>
            </div>
            <div class="clear"></div>
        </div>
    <?php

}else 
if(@$_GET['q'] == "submitEditCategory")
{
    $id=$_POST['id'];
    $category_name=$_POST['category_name'];
    $level=$_POST['level'];
    $image=$_POST['image'];
    $image = str_replace("data:image/jpeg;base64,","",$image);
    $image = str_replace("data:image/png;base64,","",$image);
    
   
    
    $url=$baseurl . 'addCategoryNew';
    
    if($image=="")
    {
       $data = array(
            "id"=> $id,
            "name"=> $category_name,
            "store_id"=> "0",
            "description"=> "",
            "level"=>$level
        ); 
    }
    else
    {
        $data = array(
            "id"=> $id,
            "name"=> $category_name,
            "store_id"=> "0",
            "description"=> "",
            "image"=>array("file_data" => $image),
            "level"=>$level
        );
    }
    
    

    $json_data=@curl_request($data,$url);
    $json_data=$json_data['code'];
    
    if($json_data=="200")
    {
        echo "200";
    }
    else
    {
        echo $json_data['msg'];
    }
    
   

}else
if(@$_GET['q'] == "deleteCategory")
{
    $category_id=$_GET['category_id'];
    
    $url=$baseurl . 'deleteCategoryNew';

    $data = array(
        "category_id" => $category_id
    );

    $json_data=@curl_request($data,$url);
    //print_r($json_data);
    $json_data=$json_data['code'];
    
    if($json_data=="200")
    {
        echo "200";
    }
    else
    {
        echo $json_data['msg'];
    }
    
    

}
else
if (@$_GET['action'] == "paytoRestaurant")
{
    
    $restaurant_id=@$_GET['id'];
    $payment=@$_GET['payment'];
    $type=@$_GET['type'];

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Pay Now</h2>

        <form class="addcategory" action="?p=earning&action=paytoRestaurant" method="post" >
            
            <input name="restaurant_id" type="hidden" value="<?php echo $restaurant_id; ?>" required>
            <input name="type" type="hidden" value="<?php echo $type; ?>" required>
            
            <div class="full_width">
                <label class="field_title">Paid Date</label>
                <input name="amount" type="text" value="<?php echo $payment; ?>" required>
            </div>
            
            <div class="full_width">
                <label class="field_title">Paid Date</label>
                <input name="paid_date" id="paid_date" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Payment Method</label>
                <select name="pay_via" class="full_width" required>
                    <option value="">Select Payment Method</option>
                    <option value="bank">Payment Via Bank</option>
                    <option value="cash">Payment Via Cash</option>
                </select>
            </div>

            <div class="full_width">
                <label class="field_title">Note</label>
                <textarea name="note" type="text"></textarea>
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>


        </form>

    </div>
    <?php

}

else
if (@$_GET['action'] == "addTax")
{

    $url=$baseurl . 'showCountries';

    $data = "";

    $json_data=@curl_request($data,$url);
    
    //get all currency
    $url=$baseurl . 'showCurrencies';
    $data = [];

    $currency=@curl_request($data,$url);

    //get all tax
    $url=$baseurl . 'showAllTaxes';
    $data = [];

    $tax=@curl_request($data,$url);
    
    
    if(count($currency['msg'])=="0" || count($currency['msg'])=="")
    {
        echo"<em>Note: You have to add currency first <a href='dashboard.php?p=manageCurrency' class='redLink'>Add Currency</a></em>";
        die();
    }
   

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Tax</h2>

        <div style="height:420px; overflow:scroll;">
            <form class="addcategory" action="?p=taxSetting&action=addTax" method="post" >

                <div class="full_width">
                    <label class="field_title">Country</label>
                    <select name="country" class="full_width" required>
                        <option value="">Select Country</option>

                        <?php  foreach( $json_data['msg']['countries'] as $str => $val ): ?>

                            <option value="<?php echo $val['Currency']['country']; ?>"><?php echo $val['Currency']['country']; ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>
                
                <div class="full_width">
                    <label class="field_title">City</label>
                    <input name="city" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">State</label>
                    <input name="state" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Tax %</label>
                    <input name="tax" type="number" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Delivery Fee Per Km</label>
                    <input name="deliveryfee" type="number" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Country Code  eg +1</label>
                    <input name="countrycode" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Delivery Est time</label>
                    <input name="delivery_time" type="number" required>
                </div>


                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
                
                <em>Note:Make sure you have added the currency 1st then country will be show.</em>

            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "editUser")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showUserDetail';

    $data = array(
                    "user_id" => $id
                );

    $json_data=@curl_request($data,$url);

    $id=$json_data['msg']['User']['id'];
    $fullname = explode(" ", $json_data['msg']['UserInfo']['full_name']);
    if(isset($fullname[0]))
        $first_name=$fullname[0];
    else
        $first_name = "";

    if(isset($fullname[1]))
        $last_name=$fullname[1];
    else
        $last_name = "";
    $phone=$json_data['msg']['UserInfo']['phone'];
    $email=$json_data['msg']['User']['email'];
    $role=$json_data['msg']['User']['role'];
    $rider_fee=$json_data['msg']['UserInfo']['rider_fee'];

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit User</h2>

        <div style="height:400px; overflow:scroll;">
            <form action="?p=users&action=editProfile" method="post" >
                <input name="user_id" type="hidden" value="<?php echo $id; ?>" required>
                <div class="full_width">
                    <label class="field_title">First Name</label>
                    <input name="first_name" type="text" value="<?php echo $first_name; ?>" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Last Name</label>
                    <input name="last_name" type="text" value="<?php echo $last_name; ?>" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Phone #</label>
                    <input name="phone" type="text" value="<?php echo $phone; ?>" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Email</label>
                    <input name="email" type="text" value="<?php echo $email; ?>" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Role</label>
                    <input name="state" type="text" value="<?php echo $role; ?>" readonly required>
                </div>
                
                <?php
                    if($role=="rider")
                    {
                        ?>
                            <div class="full_width">
                                <label class="field_title">Rider Fee (Price eg 4)</label>
                                <input name="rider_fee" type="text" value="<?php echo $rider_fee; ?>" required>
                            </div>
                        <?php
                    }
                    else
                    {
                        ?>
                            <input name="rider_fee" type="hidden" value="0">
                        <?php
                    }
                ?>
                
                
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "editBookingTime")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showBookingTime';

    $data = array(
            "id" => $id
        );

    $json_data=@curl_request($data,$url);

    $id=$json_data['msg']['BookingTime']['id'];
    
    $bookingTime = $json_data['msg']['BookingTime']['booking_time'];
    $b_status = $json_data['msg']['BookingTime']['b_status'];
    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit Booking Time</h2>

        <div style="height:160px;">
            <form action="?p=booking_time&action=addBookingTime" method="post" >
                <input name="booking_id" type="hidden" value="<?php echo $id; ?>" required>
                <div class="full_width">
                    <label class="field_title">Time</label>
                    <input name="booking_time" type="text" value="<?php echo $bookingTime; ?>" autocomplete="off" placeholder="  Example: 09:00AM - 10:00AM" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Status</label>
                    <select name="status_id" class="full_width" required>
                        <option value="">Select Status</option>
                        <option value="1" <?php if($b_status == 1){ echo "selected='selected'";}?>>Publish</option>
                        <option value="2" <?php if($b_status == 2){ echo "selected='selected'";}?>>Draft</option>
                    </select>
                </div>

                
                
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "changePassword")
{
    $id=$_GET['id'];

    /*$url=$baseurl . 'showUserDetail';

    $data = array(
        "user_id" => $id
    );

    $json_data=@curl_request($data,$url);

    $id=$json_data['msg']['User']['id'];
    $first_name=$json_data['msg']['UserInfo']['first_name'];
    $last_name=$json_data['msg']['UserInfo']['last_name'];
    $phone=$json_data['msg']['UserInfo']['phone'];
    $email=$json_data['msg']['User']['email'];
    $role=$json_data['msg']['User']['role'];*/


    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Change Password</h2>

        <div style="height:130px; overflow:scroll;">
            <form action="?p=users&action=changePassword" method="post" >
                <input name="user_id" type="hidden" value="<?php echo $id; ?>" required>
                <div class="full_width">
                    <label class="field_title">New Password</label>
                    <input name="password" type="text" required>
                </div>
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "changeAdminPassword")
{
    $id=$_GET['id'];

    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Change Admin Password</h2>

        <div style="height:130px; overflow:scroll;">
            <form action="?p=adminUsers&action=changeAdminPassword" method="post" >
                <input name="user_id" type="hidden" value="<?php echo $id; ?>" required>
                <div class="full_width">
                    <label class="field_title">New Password</label>
                    <input name="password" type="text" required>
                </div>
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "editTax")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showTaxDetail';

    $data = array(
                    "id" => $id
                );

    $json_data=@curl_request($data,$url);

    $id=$json_data['msg'][0]['Tax']['id'];
    $city=$json_data['msg'][0]['Tax']['city'];
    $state=$json_data['msg'][0]['Tax']['state'];
    $country=$json_data['msg'][0]['Tax']['country'];
    $country_code=$json_data['msg'][0]['Tax']['country_code'];
    $tax=$json_data['msg'][0]['Tax']['tax'];
    $delivery_fee_per_km=$json_data['msg'][0]['Tax']['delivery_fee_per_km'];
    $delivery_time=$json_data['msg'][0]['Tax']['delivery_time'];

    //get countries
    $url=$baseurl . 'showCountries';

    $data = "";

    $json_data=@curl_request($data,$url);

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit Tax</h2>

        <div style="height:450px; overflow:scroll;">
            <form class="addcategory" action="?p=taxSetting&action=addTax" method="post" >
                <input name="id" type="hidden" value="<?php echo $id; ?>" required>

                <div class="full_width">
                    <label class="field_title">Country</label>
                    <select name="country" class="full_width" required>
                        <option value="">Select Country</option>

                        <?php  foreach( $json_data['msg']['countries'] as $str => $val ): ?>

                            <option value="<?php echo $val['Currency']['country'];?>" <?php if($country==$val['Currency']['country']){ echo "selected";} ?> ><?php echo $val['Currency']['country']; ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="full_width">
                    <label class="field_title">City</label>
                    <input name="city" type="text" value="<?php echo $city; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">State</label>
                    <input name="state" type="text" value="<?php echo $state; ?>"  required>
                </div>


                <div class="full_width">
                    <label class="field_title">Tax %</label>
                    <input name="tax" type="number" value="<?php echo $tax; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Delivery Fee Per Km</label>
                    <input name="deliveryfee" type="number" value="<?php echo $delivery_fee_per_km; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Country Code  eg +1</label>
                    <input name="countrycode" type="text" value="<?php echo $country_code; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Delivery Est time</label>
                    <input name="delivery_time" type="number" value="<?php echo $delivery_time; ?>"  required>
                </div>


                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>


            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "editCurrency")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showCurrencyDetail';

    $data = array(
                    "id" => $id
                );

    $showCurrencyDetail=@curl_request($data,$url);

    //get countries
    $url=$baseurl . 'showCountries';

    $data = "";

    $json_data=@curl_request($data,$url);

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit Currency</h2>

        <div style="height:350px; overflow:scroll;">
            <form class="addcategory" action="?p=manageCurrency&action=addCurrency" method="post" >
                <input name="id" type="hidden" value="<?php echo $showCurrencyDetail['msg'][0]['Currency']['id']; ?>" required>

                <div class="full_width">
                    <label class="field_title">Country</label>
                    <input name="country" type="text" value="<?php echo $showCurrencyDetail['msg'][0]['Currency']['country']; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Currency Name</label>
                    <input name="currency_name" type="text" value="<?php echo $showCurrencyDetail['msg'][0]['Currency']['currency']; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Currency Code</label>
                    <input name="code" type="text" value="<?php echo $showCurrencyDetail['msg'][0]['Currency']['code']; ?>"  required>
                </div>

                <div class="full_width">
                    <label class="field_title">Symbol</label>
                    <input name="symbol" type="text" value="<?php echo $showCurrencyDetail['msg'][0]['Currency']['symbol']; ?>"  required>
                </div>

                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>


            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "editCategoryO")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showCategoryDetail';

    $data = array(
                    "id" => $id
                );

    $showCategoryDetail=@curl_request($data,$url);
    //get countries
    $url=$baseurl . 'showCategories';

    $data = "";

    $json_data=@curl_request($data,$url);

    if($showCategoryDetail['msg'][0]['Category']['icon']!="")
    {
        $icon = $showCategoryDetail['msg'][0]['Category']['icon'];
    }
    else
    {
        $icon = "frontend_public/uploads/attachment/upload.png";
    }

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit Category</h2>

        <div style="height:350px; overflow:scroll;">
            <form class="addcategory" action="?p=manageCategory&action=addCategory" method="post" enctype="multipart/form-data" >
                <input name="id" type="hidden" value="<?php echo $showCategoryDetail['msg'][0]['Category']['id']; ?>" required>

                <div class="full_width">
                    <label class="field_title">Category</label>
                    <input name="category_name" type="text" value="<?php echo $showCategoryDetail['msg'][0]['Category']['category']; ?>"  required>
                </div>

                <?php
           $categories = array();
            if($json_data['code'] == 200)
            {
                $categories = $json_data['msg'];
                //print_r($categories);
            }
           ?>
            <div class="full_width">
                <label class="field_title">Parent Category Name</label>
                <select name="parent_id" class="full_width">
                    <option value="">Select Parent Category</option>
                    <?php 
                            if(!empty($categories))
                            {
                                foreach($categories as $categorie)
                                {
                    ?>
                        <option value="<?php echo $categorie['Category']['id'];?>" <?php if($categorie['Category']['id'] == $showCategoryDetail['msg'][0]['Category']['parent_id']){ echo 'selected';}?>><?php echo $categorie['Category']['category']?></option>
                    <?php
                                }
                            }
                    ?>
                    
                   
                </select>
            </div>

                <div class="qr-el qr-el-3" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="<?php echo $icon;?>" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">Upload Logo</span><br>
                        Size 150x150
                    </div>
                </label>
                <input name="icon" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" >
            </div>

                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>


            </form>
        </div>
    </div>
    <?php

}else 
if(@$_GET['q'] == "showCategory")
{
    $id=$_GET['id'];
    $level=$_GET['level'];

    $url=$baseurl . 'showCategoriesNew';

    $data = array(
                    "level" => $id
                );

    $json_data=@curl_request($data,$url);
    //print_r($json_data);
    $json_data=$json_data['msg'];
    
    ?>
        
        <div class="category" style="border:solid 1px #fbf9f9; height: auto; width:240px;padding: 8px 8px;border-radius: 3px;background: #fcfcfc; float: left;margin-left: 10px;">
            <?php
            foreach ($json_data as $singleRow): 
                    
                    $checkImageExist=checkImageExist($imagebaseurl.$singleRow['Category']['image']);
                    if($checkImageExist=="200")
                    {
                        $checkImageExist=$imagebaseurl.$singleRow['Category']['image'];
                    }
                    else
                    {
                        $checkImageExist="frontend_public/uploads/noimage.jpg";
                    }
                    
                ?>
                     <div id="row_<?php echo $singleRow['Category']['id']; ?>" style="border: solid 1px #f5f5f5;padding:2px 8px;font-size: 13px;background: white;margin: 0 0 5px 0;cursor: pointer;">
                        
                        <div style="float: left;" onclick="showCategory('<?php echo $singleRow['Category']['id']; ?>','<?php echo $level+1;?>')" ><img src="<?php echo $checkImageExist; ?>" style="width: 30px; height: 30px;border-radius: 100%;"></div>
                        <div style="float: left;margin-top: 10px;" class="title_<?php echo $singleRow['Category']['id']; ?>" onclick="showCategory('<?php echo $singleRow['Category']['id']; ?>','<?php echo $level+1;?>')" ><?php echo $singleRow['Category']['category']; ?></div>
                        <div style="float: right; margin-top: 10px;">
                            
                            <?php
                                if($singleRow['Category']['featured']=="0")
                                {
                                   ?>
                                        <span style="font-size: 12px;margin-right: 5px;">
                                            <a href="process.php?action=favCategory&featured=1&category_id=<?php echo $singleRow['Category']['id']; ?>"><span class="far fa-star"></span></a>
                                        </span>
                                   <?php 
                                }
                                else
                                if($singleRow['Category']['featured']=="1")
                                {
                                   ?>
                                        <span style="font-size: 12px;margin-right: 5px;">
                                            <a href="process.php?action=favCategory&featured=0&category_id=<?php echo $singleRow['Category']['id']; ?>"><span class="fas fa-star" style="color:black;"></span></a>
                                        </span>
                                   <?php 
                                }
                            ?>
                            
                            <span class="editCategory" onclick="editCategoryRow('<?php echo $singleRow['Category']['id']; ?>')" style="font-size: 12px;margin-right: 5px;">
                                <span class="far fa-edit"></span>
                            </span>
                            
                            <span class="deleteCategory" onclick="deleteCategory('<?php echo $singleRow['Category']['id']; ?>')" style="font-size: 12px;">
                                <span class="far fa-trash-alt"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="editBox" id="edit_<?php echo $singleRow['Category']['id']; ?>"></div>
                    
                    
                    
                    
                    
                <?php 
            endforeach;   
            ?>
            <div id="newEntry_<?php echo $level+1;?>"></div>
            <div onclick="addCategory('<?php echo $id;?>','<?php echo $level+1;?>')" style="border: dashed 2px #f5f5f5;padding:8px 8px;font-size: 13px;background: #fbfbfb;margin: 0 0 5px 0;">
                + Add New
            </div>
            <div id="addCategory_<?php echo $level+1;?>"></div>
        </div>
        <span id="dataRecived_<?php echo $level+1;?>" style="float: left;"></span>
        
    <?php

}
else
if (@$_GET['action'] == "viewRestaurant")
{
    $id=$_GET['id'];

    $url=$baseurl . 'showRestaurantDetail';

    $data = array(
                    "id" => $id
                );

    $json_data=@curl_request($data,$url);



    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Restaurant Details</h2>

        <div style="height:400px; overflow:scroll;">

            <input name="id" type="hidden" value="<?php echo $json_data['msg'][0]['Restaurant']['id'];  ?>" required>
            <div class="full_width">
                <label class="field_title">Name</label>
                <input name="name" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['name']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Slogan</label>
                <input name="slogan" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['slogan']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">About</label>
                <textarea name="about" type="text" required><?php echo $json_data['msg'][0]['Restaurant']['about']; ?></textarea>
            </div>

            <div class="full_width">
                <label class="field_title">Speciality</label>
                <input name="speciality" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['speciality']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Phone</label>
                <input name="phone" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['phone']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Time Zone</label>
                <input name="timezone" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['timezone']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Min Order Price</label>
                <input name="min_order_price" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['min_order_price']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Delivery Free Range</label>
                <input name="delivery_free_range" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['delivery_free_range']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">currency_id</label>
                <input name="currency_id" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['currency_id']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">tax_id</label>
                <input name="tax_id" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['tax_id']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Google Analytics</label>
                <input name="google_analytics" type="text" value="<?php echo $json_data['msg'][0]['Restaurant']['google_analytics']; ?>"  required>
            </div>


            <h3 style="font-weight: 300;" align="center">User Info</h3>


            <div class="full_width">
                <label class="field_title">First Name</label>
                <input name="first_name" type="text" value="<?php echo $json_data['msg'][0]['UserInfo']['first_name']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Last Name</label>
                <input name="last_name" type="text" value="<?php echo $json_data['msg'][0]['UserInfo']['last_name']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Phone</label>
                <input name="phone" type="text" value="<?php echo $json_data['msg'][0]['UserInfo']['phone']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Email</label>
                <input name="email" type="text" value="<?php echo $json_data['msg'][0]['User']['email']; ?>"  required>
            </div>


            <h3 style="font-weight: 300;" align="center">Currency Info</h3>
            <div style="text-align: center;font-size: 12px;">
                <!--<a href="" style=" color:#C3242E;">-->
                <!--    <span class="fa fa-edit"></span> Edit-->
                </a>
            </div>


            <div class="full_width">
                <label class="field_title">Country</label>
                <input name="country" type="text" value="<?php echo $json_data['msg'][0]['Currency']['country']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Currency</label>
                <input name="currency" type="text" value="<?php echo $json_data['msg'][0]['Currency']['currency']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Code</label>
                <input name="code" type="text" value="<?php echo $json_data['msg'][0]['Currency']['code']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Symbol</label>
                <input name="symbol" type="text" value="<?php echo $json_data['msg'][0]['Currency']['symbol']; ?>"  required>
            </div>




            <h3 style="font-weight: 300;" align="center">Tax Info</h3>
            <div style="text-align: center;font-size: 12px;">
                <!--<a href="" style=" color:#C3242E;">-->
                <!--    <span class="fa fa-edit"></span> Edit-->
                <!--</a>-->
            </div>


            <div class="full_width">
                <label class="field_title">City</label>
                <input name="city" type="text" value="<?php echo $json_data['msg'][0]['Tax']['city']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">State</label>
                <input name="state" type="text" value="<?php echo $json_data['msg'][0]['Tax']['state']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Country</label>
                <input name="country" type="text" value="<?php echo $json_data['msg'][0]['Tax']['country']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Tax</label>
                <input name="tax" type="text" value="<?php echo $json_data['msg'][0]['Tax']['tax']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Delivery Fee Per km</label>
                <input name="delivery_fee_per_km" type="text" value="<?php echo $json_data['msg'][0]['Tax']['delivery_fee_per_km']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Country Code</label>
                <input name="country_code" type="text" value="<?php echo $json_data['msg'][0]['Tax']['country_code']; ?>"  required>
            </div>

            <div class="full_width">
                <label class="field_title">Delivery Time</label>
                <input name="delivery_time" type="text" value="<?php echo $json_data['msg'][0]['Tax']['delivery_time']; ?>"  required>
            </div>



            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "addBookingTime")
{
    

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Booking Time</h2>

        <div style="height:160px; ">
            <form class="addcategory" action="?p=booking_time&action=addBookingTime" method="post" >

                <div class="full_width">
                    <label class="field_title">Time</label>
                    <input name="booking_time" type="text" placeholder=" Example: 09:00AM - 10:00AM" autocomplete="off" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Status</label>
                    <select name="status_id" class="full_width" required>
                        <option value="">Select Status</option>
                        <option value="1">Publish</option>
                        <option value="2">Draft</option>
                    </select>
                </div>

                
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>


            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "addRider")
{
    $url=$baseurl . 'showCountries';

    $data = "";

    $json_data=@curl_request($data,$url);

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Rider</h2>

        <div style="height:400px; overflow:scroll;">
            <form class="addcategory" action="?p=rider&action=addRider" method="post" >

                <div class="full_width">
                    <label class="field_title">Email</label>
                    <input name="email" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Password</label>
                    <input name="password" type="password" required>
                </div>

                <div class="full_width">
                    <label class="field_title">First Name</label>
                    <input name="first_name" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Last Name</label>
                    <input name="last_name" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Phone No</label>
                    <input name="phone" type="number" required>
                </div>
                
                <div class="full_width">
                    <label class="field_title">Rider Fee (Price eg 4)</label>
                    <input name="rider_fee" type="text" required>
                </div>
                
                <div class="full_width">
                    <label class="field_title">Country</label>
                    <select name="country" class="full_width" required>
                        <option value="">Select Country</option>

                        <?php  foreach( $json_data['msg']['countries'] as $str => $val ): ?>

                            <option value="<?php echo $val['Currency']['country']; ?>"><?php echo $val['Currency']['country']; ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="full_width">
                    <label class="field_title">City</label>
                    <select name="city" class="full_width" required>
                        <option value="">Select City</option>
						<option value="Dammam">Dammam</option>
						<option value="Dhahran">Dhahran</option>
						<option value="Khobar">Khobar</option>

                    </select>
                </div>


                <div class="full_width">
                    <label class="field_title">Shift Start From (Address/Area)</label>
                    <input name="address_to_start_shift" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Note</label>
                    <input name="note" type="text" required>
                </div>


                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>


            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "addRestaurant")
{
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

    //$url=$baseurl . 'showCategories';
    $url=$baseurl . 'showCategoriesStore';
    $data = [];

    $category=@curl_request($data,$url);
    
    
    if(count($currency['msg'])=="0" || count($currency['msg'])=="")
    {
        echo"<em>Note: You have to add currency first <a href='dashboard.php?p=manageCurrency' class='redLink'>Add Currency</a></em>";
        die();
    }
    else
    if(count($tax['msg'])=="0" || count($tax['msg'])=="")
    {
        echo"<em>Note: You have to add tax information first <a href='dashboard.php?p=taxSetting' class='redLink'>Add Tax</a></em>";
        die();
    }
    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Restaurant</h2>

        <div style="height:450px; overflow:scroll;">

        <form action="dashboard.php?p=restaurants&action=addRestaurant" method="post" enctype="multipart/form-data">
            <div class="qr-el qr-el-3" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">Upload Logo</span><br>
                        Size 150x150
                    </div>
                </label>
                <input name="upload_image" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" required="required">
            </div>

            <div class="qr-el qr-el-3" id="coverPreview" style="min-height: auto; float:right; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFileCover" class="hoviringdell uploadBox" id="uploadTriggerCover" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="coverPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="coveruploadText">
                        <span style="color:#F69518;">Upload Cover</span><br>
                        Size no more than 900x270
                    </div>
                </label>
                <input name="Cover_upload_image" class="" id="uploadFileCover" type="file" onchange="return Upload_image_desktopCover()" style="width: 100%; margin-top: 20px;" required="required">
            </div>
            <div style="clear:both;"></div>


            <div class="half_width float_left">
                <label class="field_title">Restaurant Name</label>
                <input name="name" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Slogan</label>
                <input name="slogan" type="text" required>
            </div>

            <div class="full_width clear_both">
                <label class="field_title">About</label>
                <textarea name="about" type="text" required></textarea>
            </div>

            <!-- <div class="half_width float_left">
                <label class="field_title">Speciality</label>
                <input name="speciality" type="text" required>
            </div> -->

            <div class="half_width float_left">
                <label class="field_title">Categories</label>
                <select name="category_id[]" class="form-control category-dropdown" required="" multiple="multiple" style="height: 110px;">
                    <option value="">Select Categories</option>

                    <?php  foreach( $category['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Category']['id']; ?>"><?php echo $val['Category']['category']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_right" style="height: 132px;">
                <label class="field_title">Phone</label>
                <input name="phone" type="text" required>
            </div>
            
            <div class="full_width">
                <label class="field_title">Admin Commission (%)</label>
                <input name="admin_commission" type="text" required>
            </div>

            <div class="half_width float_left" style="display:none;">
                <label class="field_title">Timezone</label>
                <select name="timezone" class="form-control">
                    <option value="0">Select timezone</option>
                        <option value=" 00:00">Africa/Abidjan</option>
                        <option value=" 00:00">Africa/Accra</option>
                        <option value="+03:00">Africa/Addis_Ababa</option>
                        <option value="+01:00">Africa/Algiers</option>
                        <option value="+03:00">Africa/Asmara</option>
                        <option value=" 00:00">Africa/Bamako</option>
                        <option value="+01:00">Africa/Bangui</option>
                        <option value=" 00:00">Africa/Banjul</option>
                        <option value=" 00:00">Africa/Bissau</option>
                        <option value="+02:00">Africa/Blantyre</option>
                        <option value="+01:00">Africa/Brazzaville</option>
                        <option value="+02:00">Africa/Bujumbura</option>
                        <option value="+02:00">Africa/Cairo</option>
                        <option value=" 00:00">Africa/Casablanca</option>
                        <option value="+01:00">Africa/Ceuta</option>
                        <option value=" 00:00">Africa/Conakry</option>
                        <option value=" 00:00">Africa/Dakar</option>
                        <option value="+03:00">Africa/Dar_es_Salaam</option>
                        <option value="+03:00">Africa/Djibouti</option>
                        <option value="+01:00">Africa/Douala</option>
                        <option value=" 00:00">Africa/El_Aaiun</option>
                        <option value=" 00:00">Africa/Freetown</option>
                        <option value="+02:00">Africa/Gaborone</option>
                        <option value="+02:00">Africa/Harare</option>
                        <option value="+02:00">Africa/Johannesburg</option>
                        <option value="+03:00">Africa/Juba</option>
                        <option value="+03:00">Africa/Kampala</option>
                        <option value="+02:00">Africa/Khartoum</option>
                        <option value="+02:00">Africa/Kigali</option>
                        <option value="+01:00">Africa/Kinshasa</option>
                        <option value="+01:00">Africa/Lagos</option>
                        <option value="+01:00">Africa/Libreville</option>
                        <option value=" 00:00">Africa/Lome</option>
                        <option value="+01:00">Africa/Luanda</option>
                        <option value="+02:00">Africa/Lubumbashi</option>
                        <option value="+02:00">Africa/Lusaka</option>
                        <option value="+01:00">Africa/Malabo</option>
                        <option value="+02:00">Africa/Maputo</option>
                        <option value="+02:00">Africa/Maseru</option>
                        <option value="+02:00">Africa/Mbabane</option>
                        <option value="+03:00">Africa/Mogadishu</option>
                        <option value=" 00:00">Africa/Monrovia</option>
                        <option value="+03:00">Africa/Nairobi</option>
                        <option value="+01:00">Africa/Ndjamena</option>
                        <option value="+01:00">Africa/Niamey</option>
                        <option value=" 00:00">Africa/Nouakchott</option>
                        <option value=" 00:00">Africa/Ouagadougou</option>
                        <option value="+01:00">Africa/Porto-Novo</option>
                        <option value="+01:00">Africa/Sao_Tome</option>
                        <option value="+02:00">Africa/Tripoli</option>
                        <option value="+01:00">Africa/Tunis</option>
                        <option value="+02:00">Africa/Windhoek</option>
                        <option value="-09:00">America/Adak</option>
                        <option value="-08:00">America/Anchorage</option>
                        <option value="-04:00">America/Anguilla</option>
                        <option value="-04:00">America/Antigua</option>
                        <option value="-03:00">America/Araguaina</option>
                        <option value="-03:00">America/Argentina/Buenos_Aires</option>
                        <option value="-03:00">America/Argentina/Catamarca</option>
                        <option value="-03:00">America/Argentina/Cordoba</option>
                        <option value="-03:00">America/Argentina/Jujuy</option>
                        <option value="-03:00">America/Argentina/La_Rioja</option>
                        <option value="-03:00">America/Argentina/Mendoza</option>
                        <option value="-03:00">America/Argentina/Rio_Gallegos</option>
                        <option value="-03:00">America/Argentina/Salta</option>
                        <option value="-03:00">America/Argentina/San_Juan</option>
                        <option value="-03:00">America/Argentina/San_Luis</option>
                        <option value="-03:00">America/Argentina/Tucuman</option>
                        <option value="-03:00">America/Argentina/Ushuaia</option>
                        <option value="-04:00">America/Aruba</option>
                        <option value="-03:00">America/Asuncion</option>
                        <option value="-05:00">America/Atikokan</option>
                        <option value="-03:00">America/Bahia</option>
                        <option value="-06:00">America/Bahia_Banderas</option>
                        <option value="-04:00">America/Barbados</option>
                        <option value="-03:00">America/Belem</option>
                        <option value="-06:00">America/Belize</option>
                        <option value="-04:00">America/Blanc-Sablon</option>
                        <option value="-04:00">America/Boa_Vista</option>
                        <option value="-05:00">America/Bogota</option>
                        <option value="-06:00">America/Boise</option>
                        <option value="-06:00">America/Cambridge_Bay</option>
                        <option value="-04:00">America/Campo_Grande</option>
                        <option value="-05:00">America/Cancun</option>
                        <option value="-04:00">America/Caracas</option>
                        <option value="-03:00">America/Cayenne</option>
                        <option value="-05:00">America/Cayman</option>
                        <option value="-05:00">America/Chicago</option>
                        <option value="-07:00">America/Chihuahua</option>
                        <option value="-06:00">America/Costa_Rica</option>
                        <option value="-07:00">America/Creston</option>
                        <option value="-04:00">America/Cuiaba</option>
                        <option value="-04:00">America/Curacao</option>
                        <option value=" 00:00">America/Danmarkshavn</option>
                        <option value="-07:00">America/Dawson</option>
                        <option value="-07:00">America/Dawson_Creek</option>
                        <option value="-06:00">America/Denver</option>
                        <option value="-04:00">America/Detroit</option>
                        <option value="-04:00">America/Dominica</option>
                        <option value="-06:00">America/Edmonton</option>
                        <option value="-05:00">America/Eirunepe</option>
                        <option value="-06:00">America/El_Salvador</option>
                        <option value="-07:00">America/Fort_Nelson</option>
                        <option value="-03:00">America/Fortaleza</option>
                        <option value="-03:00">America/Glace_Bay</option>
                        <option value="-03:00">America/Godthab</option>
                        <option value="-03:00">America/Goose_Bay</option>
                        <option value="-04:00">America/Grand_Turk</option>
                        <option value="-04:00">America/Grenada</option>
                        <option value="-04:00">America/Guadeloupe</option>
                        <option value="-06:00">America/Guatemala</option>
                        <option value="-05:00">America/Guayaquil</option>
                        <option value="-04:00">America/Guyana</option>
                        <option value="-03:00">America/Halifax</option>
                        <option value="-04:00">America/Havana</option>
                        <option value="-07:00">America/Hermosillo</option>
                        <option value="-04:00">America/Indiana/Indianapolis</option>
                        <option value="-05:00">America/Indiana/Knox</option>
                        <option value="-04:00">America/Indiana/Marengo</option>
                        <option value="-04:00">America/Indiana/Petersburg</option>
                        <option value="-05:00">America/Indiana/Tell_City</option>
                        <option value="-04:00">America/Indiana/Vevay</option>
                        <option value="-04:00">America/Indiana/Vincennes</option>
                        <option value="-04:00">America/Indiana/Winamac</option>
                        <option value="-06:00">America/Inuvik</option>
                        <option value="-04:00">America/Iqaluit</option>
                        <option value="-05:00">America/Jamaica</option>
                        <option value="-08:00">America/Juneau</option>
                        <option value="-04:00">America/Kentucky/Louisville</option>
                        <option value="-04:00">America/Kentucky/Monticello</option>
                        <option value="-04:00">America/Kralendijk</option>
                        <option value="-04:00">America/La_Paz</option>
                        <option value="-05:00">America/Lima</option>
                        <option value="-07:00">America/Los_Angeles</option>
                        <option value="-04:00">America/Lower_Princes</option>
                        <option value="-03:00">America/Maceio</option>
                        <option value="-06:00">America/Managua</option>
                        <option value="-04:00">America/Manaus</option>
                        <option value="-04:00">America/Marigot</option>
                        <option value="-04:00">America/Martinique</option>
                        <option value="-05:00">America/Matamoros</option>
                        <option value="-07:00">America/Mazatlan</option>
                        <option value="-05:00">America/Menominee</option>
                        <option value="-06:00">America/Merida</option>
                        <option value="-08:00">America/Metlakatla</option>
                        <option value="-06:00">America/Mexico_City</option>
                        <option value="-02:00">America/Miquelon</option>
                        <option value="-03:00">America/Moncton</option>
                        <option value="-06:00">America/Monterrey</option>
                        <option value="-03:00">America/Montevideo</option>
                        <option value="-04:00">America/Montserrat</option>
                        <option value="-04:00">America/Nassau</option>
                        <option value="-04:00">America/New_York</option>
                        <option value="-04:00">America/Nipigon</option>
                        <option value="-08:00">America/Nome</option>
                        <option value="-02:00">America/Noronha</option>
                        <option value="-05:00">America/North_Dakota/Beulah</option>
                        <option value="-05:00">America/North_Dakota/Center</option>
                        <option value="-05:00">America/North_Dakota/New_Salem</option>
                        <option value="-06:00">America/Ojinaga</option>
                        <option value="-05:00">America/Panama</option>
                        <option value="-04:00">America/Pangnirtung</option>
                        <option value="-03:00">America/Paramaribo</option>
                        <option value="-07:00">America/Phoenix</option>
                        <option value="-04:00">America/Port-au-Prince</option>
                        <option value="-04:00">America/Port_of_Spain</option>
                        <option value="-04:00">America/Porto_Velho</option>
                        <option value="-04:00">America/Puerto_Rico</option>
                        <option value="-03:00">America/Punta_Arenas</option>
                        <option value="-05:00">America/Rainy_River</option>
                        <option value="-05:00">America/Rankin_Inlet</option>
                        <option value="-03:00">America/Recife</option>
                        <option value="-06:00">America/Regina</option>
                        <option value="-05:00">America/Resolute</option>
                        <option value="-05:00">America/Rio_Branco</option>
                        <option value="-03:00">America/Santarem</option>
                        <option value="-03:00">America/Santiago</option>
                        <option value="-04:00">America/Santo_Domingo</option>
                        <option value="-03:00">America/Sao_Paulo</option>
                        <option value="-01:00">America/Scoresbysund</option>
                        <option value="-08:00">America/Sitka</option>
                        <option value="-04:00">America/St_Barthelemy</option>
                        <option value="-02:30">America/St_Johns</option>
                        <option value="-04:00">America/St_Kitts</option>
                        <option value="-04:00">America/St_Lucia</option>
                        <option value="-04:00">America/St_Thomas</option>
                        <option value="-04:00">America/St_Vincent</option>
                        <option value="-06:00">America/Swift_Current</option>
                        <option value="-06:00">America/Tegucigalpa</option>
                        <option value="-03:00">America/Thule</option>
                        <option value="-04:00">America/Thunder_Bay</option>
                        <option value="-07:00">America/Tijuana</option>
                        <option value="-04:00">America/Toronto</option>
                        <option value="-04:00">America/Tortola</option>
                        <option value="-07:00">America/Vancouver</option>
                        <option value="-07:00">America/Whitehorse</option>
                        <option value="-05:00">America/Winnipeg</option>
                        <option value="-08:00">America/Yakutat</option>
                        <option value="-06:00">America/Yellowknife</option>
                        <option value="+08:00">Antarctica/Casey</option>
                        <option value="+07:00">Antarctica/Davis</option>
                        <option value="+10:00">Antarctica/DumontDUrville</option>
                        <option value="+11:00">Antarctica/Macquarie</option>
                        <option value="+05:00">Antarctica/Mawson</option>
                        <option value="+13:00">Antarctica/McMurdo</option>
                        <option value="-03:00">Antarctica/Palmer</option>
                        <option value="-03:00">Antarctica/Rothera</option>
                        <option value="+03:00">Antarctica/Syowa</option>
                        <option value=" 00:00">Antarctica/Troll</option>
                        <option value="+06:00">Antarctica/Vostok</option>
                        <option value="+01:00">Arctic/Longyearbyen</option>
                        <option value="+03:00">Asia/Aden</option>
                        <option value="+06:00">Asia/Almaty</option>
                        <option value="+02:00">Asia/Amman</option>
                        <option value="+12:00">Asia/Anadyr</option>
                        <option value="+05:00">Asia/Aqtau</option>
                        <option value="+05:00">Asia/Aqtobe</option>
                        <option value="+05:00">Asia/Ashgabat</option>
                        <option value="+05:00">Asia/Atyrau</option>
                        <option value="+03:00">Asia/Baghdad</option>
                        <option value="+03:00">Asia/Bahrain</option>
                        <option value="+04:00">Asia/Baku</option>
                        <option value="+07:00">Asia/Bangkok</option>
                        <option value="+07:00">Asia/Barnaul</option>
                        <option value="+02:00">Asia/Beirut</option>
                        <option value="+06:00">Asia/Bishkek</option>
                        <option value="+08:00">Asia/Brunei</option>
                        <option value="+09:00">Asia/Chita</option>
                        <option value="+08:00">Asia/Choibalsan</option>
                        <option value="+05:30">Asia/Colombo</option>
                        <option value="+02:00">Asia/Damascus</option>
                        <option value="+06:00">Asia/Dhaka</option>
                        <option value="+09:00">Asia/Dili</option>
                        <option value="+04:00">Asia/Dubai</option>
                        <option value="+05:00">Asia/Dushanbe</option>
                        <option value="+02:00">Asia/Famagusta</option>
                        <option value="+02:00">Asia/Gaza</option>
                        <option value="+02:00">Asia/Hebron</option>
                        <option value="+07:00">Asia/Ho_Chi_Minh</option>
                        <option value="+08:00">Asia/Hong_Kong</option>
                        <option value="+07:00">Asia/Hovd</option>
                        <option value="+08:00">Asia/Irkutsk</option>
                        <option value="+07:00">Asia/Jakarta</option>
                        <option value="+09:00">Asia/Jayapura</option>
                        <option value="+02:00">Asia/Jerusalem</option>
                        <option value="+04:30">Asia/Kabul</option>
                        <option value="+12:00">Asia/Kamchatka</option>
                        <option value="+05:00">Asia/Karachi</option>
                        <option value="+05:45">Asia/Kathmandu</option>
                        <option value="+09:00">Asia/Khandyga</option>
                        <option value="+05:30">Asia/Kolkata</option>
                        <option value="+07:00">Asia/Krasnoyarsk</option>
                        <option value="+08:00">Asia/Kuala_Lumpur</option>
                        <option value="+08:00">Asia/Kuching</option>
                        <option value="+03:00">Asia/Kuwait</option>
                        <option value="+08:00">Asia/Macau</option>
                        <option value="+11:00">Asia/Magadan</option>
                        <option value="+08:00">Asia/Makassar</option>
                        <option value="+08:00">Asia/Manila</option>
                        <option value="+04:00">Asia/Muscat</option>
                        <option value="+02:00">Asia/Nicosia</option>
                        <option value="+07:00">Asia/Novokuznetsk</option>
                        <option value="+07:00">Asia/Novosibirsk</option>
                        <option value="+06:00">Asia/Omsk</option>
                        <option value="+05:00">Asia/Oral</option>
                        <option value="+07:00">Asia/Phnom_Penh</option>
                        <option value="+07:00">Asia/Pontianak</option>
                        <option value="+09:00">Asia/Pyongyang</option>
                        <option value="+03:00">Asia/Qatar</option>
                        <option value="+06:00">Asia/Qyzylorda</option>
                        <option value="+03:00">Asia/Riyadh</option>
                        <option value="+11:00">Asia/Sakhalin</option>
                        <option value="+05:00">Asia/Samarkand</option>
                        <option value="+09:00">Asia/Seoul</option>
                        <option value="+08:00">Asia/Shanghai</option>
                        <option value="+08:00">Asia/Singapore</option>
                        <option value="+11:00">Asia/Srednekolymsk</option>
                        <option value="+08:00">Asia/Taipei</option>
                        <option value="+05:00">Asia/Tashkent</option>
                        <option value="+04:00">Asia/Tbilisi</option>
                        <option value="+03:30">Asia/Tehran</option>
                        <option value="+06:00">Asia/Thimphu</option>
                        <option value="+09:00">Asia/Tokyo</option>
                        <option value="+07:00">Asia/Tomsk</option>
                        <option value="+08:00">Asia/Ulaanbaatar</option>
                        <option value="+06:00">Asia/Urumqi</option>
                        <option value="+10:00">Asia/Ust-Nera</option>
                        <option value="+07:00">Asia/Vientiane</option>
                        <option value="+10:00">Asia/Vladivostok</option>
                        <option value="+09:00">Asia/Yakutsk</option>
                        <option value="+06:30">Asia/Yangon</option>
                        <option value="+05:00">Asia/Yekaterinburg</option>
                        <option value="+04:00">Asia/Yerevan</option>
                        <option value="-01:00">Atlantic/Azores</option>
                        <option value="-03:00">Atlantic/Bermuda</option>
                        <option value=" 00:00">Atlantic/Canary</option>
                        <option value="-01:00">Atlantic/Cape_Verde</option>
                        <option value=" 00:00">Atlantic/Faroe</option>
                        <option value=" 00:00">Atlantic/Madeira</option>
                        <option value=" 00:00">Atlantic/Reykjavik</option>
                        <option value="-02:00">Atlantic/South_Georgia</option>
                        <option value=" 00:00">Atlantic/St_Helena</option>
                        <option value="-03:00">Atlantic/Stanley</option>
                        <option value="+10:30">Australia/Adelaide</option>
                        <option value="+10:00">Australia/Brisbane</option>
                        <option value="+10:30">Australia/Broken_Hill</option>
                        <option value="+11:00">Australia/Currie</option>
                        <option value="+09:30">Australia/Darwin</option>
                        <option value="+08:45">Australia/Eucla</option>
                        <option value="+11:00">Australia/Hobart</option>
                        <option value="+10:00">Australia/Lindeman</option>
                        <option value="+11:00">Australia/Lord_Howe</option>
                        <option value="+11:00">Australia/Melbourne</option>
                        <option value="+08:00">Australia/Perth</option>
                        <option value="+11:00">Australia/Sydney</option>
                        <option value="+01:00">Europe/Amsterdam</option>
                        <option value="+01:00">Europe/Andorra</option>
                        <option value="+04:00">Europe/Astrakhan</option>
                        <option value="+02:00">Europe/Athens</option>
                        <option value="+01:00">Europe/Belgrade</option>
                        <option value="+01:00">Europe/Berlin</option>
                        <option value="+01:00">Europe/Bratislava</option>
                        <option value="+01:00">Europe/Brussels</option>
                        <option value="+02:00">Europe/Bucharest</option>
                        <option value="+01:00">Europe/Budapest</option>
                        <option value="+01:00">Europe/Busingen</option>
                        <option value="+02:00">Europe/Chisinau</option>
                        <option value="+01:00">Europe/Copenhagen</option>
                        <option value=" 00:00">Europe/Dublin</option>
                        <option value="+01:00">Europe/Gibraltar</option>
                        <option value=" 00:00">Europe/Guernsey</option>
                        <option value="+02:00">Europe/Helsinki</option>
                        <option value=" 00:00">Europe/Isle_of_Man</option>
                        <option value="+03:00">Europe/Istanbul</option>
                        <option value=" 00:00">Europe/Jersey</option>
                        <option value="+02:00">Europe/Kaliningrad</option>
                        <option value="+02:00">Europe/Kiev</option>
                        <option value="+03:00">Europe/Kirov</option>
                        <option value=" 00:00">Europe/Lisbon</option>
                        <option value="+01:00">Europe/Ljubljana</option>
                        <option value=" 00:00">Europe/London</option>
                        <option value="+01:00">Europe/Luxembourg</option>
                        <option value="+01:00">Europe/Madrid</option>
                        <option value="+01:00">Europe/Malta</option>
                        <option value="+02:00">Europe/Mariehamn</option>
                        <option value="+03:00">Europe/Minsk</option>
                        <option value="+01:00">Europe/Monaco</option>
                        <option value="+03:00">Europe/Moscow</option>
                        <option value="+01:00">Europe/Oslo</option>
                        <option value="+01:00">Europe/Paris</option>
                        <option value="+01:00">Europe/Podgorica</option>
                        <option value="+01:00">Europe/Prague</option>
                        <option value="+02:00">Europe/Riga</option>
                        <option value="+01:00">Europe/Rome</option>
                        <option value="+04:00">Europe/Samara</option>
                        <option value="+01:00">Europe/San_Marino</option>
                        <option value="+01:00">Europe/Sarajevo</option>
                        <option value="+04:00">Europe/Saratov</option>
                        <option value="+03:00">Europe/Simferopol</option>
                        <option value="+01:00">Europe/Skopje</option>
                        <option value="+02:00">Europe/Sofia</option>
                        <option value="+01:00">Europe/Stockholm</option>
                        <option value="+02:00">Europe/Tallinn</option>
                        <option value="+01:00">Europe/Tirane</option>
                        <option value="+04:00">Europe/Ulyanovsk</option>
                        <option value="+02:00">Europe/Uzhgorod</option>
                        <option value="+01:00">Europe/Vaduz</option>
                        <option value="+01:00">Europe/Vatican</option>
                        <option value="+01:00">Europe/Vienna</option>
                        <option value="+02:00">Europe/Vilnius</option>
                        <option value="+03:00">Europe/Volgograd</option>
                        <option value="+01:00">Europe/Warsaw</option>
                        <option value="+01:00">Europe/Zagreb</option>
                        <option value="+02:00">Europe/Zaporozhye</option>
                        <option value="+01:00">Europe/Zurich</option>
                        <option value="+03:00">Indian/Antananarivo</option>
                        <option value="+06:00">Indian/Chagos</option>
                        <option value="+07:00">Indian/Christmas</option>
                        <option value="+06:30">Indian/Cocos</option>
                        <option value="+03:00">Indian/Comoro</option>
                        <option value="+05:00">Indian/Kerguelen</option>
                        <option value="+04:00">Indian/Mahe</option>
                        <option value="+05:00">Indian/Maldives</option>
                        <option value="+04:00">Indian/Mauritius</option>
                        <option value="+03:00">Indian/Mayotte</option>
                        <option value="+04:00">Indian/Reunion</option>
                        <option value="+14:00">Pacific/Apia</option>
                        <option value="+13:00">Pacific/Auckland</option>
                        <option value="+11:00">Pacific/Bougainville</option>
                        <option value="+13:45">Pacific/Chatham</option>
                        <option value="+10:00">Pacific/Chuuk</option>
                        <option value="-05:00">Pacific/Easter</option>
                        <option value="+11:00">Pacific/Efate</option>
                        <option value="+13:00">Pacific/Enderbury</option>
                        <option value="+13:00">Pacific/Fakaofo</option>
                        <option value="+12:00">Pacific/Fiji</option>
                        <option value="+12:00">Pacific/Funafuti</option>
                        <option value="-06:00">Pacific/Galapagos</option>
                        <option value="-09:00">Pacific/Gambier</option>
                        <option value="+11:00">Pacific/Guadalcanal</option>
                        <option value="+10:00">Pacific/Guam</option>
                        <option value="-10:00">Pacific/Honolulu</option>
                        <option value="+14:00">Pacific/Kiritimati</option>
                        <option value="+11:00">Pacific/Kosrae</option>
                        <option value="+12:00">Pacific/Kwajalein</option>
                        <option value="+12:00">Pacific/Majuro</option>
                        <option value="-09:30">Pacific/Marquesas</option>
                        <option value="-11:00">Pacific/Midway</option>
                        <option value="+12:00">Pacific/Nauru</option>
                        <option value="-11:00">Pacific/Niue</option>
                        <option value="+11:00">Pacific/Norfolk</option>
                        <option value="+11:00">Pacific/Noumea</option>
                        <option value="-11:00">Pacific/Pago_Pago</option>
                        <option value="+09:00">Pacific/Palau</option>
                        <option value="-08:00">Pacific/Pitcairn</option>
                        <option value="+11:00">Pacific/Pohnpei</option>
                        <option value="+10:00">Pacific/Port_Moresby</option>
                        <option value="-10:00">Pacific/Rarotonga</option>
                        <option value="+10:00">Pacific/Saipan</option>
                        <option value="-10:00">Pacific/Tahiti</option>
                        <option value="+12:00">Pacific/Tarawa</option>
                        <option value="+13:00">Pacific/Tongatapu</option>
                        <option value="+12:00">Pacific/Wake</option>
                        <option value="+12:00">Pacific/Wallis</option>
                        <option value=" 00:00">UTC</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Country</label>
                <select name="country" class="form-control" required="">
                    <option value="">Select Country</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
               </select>
            </div>

            <div class="half_width float_left" style="display: none;">
                <label class="field_title">State</label>
                <select name="state" class="form-control">
                    <option value="">Select State</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['state']; ?>"><?php echo $val['Tax']['state']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">City</label>
                <select name="city" class="form-control" required="">
                    <option value="">Select City</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['city']; ?>"><?php echo $val['Tax']['city']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Tax</label>
                <select name="tax_id" class="form-control" required="">
                    <option value="">Select Tax</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['id']; ?>"><?php echo $val['Tax']['tax']; ?> %</option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Location Long</label>
                <input name="long" type="text" required>
                
                
            </div>

            <div class="half_width float_left">
                <label class="field_title">Location Lat</label>
                <input name="lat" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Zip Code</label>
                <input name="zip" type="text" required>
            </div>
            
            <input type="hidden" name="currencyid" value="<?php echo $currency['msg'][0]['Currency']['id'] ?>" >
            <div class="half_width float_left">
                <label class="field_title">Minimum Order Price</label>
                <select name="min_order_price" class="form-control" required="">
                    <option value="">Select Amount</option>

                    <?php

                        for($i = 1; $i<=999; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i.' '; echo $currency['msg'][0]['Currency']['symbol']; ?></option>
                            <?php
                        }

                    ?>

               </select>

            </div>



            <div class="half_width float_right">
                <label class="field_title">Free Delivery Range</label>
                <select name="delivery_free_range" class="form-control" required="">
                    <option value="">Select KM Range</option>
                    <option value="0">Non Free Delivery</option>
                    <?php

                        for($i = 1; $i<=49; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> KM</option>
                            <?php
                        }

                    ?>

               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">AVG Food Prepation Time</label>
                <select name="preparation_time" class="form-control" required="">
                    <option value="">Select Minutes</option>
                    <option value="5">5 min</option>
                    <option value="10">10 min</option>
                    <option value="15">15 min</option>
                    <option value="20">20 min</option>
                    <option value="25">25 min</option>
                    <option value="30">30 min</option>
                    <option value="35">35 min</option>
                    <option value="40">40 min</option>
                    <option value="45">45 min</option>
                    <option value="50">50 min</option>
                    <option value="55">55 min</option>
                    <option value="60">60 min</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Tax implementation</label>
                <select name="tax_free" class="form-control" required="">
                    <option value="1">No Tax will implement</option>
                    <option value="0">Tax % will implement</option>
                </select>
            </div>

            <div class="clear_both"></div>





            <h3 style="font-weight: 300;" align="center">Restaurant Open/Close Timing</h3>
            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Sunday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                        for($i = 0; $i<=23; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                            <?php
                        }


                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Monday" style="background: #e1e1e11a;" readonly >
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Tuesday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Wednesday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }
                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }
                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Thursday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }
                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Friday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }

                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <br>
            <div class="full_width">
                <input name="day[]" type="text" value="Saturday" style="background: #e1e1e11a;" readonly>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Opening Time</label>
                <select name="opening_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php
                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="0"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }
                    ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Closing Time</label>
                <select name="closing_time[]" class="form-control" required="">
                    <option value="">Select Time</option>
                    <?php

                    for($i = 0; $i<=23; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>:00:00" <?php if($i=="23"){echo "selected";} ?>><?php echo $i; ?>:00:00</option>
                        <?php
                    }
                    ?>
               </select>
            </div>
            <div class="clear_both"></div>

            <h3 style="font-weight: 300;" align="center">Restaurant Login information</h3>

            <div class="full_width">
                <label class="field_title">First Name</label>
                <input name="first_name" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Last Name</label>
                <input name="last_name" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Email</label>
                <input name="email" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Password</label>
                <input name="password" type="text" required>
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>

        </form>
        </div>
    </div>
    <?php

}else 
if (@$_GET['action'] == "addProduct")
{
    
    $store_id = $_GET['store_id'];
    $userid = $_GET['userid'];

    //$url=$baseurl . 'showCategories';
    $url=$baseurl . 'showCategoriesStore';
    $data = [];

    $category=@curl_request($data,$url);
        
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Product</h2>

        <div style="height:450px; overflow:scroll;">

        <form action="dashboard.php?p=manageProducts&action=addProduct" method="post" enctype="multipart/form-data">
            <div class="qr-el" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">New Product</span>
                    </div>
                </label>
                <input name="upload_image" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" required="required">
            </div>
<input type="hidden" name="store_id" value="<?php echo $store_id;?>">
<input type="hidden" name="userid" value="<?php echo $userid;?>">
            <div style="clear:both;"></div>


            <div class="full_width float_left">
                <label class="field_title">Product Title</label>
                <input name="product_title" type="text" required>
            </div>


            <div class="full_width clear_both">
                <label class="field_title">Product Description</label>
                <textarea name="product_description" type="text" required></textarea>
            </div>

            <!-- <div class="half_width float_left">
                <label class="field_title">Speciality</label>
                <input name="speciality" type="text" required>
            </div> -->

            <div class="full_width">
                <label class="field_title">Categories</label>
                <select name="category_id" class="form-control category-dropdown" id="category_id" onchange="selectCategory(this.value,'1')" required="">
                    <option value="">Select Categories</option>

                    <?php  foreach( $category['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Category']['id']; ?>"><?php echo $val['Category']['category']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>
            <span id="dataRecived_1"></span>
            
            <div class="full_width">
                <label class="field_title">Price</label>
                <input name="price" type="text" required>
            </div>
           
            <div class="full_width">
                <label class="field_title">Sale Price</label>
                <input name="sale_price" type="text" required>
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>

        </form>
        </div>
    </div>
    <?php

}else
if(@$_GET['action'] == "selectCategory")
{
    $id         = $_GET['id'];
    $level      = $_GET['level'];
    $next_level = $level+1;
    
    $url    = $baseurl . 'showCategoriesNew';

    $data = array(
        "level" => $id
    );

    $json_data=@curl_request($data,$url);
    
    $json_data=$json_data['msg'];
  //  print_r($json_data);exit;
    $countData=count($json_data);
    if($countData>0)
    {
        ?>
            <div class="full_width">
                <label class="field_title">Select Sub Category</label>
                <select name="category_id" id="category_id" class="form-control" onchange="selectCategory(this.value,'<?php echo $next_level;?>')" required="">
                    <option value="">Select Sub Category</option>
                    <?php  foreach( $json_data as $str => $val ): ?>
    
                        <option value="<?php echo $val['Category']['id']; ?>" ><?php echo $val['Category']['category']; ?></option>
    
                    <?php endforeach; ?>
               </select>
            </div>
            <span id="dataRecived_<?php echo $next_level;?>"></span>
        <?php
    }

}else
if (@$_GET['action'] == "editProduct")
{
    
    $id = $_GET['id'];
    $store_id = $_GET['store_id'];

    $url=$baseurl . 'getProduct';
    $p_data = array(
        'id'    => $id,
        'store_id'  => $store_id
    );
    $pro=@curl_request($p_data,$url);
    $product = $pro['msg'];
//print_r($product);exit;
    //$url=$baseurl . 'showCategories';
    $url=$baseurl . 'showCategoriesStore';
    $data = [];

    $category=@curl_request($data,$url);
        
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Edit Product</h2>

        <div style="height:450px; overflow:scroll;">

        <form action="dashboard.php?p=manageProducts&action=addProduct" method="post" enctype="multipart/form-data">
            <div class="qr-el" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">New Product</span>
                    </div>
                </label>
                <input name="upload_image" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" required="required">
            </div>
<input type="hidden" name="store_id" value="<?php echo $store_id;?>">
<input type="hidden" name="id" value="<?php echo $id;?>">
            <div style="clear:both;"></div>


            <div class="full_width float_left">
                <label class="field_title">Product Title</label>
                <input name="product_title" type="text" value="<?php echo $product[0]['re_i']['product_title']?>" required>
            </div>


            <div class="full_width clear_both">
                <label class="field_title">Product Description</label>
                <textarea name="product_description" required><?php echo $product[0]['re_i']['description']?></textarea>
            </div>

            <!-- <div class="half_width float_left">
                <label class="field_title">Speciality</label>
                <input name="speciality" type="text" required>
            </div> -->

            <!-- <div class="full_width">
                <label class="field_title">Categories</label>
                <select name="category_id" class="form-control category-dropdown" required>
                    <option value="">Select Categories</option>

                    <?php  //foreach( $category['msg'] as $str => $val ): ?>

                        <option value="<?php //echo $val['Category']['id']; ?>" <?php //if($product[0]['c']['category_id'] == $val['Category']['id']){ echo "selected";}?>><?php //echo $val['Category']['category']; ?></option>

                    <?php //endforeach; ?>
               </select>
            </div> -->

            <div class="full_width">
                <label class="field_title">Categories</label>
                <input name="category_name" type="text" value="<?php echo $product[0]['c']['category']?>" required readonly>
                <input name="category_id" type="hidden" value="<?php echo $product[0]['c']['category_id']?>" readonly>
            </div>
            
            <div class="full_width">
                <label class="field_title">Price</label>
                <input name="price" type="text" value="<?php echo $product[0]['re_i']['price']?>" required>
            </div>
           
            <div class="full_width">
                <label class="field_title">Sale Price</label>
                <input name="sale_price" type="text" value="<?php echo $product[0]['re_i']['p_price']?>" required>
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Update
                </button>
            </div>

        </form>
        </div>
    </div>
    <?php

}else
if (@$_GET['action'] == "addStore")
{
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
    
    
    if(count($currency['msg'])=="0" || count($currency['msg'])=="")
    {
        echo"<em>Note: You have to add currency first <a href='dashboard.php?p=manageCurrency' class='redLink'>Add Currency</a></em>";
        die();
    }
    else
    if(count($tax['msg'])=="0" || count($tax['msg'])=="")
    {
        echo"<em>Note: You have to add tax information first <a href='dashboard.php?p=taxSetting' class='redLink'>Add Tax</a></em>";
        die();
    }
    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Store</h2>

        <div style="height:450px; overflow:scroll;">

        <form action="dashboard.php?p=stores&action=addStore" method="post" enctype="multipart/form-data">
            <div class="qr-el qr-el-3" id="logoPreview"  style="min-height: auto; float:left; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="logoPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="logouploadText">
                        <span style="color:#F69518;">Upload Logo</span><br>
                        Size 150x150
                    </div>
                </label>
                <input name="upload_image" class="" id="uploadFile" type="file" onchange="return Upload_image_desktop()" style="width: 100%; margin-top: 20px;" required="required">
            </div>

            <div class="qr-el qr-el-3" id="coverPreview" style="min-height: auto; float:right; box-shadow: 2px 0px 30px 5px rgba(0, 0, 0, 0.03);">
                <label for="uploadFileCover" class="hoviringdell uploadBox" id="uploadTriggerCover" style="height: 110px;">
                    <img src="frontend_public/uploads/attachment/upload.png" id="coverPlaceholderimage" style="width: 38px;">
                    <div class="uploadText" style="font-size: 12px;" id="coveruploadText">
                        <span style="color:#F69518;">Upload Cover</span><br>
                        Size no more than 900x270
                    </div>
                </label>
                <input name="Cover_upload_image" class="" id="uploadFileCover" type="file" onchange="return Upload_image_desktopCover()" style="width: 100%; margin-top: 20px;" required="required">
            </div>
            <div style="clear:both;"></div>


            <div class="half_width float_left">
                <label class="field_title">Store Name</label>
                <input name="name" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Slogan</label>
                <input name="slogan" type="text" required>
            </div>

            <div class="full_width clear_both">
                <label class="field_title">About</label>
                <textarea name="about" type="text" required></textarea>
            </div>

            <!-- <div class="half_width float_left">
                <label class="field_title">Speciality</label>
                <input name="speciality" type="text" required>
            </div> -->

            <div class="half_width float_left">
                <label class="field_title">Categories</label>
                <select name="category_id[]" class="form-control category-dropdown" required="" multiple="multiple" style="height: 110px;">
                    <option value="">Select Categories</option>

                    <?php  foreach( $category['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Category']['id']; ?>"><?php echo $val['Category']['category']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_right" style="height: 132px;">
                <label class="field_title">Phone</label>
                <input name="phone" type="text" required>
            </div>
            
            <div class="full_width">
                <label class="field_title">Admin Commission (%)</label>
                <input name="admin_commission" type="text" required>
            </div>

            <div class="half_width float_left" style="display:none;">
                <label class="field_title">Timezone</label>
                <select name="timezone" class="form-control">
                    <option value="0">Select timezone</option>
                        <option value=" 00:00">Africa/Abidjan</option>
                        <option value=" 00:00">Africa/Accra</option>
                        <option value="+03:00">Africa/Addis_Ababa</option>
                        <option value="+01:00">Africa/Algiers</option>
                        <option value="+03:00">Africa/Asmara</option>
                        <option value=" 00:00">Africa/Bamako</option>
                        <option value="+01:00">Africa/Bangui</option>
                        <option value=" 00:00">Africa/Banjul</option>
                        <option value=" 00:00">Africa/Bissau</option>
                        <option value="+02:00">Africa/Blantyre</option>
                        <option value="+01:00">Africa/Brazzaville</option>
                        <option value="+02:00">Africa/Bujumbura</option>
                        <option value="+02:00">Africa/Cairo</option>
                        <option value=" 00:00">Africa/Casablanca</option>
                        <option value="+01:00">Africa/Ceuta</option>
                        <option value=" 00:00">Africa/Conakry</option>
                        <option value=" 00:00">Africa/Dakar</option>
                        <option value="+03:00">Africa/Dar_es_Salaam</option>
                        <option value="+03:00">Africa/Djibouti</option>
                        <option value="+01:00">Africa/Douala</option>
                        <option value=" 00:00">Africa/El_Aaiun</option>
                        <option value=" 00:00">Africa/Freetown</option>
                        <option value="+02:00">Africa/Gaborone</option>
                        <option value="+02:00">Africa/Harare</option>
                        <option value="+02:00">Africa/Johannesburg</option>
                        <option value="+03:00">Africa/Juba</option>
                        <option value="+03:00">Africa/Kampala</option>
                        <option value="+02:00">Africa/Khartoum</option>
                        <option value="+02:00">Africa/Kigali</option>
                        <option value="+01:00">Africa/Kinshasa</option>
                        <option value="+01:00">Africa/Lagos</option>
                        <option value="+01:00">Africa/Libreville</option>
                        <option value=" 00:00">Africa/Lome</option>
                        <option value="+01:00">Africa/Luanda</option>
                        <option value="+02:00">Africa/Lubumbashi</option>
                        <option value="+02:00">Africa/Lusaka</option>
                        <option value="+01:00">Africa/Malabo</option>
                        <option value="+02:00">Africa/Maputo</option>
                        <option value="+02:00">Africa/Maseru</option>
                        <option value="+02:00">Africa/Mbabane</option>
                        <option value="+03:00">Africa/Mogadishu</option>
                        <option value=" 00:00">Africa/Monrovia</option>
                        <option value="+03:00">Africa/Nairobi</option>
                        <option value="+01:00">Africa/Ndjamena</option>
                        <option value="+01:00">Africa/Niamey</option>
                        <option value=" 00:00">Africa/Nouakchott</option>
                        <option value=" 00:00">Africa/Ouagadougou</option>
                        <option value="+01:00">Africa/Porto-Novo</option>
                        <option value="+01:00">Africa/Sao_Tome</option>
                        <option value="+02:00">Africa/Tripoli</option>
                        <option value="+01:00">Africa/Tunis</option>
                        <option value="+02:00">Africa/Windhoek</option>
                        <option value="-09:00">America/Adak</option>
                        <option value="-08:00">America/Anchorage</option>
                        <option value="-04:00">America/Anguilla</option>
                        <option value="-04:00">America/Antigua</option>
                        <option value="-03:00">America/Araguaina</option>
                        <option value="-03:00">America/Argentina/Buenos_Aires</option>
                        <option value="-03:00">America/Argentina/Catamarca</option>
                        <option value="-03:00">America/Argentina/Cordoba</option>
                        <option value="-03:00">America/Argentina/Jujuy</option>
                        <option value="-03:00">America/Argentina/La_Rioja</option>
                        <option value="-03:00">America/Argentina/Mendoza</option>
                        <option value="-03:00">America/Argentina/Rio_Gallegos</option>
                        <option value="-03:00">America/Argentina/Salta</option>
                        <option value="-03:00">America/Argentina/San_Juan</option>
                        <option value="-03:00">America/Argentina/San_Luis</option>
                        <option value="-03:00">America/Argentina/Tucuman</option>
                        <option value="-03:00">America/Argentina/Ushuaia</option>
                        <option value="-04:00">America/Aruba</option>
                        <option value="-03:00">America/Asuncion</option>
                        <option value="-05:00">America/Atikokan</option>
                        <option value="-03:00">America/Bahia</option>
                        <option value="-06:00">America/Bahia_Banderas</option>
                        <option value="-04:00">America/Barbados</option>
                        <option value="-03:00">America/Belem</option>
                        <option value="-06:00">America/Belize</option>
                        <option value="-04:00">America/Blanc-Sablon</option>
                        <option value="-04:00">America/Boa_Vista</option>
                        <option value="-05:00">America/Bogota</option>
                        <option value="-06:00">America/Boise</option>
                        <option value="-06:00">America/Cambridge_Bay</option>
                        <option value="-04:00">America/Campo_Grande</option>
                        <option value="-05:00">America/Cancun</option>
                        <option value="-04:00">America/Caracas</option>
                        <option value="-03:00">America/Cayenne</option>
                        <option value="-05:00">America/Cayman</option>
                        <option value="-05:00">America/Chicago</option>
                        <option value="-07:00">America/Chihuahua</option>
                        <option value="-06:00">America/Costa_Rica</option>
                        <option value="-07:00">America/Creston</option>
                        <option value="-04:00">America/Cuiaba</option>
                        <option value="-04:00">America/Curacao</option>
                        <option value=" 00:00">America/Danmarkshavn</option>
                        <option value="-07:00">America/Dawson</option>
                        <option value="-07:00">America/Dawson_Creek</option>
                        <option value="-06:00">America/Denver</option>
                        <option value="-04:00">America/Detroit</option>
                        <option value="-04:00">America/Dominica</option>
                        <option value="-06:00">America/Edmonton</option>
                        <option value="-05:00">America/Eirunepe</option>
                        <option value="-06:00">America/El_Salvador</option>
                        <option value="-07:00">America/Fort_Nelson</option>
                        <option value="-03:00">America/Fortaleza</option>
                        <option value="-03:00">America/Glace_Bay</option>
                        <option value="-03:00">America/Godthab</option>
                        <option value="-03:00">America/Goose_Bay</option>
                        <option value="-04:00">America/Grand_Turk</option>
                        <option value="-04:00">America/Grenada</option>
                        <option value="-04:00">America/Guadeloupe</option>
                        <option value="-06:00">America/Guatemala</option>
                        <option value="-05:00">America/Guayaquil</option>
                        <option value="-04:00">America/Guyana</option>
                        <option value="-03:00">America/Halifax</option>
                        <option value="-04:00">America/Havana</option>
                        <option value="-07:00">America/Hermosillo</option>
                        <option value="-04:00">America/Indiana/Indianapolis</option>
                        <option value="-05:00">America/Indiana/Knox</option>
                        <option value="-04:00">America/Indiana/Marengo</option>
                        <option value="-04:00">America/Indiana/Petersburg</option>
                        <option value="-05:00">America/Indiana/Tell_City</option>
                        <option value="-04:00">America/Indiana/Vevay</option>
                        <option value="-04:00">America/Indiana/Vincennes</option>
                        <option value="-04:00">America/Indiana/Winamac</option>
                        <option value="-06:00">America/Inuvik</option>
                        <option value="-04:00">America/Iqaluit</option>
                        <option value="-05:00">America/Jamaica</option>
                        <option value="-08:00">America/Juneau</option>
                        <option value="-04:00">America/Kentucky/Louisville</option>
                        <option value="-04:00">America/Kentucky/Monticello</option>
                        <option value="-04:00">America/Kralendijk</option>
                        <option value="-04:00">America/La_Paz</option>
                        <option value="-05:00">America/Lima</option>
                        <option value="-07:00">America/Los_Angeles</option>
                        <option value="-04:00">America/Lower_Princes</option>
                        <option value="-03:00">America/Maceio</option>
                        <option value="-06:00">America/Managua</option>
                        <option value="-04:00">America/Manaus</option>
                        <option value="-04:00">America/Marigot</option>
                        <option value="-04:00">America/Martinique</option>
                        <option value="-05:00">America/Matamoros</option>
                        <option value="-07:00">America/Mazatlan</option>
                        <option value="-05:00">America/Menominee</option>
                        <option value="-06:00">America/Merida</option>
                        <option value="-08:00">America/Metlakatla</option>
                        <option value="-06:00">America/Mexico_City</option>
                        <option value="-02:00">America/Miquelon</option>
                        <option value="-03:00">America/Moncton</option>
                        <option value="-06:00">America/Monterrey</option>
                        <option value="-03:00">America/Montevideo</option>
                        <option value="-04:00">America/Montserrat</option>
                        <option value="-04:00">America/Nassau</option>
                        <option value="-04:00">America/New_York</option>
                        <option value="-04:00">America/Nipigon</option>
                        <option value="-08:00">America/Nome</option>
                        <option value="-02:00">America/Noronha</option>
                        <option value="-05:00">America/North_Dakota/Beulah</option>
                        <option value="-05:00">America/North_Dakota/Center</option>
                        <option value="-05:00">America/North_Dakota/New_Salem</option>
                        <option value="-06:00">America/Ojinaga</option>
                        <option value="-05:00">America/Panama</option>
                        <option value="-04:00">America/Pangnirtung</option>
                        <option value="-03:00">America/Paramaribo</option>
                        <option value="-07:00">America/Phoenix</option>
                        <option value="-04:00">America/Port-au-Prince</option>
                        <option value="-04:00">America/Port_of_Spain</option>
                        <option value="-04:00">America/Porto_Velho</option>
                        <option value="-04:00">America/Puerto_Rico</option>
                        <option value="-03:00">America/Punta_Arenas</option>
                        <option value="-05:00">America/Rainy_River</option>
                        <option value="-05:00">America/Rankin_Inlet</option>
                        <option value="-03:00">America/Recife</option>
                        <option value="-06:00">America/Regina</option>
                        <option value="-05:00">America/Resolute</option>
                        <option value="-05:00">America/Rio_Branco</option>
                        <option value="-03:00">America/Santarem</option>
                        <option value="-03:00">America/Santiago</option>
                        <option value="-04:00">America/Santo_Domingo</option>
                        <option value="-03:00">America/Sao_Paulo</option>
                        <option value="-01:00">America/Scoresbysund</option>
                        <option value="-08:00">America/Sitka</option>
                        <option value="-04:00">America/St_Barthelemy</option>
                        <option value="-02:30">America/St_Johns</option>
                        <option value="-04:00">America/St_Kitts</option>
                        <option value="-04:00">America/St_Lucia</option>
                        <option value="-04:00">America/St_Thomas</option>
                        <option value="-04:00">America/St_Vincent</option>
                        <option value="-06:00">America/Swift_Current</option>
                        <option value="-06:00">America/Tegucigalpa</option>
                        <option value="-03:00">America/Thule</option>
                        <option value="-04:00">America/Thunder_Bay</option>
                        <option value="-07:00">America/Tijuana</option>
                        <option value="-04:00">America/Toronto</option>
                        <option value="-04:00">America/Tortola</option>
                        <option value="-07:00">America/Vancouver</option>
                        <option value="-07:00">America/Whitehorse</option>
                        <option value="-05:00">America/Winnipeg</option>
                        <option value="-08:00">America/Yakutat</option>
                        <option value="-06:00">America/Yellowknife</option>
                        <option value="+08:00">Antarctica/Casey</option>
                        <option value="+07:00">Antarctica/Davis</option>
                        <option value="+10:00">Antarctica/DumontDUrville</option>
                        <option value="+11:00">Antarctica/Macquarie</option>
                        <option value="+05:00">Antarctica/Mawson</option>
                        <option value="+13:00">Antarctica/McMurdo</option>
                        <option value="-03:00">Antarctica/Palmer</option>
                        <option value="-03:00">Antarctica/Rothera</option>
                        <option value="+03:00">Antarctica/Syowa</option>
                        <option value=" 00:00">Antarctica/Troll</option>
                        <option value="+06:00">Antarctica/Vostok</option>
                        <option value="+01:00">Arctic/Longyearbyen</option>
                        <option value="+03:00">Asia/Aden</option>
                        <option value="+06:00">Asia/Almaty</option>
                        <option value="+02:00">Asia/Amman</option>
                        <option value="+12:00">Asia/Anadyr</option>
                        <option value="+05:00">Asia/Aqtau</option>
                        <option value="+05:00">Asia/Aqtobe</option>
                        <option value="+05:00">Asia/Ashgabat</option>
                        <option value="+05:00">Asia/Atyrau</option>
                        <option value="+03:00">Asia/Baghdad</option>
                        <option value="+03:00">Asia/Bahrain</option>
                        <option value="+04:00">Asia/Baku</option>
                        <option value="+07:00">Asia/Bangkok</option>
                        <option value="+07:00">Asia/Barnaul</option>
                        <option value="+02:00">Asia/Beirut</option>
                        <option value="+06:00">Asia/Bishkek</option>
                        <option value="+08:00">Asia/Brunei</option>
                        <option value="+09:00">Asia/Chita</option>
                        <option value="+08:00">Asia/Choibalsan</option>
                        <option value="+05:30">Asia/Colombo</option>
                        <option value="+02:00">Asia/Damascus</option>
                        <option value="+06:00">Asia/Dhaka</option>
                        <option value="+09:00">Asia/Dili</option>
                        <option value="+04:00">Asia/Dubai</option>
                        <option value="+05:00">Asia/Dushanbe</option>
                        <option value="+02:00">Asia/Famagusta</option>
                        <option value="+02:00">Asia/Gaza</option>
                        <option value="+02:00">Asia/Hebron</option>
                        <option value="+07:00">Asia/Ho_Chi_Minh</option>
                        <option value="+08:00">Asia/Hong_Kong</option>
                        <option value="+07:00">Asia/Hovd</option>
                        <option value="+08:00">Asia/Irkutsk</option>
                        <option value="+07:00">Asia/Jakarta</option>
                        <option value="+09:00">Asia/Jayapura</option>
                        <option value="+02:00">Asia/Jerusalem</option>
                        <option value="+04:30">Asia/Kabul</option>
                        <option value="+12:00">Asia/Kamchatka</option>
                        <option value="+05:00">Asia/Karachi</option>
                        <option value="+05:45">Asia/Kathmandu</option>
                        <option value="+09:00">Asia/Khandyga</option>
                        <option value="+05:30">Asia/Kolkata</option>
                        <option value="+07:00">Asia/Krasnoyarsk</option>
                        <option value="+08:00">Asia/Kuala_Lumpur</option>
                        <option value="+08:00">Asia/Kuching</option>
                        <option value="+03:00">Asia/Kuwait</option>
                        <option value="+08:00">Asia/Macau</option>
                        <option value="+11:00">Asia/Magadan</option>
                        <option value="+08:00">Asia/Makassar</option>
                        <option value="+08:00">Asia/Manila</option>
                        <option value="+04:00">Asia/Muscat</option>
                        <option value="+02:00">Asia/Nicosia</option>
                        <option value="+07:00">Asia/Novokuznetsk</option>
                        <option value="+07:00">Asia/Novosibirsk</option>
                        <option value="+06:00">Asia/Omsk</option>
                        <option value="+05:00">Asia/Oral</option>
                        <option value="+07:00">Asia/Phnom_Penh</option>
                        <option value="+07:00">Asia/Pontianak</option>
                        <option value="+09:00">Asia/Pyongyang</option>
                        <option value="+03:00">Asia/Qatar</option>
                        <option value="+06:00">Asia/Qyzylorda</option>
                        <option value="+03:00">Asia/Riyadh</option>
                        <option value="+11:00">Asia/Sakhalin</option>
                        <option value="+05:00">Asia/Samarkand</option>
                        <option value="+09:00">Asia/Seoul</option>
                        <option value="+08:00">Asia/Shanghai</option>
                        <option value="+08:00">Asia/Singapore</option>
                        <option value="+11:00">Asia/Srednekolymsk</option>
                        <option value="+08:00">Asia/Taipei</option>
                        <option value="+05:00">Asia/Tashkent</option>
                        <option value="+04:00">Asia/Tbilisi</option>
                        <option value="+03:30">Asia/Tehran</option>
                        <option value="+06:00">Asia/Thimphu</option>
                        <option value="+09:00">Asia/Tokyo</option>
                        <option value="+07:00">Asia/Tomsk</option>
                        <option value="+08:00">Asia/Ulaanbaatar</option>
                        <option value="+06:00">Asia/Urumqi</option>
                        <option value="+10:00">Asia/Ust-Nera</option>
                        <option value="+07:00">Asia/Vientiane</option>
                        <option value="+10:00">Asia/Vladivostok</option>
                        <option value="+09:00">Asia/Yakutsk</option>
                        <option value="+06:30">Asia/Yangon</option>
                        <option value="+05:00">Asia/Yekaterinburg</option>
                        <option value="+04:00">Asia/Yerevan</option>
                        <option value="-01:00">Atlantic/Azores</option>
                        <option value="-03:00">Atlantic/Bermuda</option>
                        <option value=" 00:00">Atlantic/Canary</option>
                        <option value="-01:00">Atlantic/Cape_Verde</option>
                        <option value=" 00:00">Atlantic/Faroe</option>
                        <option value=" 00:00">Atlantic/Madeira</option>
                        <option value=" 00:00">Atlantic/Reykjavik</option>
                        <option value="-02:00">Atlantic/South_Georgia</option>
                        <option value=" 00:00">Atlantic/St_Helena</option>
                        <option value="-03:00">Atlantic/Stanley</option>
                        <option value="+10:30">Australia/Adelaide</option>
                        <option value="+10:00">Australia/Brisbane</option>
                        <option value="+10:30">Australia/Broken_Hill</option>
                        <option value="+11:00">Australia/Currie</option>
                        <option value="+09:30">Australia/Darwin</option>
                        <option value="+08:45">Australia/Eucla</option>
                        <option value="+11:00">Australia/Hobart</option>
                        <option value="+10:00">Australia/Lindeman</option>
                        <option value="+11:00">Australia/Lord_Howe</option>
                        <option value="+11:00">Australia/Melbourne</option>
                        <option value="+08:00">Australia/Perth</option>
                        <option value="+11:00">Australia/Sydney</option>
                        <option value="+01:00">Europe/Amsterdam</option>
                        <option value="+01:00">Europe/Andorra</option>
                        <option value="+04:00">Europe/Astrakhan</option>
                        <option value="+02:00">Europe/Athens</option>
                        <option value="+01:00">Europe/Belgrade</option>
                        <option value="+01:00">Europe/Berlin</option>
                        <option value="+01:00">Europe/Bratislava</option>
                        <option value="+01:00">Europe/Brussels</option>
                        <option value="+02:00">Europe/Bucharest</option>
                        <option value="+01:00">Europe/Budapest</option>
                        <option value="+01:00">Europe/Busingen</option>
                        <option value="+02:00">Europe/Chisinau</option>
                        <option value="+01:00">Europe/Copenhagen</option>
                        <option value=" 00:00">Europe/Dublin</option>
                        <option value="+01:00">Europe/Gibraltar</option>
                        <option value=" 00:00">Europe/Guernsey</option>
                        <option value="+02:00">Europe/Helsinki</option>
                        <option value=" 00:00">Europe/Isle_of_Man</option>
                        <option value="+03:00">Europe/Istanbul</option>
                        <option value=" 00:00">Europe/Jersey</option>
                        <option value="+02:00">Europe/Kaliningrad</option>
                        <option value="+02:00">Europe/Kiev</option>
                        <option value="+03:00">Europe/Kirov</option>
                        <option value=" 00:00">Europe/Lisbon</option>
                        <option value="+01:00">Europe/Ljubljana</option>
                        <option value=" 00:00">Europe/London</option>
                        <option value="+01:00">Europe/Luxembourg</option>
                        <option value="+01:00">Europe/Madrid</option>
                        <option value="+01:00">Europe/Malta</option>
                        <option value="+02:00">Europe/Mariehamn</option>
                        <option value="+03:00">Europe/Minsk</option>
                        <option value="+01:00">Europe/Monaco</option>
                        <option value="+03:00">Europe/Moscow</option>
                        <option value="+01:00">Europe/Oslo</option>
                        <option value="+01:00">Europe/Paris</option>
                        <option value="+01:00">Europe/Podgorica</option>
                        <option value="+01:00">Europe/Prague</option>
                        <option value="+02:00">Europe/Riga</option>
                        <option value="+01:00">Europe/Rome</option>
                        <option value="+04:00">Europe/Samara</option>
                        <option value="+01:00">Europe/San_Marino</option>
                        <option value="+01:00">Europe/Sarajevo</option>
                        <option value="+04:00">Europe/Saratov</option>
                        <option value="+03:00">Europe/Simferopol</option>
                        <option value="+01:00">Europe/Skopje</option>
                        <option value="+02:00">Europe/Sofia</option>
                        <option value="+01:00">Europe/Stockholm</option>
                        <option value="+02:00">Europe/Tallinn</option>
                        <option value="+01:00">Europe/Tirane</option>
                        <option value="+04:00">Europe/Ulyanovsk</option>
                        <option value="+02:00">Europe/Uzhgorod</option>
                        <option value="+01:00">Europe/Vaduz</option>
                        <option value="+01:00">Europe/Vatican</option>
                        <option value="+01:00">Europe/Vienna</option>
                        <option value="+02:00">Europe/Vilnius</option>
                        <option value="+03:00">Europe/Volgograd</option>
                        <option value="+01:00">Europe/Warsaw</option>
                        <option value="+01:00">Europe/Zagreb</option>
                        <option value="+02:00">Europe/Zaporozhye</option>
                        <option value="+01:00">Europe/Zurich</option>
                        <option value="+03:00">Indian/Antananarivo</option>
                        <option value="+06:00">Indian/Chagos</option>
                        <option value="+07:00">Indian/Christmas</option>
                        <option value="+06:30">Indian/Cocos</option>
                        <option value="+03:00">Indian/Comoro</option>
                        <option value="+05:00">Indian/Kerguelen</option>
                        <option value="+04:00">Indian/Mahe</option>
                        <option value="+05:00">Indian/Maldives</option>
                        <option value="+04:00">Indian/Mauritius</option>
                        <option value="+03:00">Indian/Mayotte</option>
                        <option value="+04:00">Indian/Reunion</option>
                        <option value="+14:00">Pacific/Apia</option>
                        <option value="+13:00">Pacific/Auckland</option>
                        <option value="+11:00">Pacific/Bougainville</option>
                        <option value="+13:45">Pacific/Chatham</option>
                        <option value="+10:00">Pacific/Chuuk</option>
                        <option value="-05:00">Pacific/Easter</option>
                        <option value="+11:00">Pacific/Efate</option>
                        <option value="+13:00">Pacific/Enderbury</option>
                        <option value="+13:00">Pacific/Fakaofo</option>
                        <option value="+12:00">Pacific/Fiji</option>
                        <option value="+12:00">Pacific/Funafuti</option>
                        <option value="-06:00">Pacific/Galapagos</option>
                        <option value="-09:00">Pacific/Gambier</option>
                        <option value="+11:00">Pacific/Guadalcanal</option>
                        <option value="+10:00">Pacific/Guam</option>
                        <option value="-10:00">Pacific/Honolulu</option>
                        <option value="+14:00">Pacific/Kiritimati</option>
                        <option value="+11:00">Pacific/Kosrae</option>
                        <option value="+12:00">Pacific/Kwajalein</option>
                        <option value="+12:00">Pacific/Majuro</option>
                        <option value="-09:30">Pacific/Marquesas</option>
                        <option value="-11:00">Pacific/Midway</option>
                        <option value="+12:00">Pacific/Nauru</option>
                        <option value="-11:00">Pacific/Niue</option>
                        <option value="+11:00">Pacific/Norfolk</option>
                        <option value="+11:00">Pacific/Noumea</option>
                        <option value="-11:00">Pacific/Pago_Pago</option>
                        <option value="+09:00">Pacific/Palau</option>
                        <option value="-08:00">Pacific/Pitcairn</option>
                        <option value="+11:00">Pacific/Pohnpei</option>
                        <option value="+10:00">Pacific/Port_Moresby</option>
                        <option value="-10:00">Pacific/Rarotonga</option>
                        <option value="+10:00">Pacific/Saipan</option>
                        <option value="-10:00">Pacific/Tahiti</option>
                        <option value="+12:00">Pacific/Tarawa</option>
                        <option value="+13:00">Pacific/Tongatapu</option>
                        <option value="+12:00">Pacific/Wake</option>
                        <option value="+12:00">Pacific/Wallis</option>
                        <option value=" 00:00">UTC</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Country</label>
                <select name="country" class="form-control" required="">
                    <option value="">Select Country</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
               </select>
            </div>

            <div class="half_width float_left" style="display: none;">
                <label class="field_title">State</label>
                <select name="state" class="form-control">
                    <option value="">Select State</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['state']; ?>"><?php echo $val['Tax']['state']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">City</label>
                <select name="city" class="form-control" required="">
                    <option value="">Select City</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['city']; ?>"><?php echo $val['Tax']['city']; ?></option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">Tax</label>
                <select name="tax_id" class="form-control" required="">
                    <option value="">Select Tax</option>

                    <?php  foreach( $tax['msg'] as $str => $val ): ?>

                        <option value="<?php echo $val['Tax']['id']; ?>"><?php echo $val['Tax']['tax']; ?> %</option>

                    <?php endforeach; ?>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Location Long</label>
                <input name="long" type="text" required>
                
                
            </div>

            <div class="half_width float_left">
                <label class="field_title">Location Lat</label>
                <input name="lat" type="text" required>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Zip Code</label>
                <input name="zip" type="text" required>
            </div>
            
            <input type="hidden" name="currencyid" value="<?php echo $currency['msg'][0]['Currency']['id'] ?>" >
            <div class="half_width float_left">
                <label class="field_title">Minimum Order Price</label>
                <select name="min_order_price" class="form-control" required="">
                    <option value="">Select Amount</option>

                    <?php

                        for($i = 1; $i<=999; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i.' '; echo $currency['msg'][0]['Currency']['symbol']; ?></option>
                            <?php
                        }

                    ?>

               </select>

            </div>



            <div class="half_width float_right">
                <label class="field_title">Free Delivery Range</label>
                <select name="delivery_free_range" class="form-control" required="">
                    <option value="">Select KM Range</option>
                    <option value="0">Non Free Delivery</option>
                    <?php

                        for($i = 1; $i<=49; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> KM</option>
                            <?php
                        }

                    ?>

               </select>
            </div>

            <div class="half_width float_left">
                <label class="field_title">AVG Food Prepation Time</label>
                <select name="preparation_time" class="form-control" required="">
                    <option value="">Select Minutes</option>
                    <option value="5">5 min</option>
                    <option value="10">10 min</option>
                    <option value="15">15 min</option>
                    <option value="20">20 min</option>
                    <option value="25">25 min</option>
                    <option value="30">30 min</option>
                    <option value="35">35 min</option>
                    <option value="40">40 min</option>
                    <option value="45">45 min</option>
                    <option value="50">50 min</option>
                    <option value="55">55 min</option>
                    <option value="60">60 min</option>
               </select>
            </div>

            <div class="half_width float_right">
                <label class="field_title">Tax implementation</label>
                <select name="tax_free" class="form-control" required="">
                    <option value="1">No Tax will implement</option>
                    <option value="0">Tax % will implement</option>
                </select>
            </div>

            <div class="clear_both"></div>
         
            
            <h3 style="font-weight: 300;" align="center">Store User Information</h3>

            <div class="full_width">
                
                <label class="field_title">First Name</label>
                <input name="first_name" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Last Name</label>
                <input name="last_name" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Email</label>
                <input name="email" type="text" required>
            </div>

            <div class="full_width">
                <label class="field_title">Password</label>
                <input name="password" type="text" required>
            </div>

            <div class="full_width">
                <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                   Submit
                </button>
            </div>

        </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "orderDetails")
{
    $id=$_GET['id'];
    $url=$baseurl . 'showOrderDetail';

    $data = array(
                "order_id" => $id
            );

   $json_return=@curl_request($data,$url);
   $currency=$json_return['msg'][0]['Restaurant']['Currency']['symbol'];

    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Order Details (#<?php echo $json_return['msg'][0]['Order']['order_number']; ?>)</h2>

        <div style="height:400px; overflow:scroll;">

            <h3 style="font-weight: 300;" align="left">Buyer Details</h3>

            <div style="line-height: 25px;margin-top: 10px;">
                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-user"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['UserInfo']['first_name']." ". $json_return['msg'][0]['UserInfo']['last_name'];
                    ?>
                </div>
                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-phone"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['UserInfo']['phone'];
                    ?>
                </div>

                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-street-view"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['Address']['city']." ".$json_return['msg'][0]['Address']['street'];
                    ?>
                </div>


            </div>

            <br>
            <h3 style="font-weight: 300;" align="left">Restaurant Details</h3>

            <div style="line-height: 25px;margin-top: 10px;">
                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-utensils"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['Restaurant']['name'];
                    ?>
                </div>

                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-phone"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['Restaurant']['phone'];
                    ?>
                </div>

                <div class="full_width" style="font-size:13px;">
                    <span class="fa fa-street-view"></span>&nbsp;
                    <?php
                        echo $json_return['msg'][0]['Restaurant']['RestaurantLocation']['city']." ".$json_return['msg'][0]['Restaurant']['RestaurantLocation']['state']." ".$json_return['msg'][0]['Restaurant']['RestaurantLocation']['country'];
                    ?>
                </div>
            </div>

            <br>
            <h3 style="font-weight: 300;" align="left">Restaurant Instructions</h3>

            <div style="line-height: 25px;margin-top: 10px;">
                <div class="full_width" style="font-size:13px;">
                    <?php
                        echo $json_return['msg'][0]['Order']['accepted_reason'];
                    ?>
                </div>
            </div>


            <br>
            <h3 style="font-weight: 300;" align="left">Customer Instructions</h3>

            <div style="line-height: 25px;margin-top: 10px;">
                <div class="full_width" style="font-size:13px;">
                    <?php
                        echo $json_return['msg'][0]['Order']['instructions'];
                    ?>
                </div>
            </div>

            
            
            <?php
                if($json_return['msg'][0]['Order']['available_status'] ==1)
                {
            ?>
            <div style="clear: both;">&nbsp;</div>
            <br/>
            <h3 style="font-weight: 300;" align="left">Booking Delivery Details</h3>
                <div style="line-height: 25px;margin-top: 10px;">
                    <div class="full_width" style="font-size:13px;">
                    <p>
                <i class="fa fa-calendar"></i>
                        <?php echo strtoupper($json_return['msg'][0]['Order']['booking_day']); ?>
                </p>
                <p><i class="fa fa-clock-o"></i>
                        <?php echo $json_return['msg'][0]['Order']['booking_day_time']; ?>
                </p>
                    </div>
                </div>

            <?php
                }
            ?>


            <br>
            <h3 style="font-weight: 300;" align="left">Menu Item</h3>

            <div style="line-height: 25px;margin-top: 10px;">
                <div class="full_width" style="font-size:13px;">

                    <table style="font-size: 12px; width: 100%;">
                        <tr style="background: #e3e3e3;">
                            <td>Item Name</td>
                            <td>Qty.</td>
                            <td>Price</td>
                        </tr>
                        <?php  foreach( $json_return['msg'][0]['OrderMenuItem'] as $str => $val ): ?>

                        <tr style="background: #c8c1c11a;border-bottom: solid 1px #f0f0f0;">
                            <td><?php echo $val['quantity']."X ".$val['name']; ?></td>
                            <td><?php echo $val['quantity']; ?></td>
                            <td><?php echo $val['price'].' '; echo $currency; ?></td>
                        </tr>

                        <?php endforeach; ?>
                    </table>



                    <br>
                    <div style="padding:0 20px;">
                        <div class="full_width" style="font-size:13px; width:200px; float:left;font-weight: bold;">
                            Tax
                            <?php

								if($json_return['msg'][0]['Restaurant']['tax_free']=="0")
								{
								    ?>
										(<?php echo $json_return['msg'][0]['Restaurant']['Tax']['tax']; ?>%)
									<?php
								}
							?>
                        </div>

                        <div class="full_width" style="font-size:13px; width:200px; float:right;" align="right">
                            <?php echo $json_return['msg'][0]['Restaurant']['Tax']['tax'].' '; echo $currency; ?>

                        </div>
                    </div>

                    <div style="padding:0 20px;">
                        <div class="full_width" style="font-size:13px; width:200px; float:left;font-weight: bold;">
                            Payment Method
                        </div>

                        <div class="full_width" style="font-size:13px; width:200px; float:right;" align="right">
                            <?php

                                if( $json_return['msg'][0]['Order']['payment_method_id'] != "0" )
                                {

                                    echo "Credit Card";

                                }
                                else
                                if( $json_return['msg'][0]['Order']['payment_method_id'] == "0" )
                                {

                                    echo "Cash on Delivery (COD)";

                                }
                            ?>
                        </div>
                    </div>

                    <div style="padding:0 20px;">
                        <div class="full_width" style="font-size:13px; width:200px; float:left;font-weight: bold;">
                            Delivery Fee
                        </div>

                        <div class="full_width" style="font-size:13px; width:200px; float:right;" align="right">
                            <?php echo $json_return['msg'][0]['Order']['delivery_fee'].' '; echo $currency;  ?>
                        </div>
                    </div>

                    <div style="padding:0 20px;">
                        <div class="full_width" style="font-size:13px; width:200px; float:left;font-weight: bold;">
                            SubTotal
                        </div>

                        <div class="full_width" style="font-size:13px; width:200px; float:right;" align="right">
                            <?php echo $json_return['msg'][0]['Order']['sub_total'].' '; echo $currency; ?>
                        </div>
                    </div>

                </div>
            </div>
            
            
            

        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "addAdminUser")
{
    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Admin User</h2>

        <div style="height:400px; overflow:scroll;">
            <form action="?p=adminUsers&action=addAdminUser" method="post" >
                <input name="user_id" type="hidden" value="<?php echo $id; ?>" required>
                <div class="full_width">
                    <label class="field_title">First Name</label>
                    <input name="first_name" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Last Name</label>
                    <input name="last_name" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Phone #</label>
                    <input name="phone" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Email</label>
                    <input name="email" type="text" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Password</label>
                    <input name="password" type="text" required>
                </div>
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php

}
else
if (@$_GET['action'] == "filter")
{
    
    ?>
    <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Filter Earning</h2>

        <div style="height:200px; overflow-y:scroll;">
            <form action="#" method="get" >
                
                <input type="hidden" placeholder="Start Date" id="p" name="p"  value="earning" class="input-control" required/>
                <input type="hidden" placeholder="Start Date" id="action" name="action"  value="filterDate" class="input-control" required/>
                <div class="full_width">
                    <label class="field_title">Start Date</label>
                    <input type="text" placeholder="Start Date" id="start_date" name="start_date"  value="" class="input-control" required/>
                </div>

                <div class="full_width">
                    <label class="field_title">End Date</label>
                    <input type="text" placeholder="End Date" id="end_date" name="end_date"  value="" class="input-control" required/>
                </div>
                
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Submit
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    <?php

}
else
if(@$_GET['action']=="orderNotification") 
{
    
    $url=$baseurl . 'showAllOrdersAutoLoad';
    $data = array(
                "status" => "1"
            );
    
    $json_return=@curl_request($data,$url);
    
    if($json_return['code']=="200")
    {
        $lastOrder=@$json_return['msg'][0]['Order']['id'];
    
        $oldOrder=@$_SESSION[PRE_FIX.'currentOrder'];
        
        if($oldOrder != $lastOrder)
        {
            $_SESSION[PRE_FIX.'currentOrder']=$lastOrder;
            ?>
                <a href="dashboard.php?p=manageOrders" style="font-size:12px; text-decoration:none;">
                    <span style="background: #BE2C2C;border-radius: 20px;padding: 5px 10px;color: white;">
                        <span class="fa fa-refresh"></span>
                        Refresh For New Orders
                    </span>
                </a>
                
                    <iframe src="mediaplayer.html"
                        allow="autoplay 'src'" style="display: none;">
                    </iframe>
            <?php
        } 
    }


}
else
if(@$_GET['action']=="tabSettingAutoAssign") 
{
    $type=$_GET['type'];
    $value=$_GET['value'];
    
    $url=$baseurl . 'addSettings';
    $data = array(
                "type" => $type,
                "value"=> $value
            );
    
    $json_return=@curl_request($data,$url);
    
    // print_r($json_return);
    if($json_return['code']=="200")
    {
        ?>
            <div align="center" style="background: green;color: white;padding: 10px 0;border-radius: 3px;">successfully saved</div>
        <?php
    }


}else
if(@$_GET['action']=="addCouponCode") 
{
	$coupon_code = $_GET['coupon_code'];
	$user_id = $_GET['user_id'];
	$discount = $_GET['discount'];
	$expire_date = $_GET['expire_date'];
	$limit =  $_GET['limit'];
	$type =  $_GET['type'];
	
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
    if($json_return['code']=="200")
    {
        ?>
            <div align="center" style="background: green;color: white;padding: 10px 0;border-radius: 3px;">successfully added</div>
        <?php
    }


}else
if(@$_GET['action']=="showAddCoupon") 
{   
    
    $couponcode = substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6/strlen($x)) )),1,6);

    ?>
        <div class="main-container dataTables_wrapper" id="table_view_wrapper" >
        <h2 style="font-weight: 300;" align="center">Add Coupon</h2>

        <div style="height:420px;">
            <form class="addcategory" action="?p=editResturent&id=<?php echo $_GET['id']; ?>&action=addCoupon" method="post" >
                
                <div class="full_width">
                    <label class="field_title">Coupon Code</label>
                    <input name="coupon_code" type="text" maxlength="6" required value="<?php echo $couponcode; ?>">
                </div>
                
                <div class="full_width">
                    <label class="field_title">Discount %</label>
                    <input name="discount" type="number" min="1" max="100" required>
                </div>
                
                <div class="full_width">
                    <label class="field_title">Platform</label>
                    <select name="type" class="full_width" required>
                        <option value="">Select Platform</option>
                          <option value="web">Web</option> 
                          <option value="ios">iOS</option> 
                          <option value="android">Android</option>                     
                    </select>
                </div>

                <div class="full_width">
                    <label class="field_title">Expiry Date</label>
                    <input name="expire_date" type="date" required>
                </div>

                <div class="full_width">
                    <label class="field_title">Coupon Used Limit</label>
                    <input name="limit" type="number" min="1" required>
                </div>

                <div class="full_width" style="display: none;">
                    <label class="field_title">Restaurant ID</label>
                    <input id="restaurant_id" name="user_id" type="number" min="0" value="<?php echo $_GET['id']; ?>" required>
                </div>
                
                <div class="full_width">
                    <button class="com-button com-submit-button com-button--large " type="submit" style="width: 100%;" align="center">
                       Add Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php


}

?>