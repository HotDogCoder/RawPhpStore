<?php

App::uses('Lib', 'Utility');
App::uses('Postmark', 'Utility');
App::uses('Message', 'Utility');

App::uses('CustomEmail', 'Utility');
App::uses('Security', 'Utility');
App::uses('PushNotification', 'Utility');
App::uses('Firebase', 'Lib');



class PublicSiteController extends AppController{

    public $components = array('Email');

    public $autoRender = false;
    public $layout = false;
    
    public $items = array();

    /**
     * HTML contents
     */
    public $html  = array();


    public function index(){

        $output['code'] = "200";
        $output['msg'] = "Congratulations!. You have configured your website api correctly";

        echo json_encode($output);
        die();

    }


    public function registerUser()
    {

        $this->loadModel('User');
        $this->loadModel('UserInfo');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            if(!isset($data['phone_signup']))
            {
                $email        = strtolower($data['email']);
                $password     = @$data['password'];
                $type = 0;
            }
            else
            {
                $type = 1;
            }
            $first_name   = @$data['first_name'];
            $last_name    = @$data['last_name'];

            $device_token = @$data['device_token'];
            $role         = @$data['role'];

            $active = 1;

            if($role == "rider" || $role == "hotel"){

                $active = 0;
            }


            if(isset($data['phone'])){

                $phone        = $data['phone'];
                $user_info['phone']        = $phone;
            }


            $flag = false;

            if(!isset($data['phone_signup']))
            {
                if ($email != null && $password != null) {
                    $flag = true;
                }
            }
            else
            {
                $flag = true;
            }



            if ($flag) {


                if(!isset($data['phone_signup']))
                {

                    $user['email']    = $email;
                    $user['password'] = $password;
                }

                $user['type'] = $type;
                $user['active']  = $active;
                $user['role']    = $role;
                $user['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);





                if(!isset($data['phone_signup']))
                {
                    $count = $this->User->isEmailAlreadyExist($email);
                }
                else
                {
                    $count = $this->UserInfo->isPhoneAlreadyExist($phone);
                }


                if ($count && $count > 0) {

                    if(!isset($data['phone_signup']))
                    {
                        echo Message::DATAALREADYEXIST();
                    }
                    else
                    {
                        echo Message::CELLALREADYEXIST();
                    }
                    die();

                } else {

                    $lib = new Lib;
                    $key = Security::hash(CakeText::uuid(), 'sha512', true);



                    if (!$this->User->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $user_id              = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;

                    $user_info['device_token'] = $device_token;
                    $user_info['full_name']   = $first_name." ".$last_name;
                    //$user_info['last_name']    = $last_name;




                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }

                    

                    $output      = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);

                    if(!isset($data['phone_signup']))
                    {
                        $key     = Security::hash(CakeText::uuid(), 'sha512', true);
                        CustomEmail::welcomeEmail($email,$key);
                    }

                    $output['code'] = 200;
                    $output['msg']  = $userDetails;
                    echo json_encode($output);




                }
            } else {
                echo Message::ERROR();
            }
        }
    }



    public function login() //changes done by irfan
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            // $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email    = strtolower($data['email']);
            $password = @$data['password'];
            $role = @$data['role'];

                    
            //   $device_token = @$data['device_token'];
            // $userData['msg'] = ;

            if ($email != null && $password != null) {
                $userData = $this->User->loginAllUsersExceptAdmin($email, $password,$role);
                
                if (($userData) && $userData !== "203") {
                    $user_id = $userData[0]['User']['id'];


                    $output      = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
                    $output['code'] = 200;
                    $output['msg']  = $userDetails;
                    echo json_encode($output);



                } else if ($userData == "203") {

                    $output['code'] = 203;
                    $output['msg']  = "Not allowed";
                    echo json_encode($output);
                    die();

                } else {
                    echo Message::INVALIDDETAILS();
                    die();

                }





            } else {
                echo Message::ERROR();
                die();
            }
        }
    }

    public function verifyPhoneNo()
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');
        $this->loadModel('PhoneNoVerification');
        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);

            $phone_no = $data['phone_no'];
            $verify   = $data['verify'];
            if(isset($data['login']))
                $login = $data['login'];
            else
                $login = 0;


            if($login == 1)
            {
                $role = 'user';
                $UserInfo = $this->UserInfo->getUserIdByPhone($phone_no,$role);
                if(!empty($UserInfo))
                {
                    $user_id = $UserInfo['UserInfo']['user_id'];
                    $phone_verify['user_id'] = $user_id;    
                }
                else
                {
                    $output['code'] = 204;//error
                    $output['msg']  = "Phone number does not exist. Please Sign up first.";
                    echo json_encode($output);
                    die();
                }
            }

            $code     = Lib::randomNumber(4);
            // $code = 1234;

            $created                  = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
            $phone_verify['phone_no'] = $phone_no;
            $phone_verify['code']     = $code;
            $phone_verify['created']  = $created;
            /* $app = "demo";
             if($app == "demo"){

                 $output['code'] = 200;
                 $output['msg']  = "demo";
                 echo json_encode($output);
                 die();

             }*/
            if ($verify == 0) {


                $response = Lib::sendSmsVerificationCurl($phone_no, VERIFICATION_PHONENO_MESSAGE . ' ' . $code);





                if (array_key_exists('code', $response)){
                    if($response['code'] == 21608 || $response['code'] == 201 || $response['code'] ==21606 || $response['code'] ==20404){

                        $output['code'] = 200;//error
                        $output['msg']  = $response['message'];
                        echo json_encode($output);
                        die();

                    }
                }else{



                    if (array_key_exists('sid', $response)){



                        $this->PhoneNoVerification->save($phone_verify);
                        $output['code'] = 200;
                        $output['msg']  = "code has been generated and sent to:$phone_no ";
                        echo json_encode($output);
                        die();


                    }

                }



                if ($response) {
                    $this->PhoneNoVerification->save($phone_verify);
                    $output['code'] = 200;
                    $output['msg']  = "code has been generated and sent to user's phone number";
                    echo json_encode($output);
                    die();
                } else {

                    $output['code'] = 200;//error
                    $output['msg']  = "Unable to send verification code. Please check phone number.";
                    echo json_encode($output);
                    die();
                }




            } else {
                $code_user = $data['code'];

                if ($this->PhoneNoVerification->verifyCode($phone_no, $code_user) > 0) {

                     if ($verify == 1) 
                    {
                        
                        $userdata= $this->PhoneNoVerification->userupdate($phone_no, $code_user);

                        $userid=$userdata['PhoneNoVerification']['user_id'];

                        
                        $this->User->read(null,$userid);
                        $this->User->set('active', 1);
                        if($this->User->save())
                        {
                        $output['code'] = 200;
                        $output['msg']  = " User Signin successfull!";


                        $output['data'] = $this->UserInfo->getUserDetailsFromID($userid);
                        echo json_encode($output);
                        die();
                        }
                    }
                    else
                    {
                        $output['code'] = 200;
                        $output['msg']  = "successfully code matched";
                        /*$this->PhoneNoVerification->deleteAll(array(
                            'phone_no' => $phone_no
                        ), false);*/

                        echo json_encode($output);
                        die();
                    }
                } else {

                    $output['code'] = 200;
                    $output['msg']  = "invalid code";

                    echo json_encode($output);
                    die();

                }

            }
        }


    }
    public function addPaymentMethod()
    {

        $this->loadModel('StripeCustomer');
        $this->loadModel('PaymentMethod');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id = @$data['user_id'];
            $default = @$data['default'];




            //$email = @$data['email'];
            //$first_name = @$data['first_name'];
            //$last_name = @$data['last_name'];
            $name      = @$data['name'];
            $card      = @$data['card'];
            $cvc       = @$data['cvc'];
            $exp_month = @$data['exp_month'];
            $exp_year  = @$data['exp_year'];
            // $address_line_1 = @$data['street'];
            //$address_line_2 = @$data['city'];
            // $address_zip = @$data['zip'];
            //$address_state = @$data['state'];
            //$address_country = @$data['country'];

            if ($card != null && $cvc != null) {

                $a      = array(

                    // 'email' => $email,
                    'card' => array(
                        //'name' => $first_name . " " . $last_name,
                        'number' => $card,
                        'cvc' => $cvc,
                        'exp_month' => $exp_month,
                        'exp_year' => $exp_year,
                        'name' => $name

                        // 'address_line_1' => $address_line_1,
                        //'address_line_2' => $address_line_2,
                        //'address_zip' => $address_zip,
                        //'address_state' => $address_state,
                        //'address_country' => $address_country
                    )
                );
                $stripe = $this->StripeCustomer->save($a);


                if ($stripe) {





                    $payment['stripe']  = $stripe['StripeCustomer']['id'];
                    $payment['user_id'] = $user_id;
                    $payment['default'] = $default;
                    $result             = $this->PaymentMethod->save($payment);
                    $count              = $this->PaymentMethod->isUserStripeCustIDExist($user_id);
                    if ($count > 0) {

                        $cards = $this->PaymentMethod->getUserCards($user_id);


                        foreach ($cards as $card) {

                            $response[] = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);

                        }



                        $i = 0;
                        foreach ($response as $re) {

                            $stripeCustomer                        = $re[0]['StripeCustomer']['sources']['data'][0];
                            $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                            $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                            $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
                            $stripData[$i]['CardDetails']['name']  = $stripeCustomer['name'];

                            $i++;
                        }


                        $output['code'] = 200;
                        $output['msg']  = $stripData;
                        echo json_encode($output);
                        die();
                    } else {
                        Message::EmptyDATA();
                        die();
                    }




                } else {
                    $error['code'] = 400;
                    $error['msg']  = $this->StripeCustomer->getStripeError();
                    echo json_encode($error);
                }
            } else {
                echo Message::ERROR();



            }

        }

    }


    public function getPaymentDetails()
    {
        $this->loadModel('StripeCustomer');
        $this->loadModel('PaymentMethod');


        if ($this->request->isPost()) {
            //$json = file_get_contents('php://input');
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);
            $user_id = @$data['user_id'];
            if ($user_id != null) {

                $count = $this->PaymentMethod->isUserStripeCustIDExist($user_id);

                if ($count > 0) {

                    $cards = $this->PaymentMethod->getUserCards($user_id);

                    $j = 0;
                    foreach ($cards as $card) {

                        $response[$j]['Stripe']              = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);
                        $response[$j]['PaymentMethod']['id'] = $card['PaymentMethod']['id'];
                        $j++;
                    }



                    $i = 0;
                    foreach ($response as $re) {

                        $stripeCustomer                       = $re['Stripe'][0]['StripeCustomer']['sources']['data'][0];
                        /* $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
                        $stripData[$i]['CardDetails']['name'] = $stripeCustomer['name'];*/
                        $stripData[$i]['brand']               = $stripeCustomer['brand'];
                        $stripData[$i]['brand']               = $stripeCustomer['brand'];
                        $stripData[$i]['last4']               = $stripeCustomer['last4'];
                        $stripData[$i]['name']                = $stripeCustomer['name'];
                        $stripData[$i]['exp_month']           = $stripeCustomer['exp_month'];
                        $stripData[$i]['exp_year']            = $stripeCustomer['exp_year'];
                        $stripData[$i]['PaymentMethod']['id'] = $re['PaymentMethod']['id'];

                        $i++;
                    }


                    $output['code'] = 200;
                    $output['msg']  = $stripData;
                    echo json_encode($output);
                    die();
                } else {
                    Message::EmptyDATA();
                    die();
                }

            } else {
                echo Message::ERROR();
            }
        }


    }


    public function addDeliveryAddress()
    {


        $this->loadModel("Address");
        $this->loadModel("UserInfo");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $user_id     = @$data['user_id'];
            $lat     =     @$data['lat'];
            $long =        @$data['long'];
            $street      = @$data['street'];
            $apartment   = @$data['apartment'];
            $city        = @$data['city'];
            $state       = @$data['state'];
            $zip         = @$data['zip'];
            $country     = @$data['country'];
            $instruction = @$data['instructions'];






            $address['user_id']      = $user_id;
            $address['street']       = $street;
            $address['apartment']    = $apartment;
            $address['city']         = $city;
            $address['state']        = $state;
            $address['zip']          = $zip;
            $address['country']      = $country;
            $address['instructions'] = $instruction;
            $address['lat']         = $lat;
            $address['long']         = $long;

            //update
            if (isset($data['id'])) {

                $id                = $data['id'];
                $this->Address->id = $id;
                $this->Address->save($address);

                $userDetails    = $this->UserInfo->getUserDetailsFromID($user_id);
                $output['code'] = 200;
                $output['msg']  = $userDetails;
                echo json_encode($output);


                die();
            //} else if ($this->Address->isDuplicateRecord($user_id, $street, $city, $apartment, $state, $country) == 0) {
            } else if (true) {
                if ($this->Address->save($address)) {


                    //$gigpost_category['cat_id'] = $cat_id;


                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
                    $output['code'] = 200;

                    $output['msg'] = $userDetails;
                    echo json_encode($output);

                    die();

                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

        }


    }

    public function getDeliveryAddresses()
    {

        $this->loadModel('Address');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id   = @$data['user_id'];
            $addresses = $this->Address->getUserDeliveryAddresses($user_id);


            $output['code'] = 200;
            $output['msg']  = $addresses;
            echo json_encode($output);
            die();


        }

    }

    public function showRestaurants(){

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantCategory");
        $this->loadModel("Category");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $lat = @$data['lat'];
            $long = @$data['long'];
            $user_id = null;
            if(isset($data['user_id'])){

                $user_id = $data['user_id'];
            }
            /*$results = Lib::getCountryCityProvinceFromLatLong($lat,$long);

            if(strlen($results['city']) > 2) {

                $restaurants[0] = $this->Restaurant->getCurrentCityRestaurantsBasedOnPromoted($lat, $long, $user_id,$results['city']);
                $restaurants[1] = $this->Restaurant->getCurrentCityRestaurantsBasedOnDistance($lat, $long, $user_id,$results['city']);


                //  array_push($restaurants[0], $restaurants[1]);

                array_splice( $restaurants[0], count($restaurants[0]), 0,  $restaurants[1] );
            }else{

                $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);

            }*/

            $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id,RADIUS);

            $nearby_restaurants = [];
            foreach($restaurants as $restaurant){
                $rest_categories = "";
                foreach ($restaurant['RestaurantCategory'] as $key => $value) {
                    $category = $this->Category->getCategoryDetail($value['category_id']);
                    if($rest_categories != "")
                        $rest_categories .= ",".$category[0]['Category']['category'];
                    else
                        $rest_categories = $category[0]['Category']['category'];
                }
                $restaurant['Restaurant']['categories'] = $rest_categories;
                array_push($nearby_restaurants, $restaurant);
            }
            // foreach($restaurants as $restaurant){
            //     $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant['Restaurant']['name']);
                
            //     $distance = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $lat, $long);
            
            //     $restaurant['Restaurant']['distance_from_user'] = $distance;
                
            // }
            
            
            $output['code'] = 200;

            // $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($restaurants);
            $output['msg'] = $nearby_restaurants;
            
            // CakeLog::write('debug', 'restaurants: '.print_r(json_encode($restaurants), TRUE));
            
            // $email = "mouthy.chef@gmail.com";
            // $email_data['to'] = $email;
            // $email_data['name'] = "nassim";
            // $email_data['subject'] = "new restaurant request";
            // $email_data['message'] = "You have received a new restaurant request from Please login through admin portal to view the request.";
            // CustomEmail::sendMail($email_data);
            
            echo json_encode($output);


            die();



        }
    }


    public function showFeaturedRestaurants(){

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $lat = @$data['lat'];
            $long = @$data['long'];
            $user_id = null;
            if(isset($data['user_id'])){

                $user_id = $data['user_id'];
            }
            $restaurants = $this->Restaurant->getPromotedRestaurantsWeb($lat, $long, $user_id);

            // $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);
            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();



        }
    }


    public function searchRestaurants(){

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $keyword = $data['keyword'];

            $restaurants = $this->Restaurant->searchRestaurant($keyword);

            if(count($restaurants) > 0) {

                $output['code'] = 200;

                $output['msg'] = $restaurants;
                echo json_encode($output);


                die();
            }else{

                Message::EMPTYDATA();

            }


        }
    }

    public function showRestaurantDetail()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
            $restaurant_id = $id[0]['Restaurant']['id'];

                $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);

                /* $i = 0;
                 foreach ($restaurant_detail as $rest) {
                     $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                     if (count($ratings) > 0) {
                         $restaurants[$i]['TotalRatings']["avg"]          = $ratings[0]['average'];
                         $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                     }
                     $i++;

                 }*/
                $output['code'] = 200;

                $output['msg'] = $restaurant_detail;
                echo json_encode($output);
                die();
            } else {

                Message::ACCESSRESTRICTED();
            }
        }
    }
    public function showRestaurantsMenu()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            $menus = $this->Restaurant->getRestaurantMenusForMobile($restaurant_id);


            $output['code'] = 200;

            // $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($menus);
            $output['msg'] =  $menus;
            echo json_encode($output);


            die();

        }
    }


    public function showMainMenus()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $id = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];


                // $main_menus = $this->RestaurantMenu->getMainMenu($restaurant_id);
                $menus = $this->Restaurant->getRestaurantMenusForWeb($restaurant_id);

                if (isset($data['time'])) {

                    $result = $this->checkRestuarantIsOpenOrNot(ucfirst($data['day']), $data['time'], $restaurant_id);

                    $menus[0]['Restaurant']['availability'] = $result;
                }


                $output['code'] = 200;

                // $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($menus);
                $output['msg'] =  $menus;
                echo json_encode($output);


                die();
            } else {


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showMenuItems()
    {

        $this->loadModel("RestaurantMenuItem");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_menu_id = $data['restaurant_menu_id'];

            $menu_items = $this->RestaurantMenuItem->getMenuItems($restaurant_menu_id);


            $output['code'] = 200;

            $output['msg'] = $menu_items;
            echo json_encode($output);


            die();
        }
    }

    public function showMenuExtraItems()
    {

        $this->loadModel("RestaurantMenuExtraItem");
        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_menu_item_id = $data['restaurant_menu_item_id'];
            $restaurant_id           = $data['restaurant_id'];



            // $menu_extra_items = $this->RestaurantMenuExtraItem->getMenuExtraItems($restaurant_menu_item_id);
            $menu_extra_items = $this->RestaurantMenuExtraSection->getSectionsWithItems($restaurant_id, $restaurant_menu_item_id);

            if (count($menu_extra_items) > 0) {
                for ($i = 0; $i < count($menu_extra_items); $i++) {
                    // //this array was repeating so we remove this at one place
                    //$new_menu_extra_items[$i]['RestaurantMenuExtraSection'] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
                    $menu_extra_items[$i]['RestaurantMenuExtraSection']['RestaurantMenuExtraItem'] = $menu_extra_items[$i]['RestaurantMenuExtraItem'];
                    unset($menu_extra_items[$i]['RestaurantMenuExtraItem']);
                }

            }

            $output['code'] = 200;

            $output['msg'] = $menu_extra_items;
            echo json_encode($output);


            die();
        }
    }



    public function showMenuExtraItemsWithSections()
    {

        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $menu_extra_items = $this->RestaurantMenuExtraSection->getSectionsWithItems($restaurant_id);
            /*if(count($menu_extra_items) > 0) {
            for($i=0; $i < count($menu_extra_items);$i++){

            $new_menu_extra_items[$i]['RestaurantMenuExtraSection'] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
            $new_menu_extra_items[$i]['RestaurantMenuExtraSection'][''] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
            }

            }*/
            $output['code']   = 200;

            $output['msg'] = $menu_extra_items;
            echo json_encode($output);


            die();
        }
    }



    public function deleteMainMenu()
    {

        $this->loadModel("RestaurantMenu");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $restaurant_id = $data['restaurant_id'];
            $menu_id = $data['menu_id'];
            $this->RestaurantMenu->deleteMainMenu($menu_id,$restaurant_id);

            $output['code'] = 200;
            $output['msg'] = "deleted";
            echo json_encode($output);

            die();

        } else {


            Message::ACCESSRESTRICTED();
            die();
        }






    }

    public function deleteMenuItem()
    {


        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_item_id'];
            $deleted_sections = $this->RestaurantMenuItem->deleteMenuItemAgainstID($id);
            if ($deleted_sections) {


                Message::DELETEDSUCCESSFULLY();

                die();

            }
        }


    }





    public function deleteMenuExtraSection()
    {



        $this->loadModel("RestaurantMenuExtraSection");

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $menu_extra_section_id = $data['menu_extra_section_id'];



            $extra_items_deleted =    $this->RestaurantMenuExtraSection->deleteSectionAgainstID($menu_extra_section_id);
            if ($extra_items_deleted) {



                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }

    public function deleteMenuExtraItem()
    {


        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_extra_item_id'];
            $active = $data['active'];


            $extra_items_deleted = $this->RestaurantMenuExtraItem->deleteMenuExtraItemAgainstID($id);

            if ($extra_items_deleted) {


                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }

    public function updateRestaurantStatus()
    {
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $user_id = $data['user_id'];

            $id            = $this->Restaurant->getRestaurantID($user_id);

            if(count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];
            }
            $status = $data['status'];
            $this->Restaurant->id = $restaurant_id;
            $restaurant['is_online'] = $status;
            
            if ($this->Restaurant->save($restaurant)) {

                 $output['code'] = 200;

                 $output['msg'] = 'status updated';

                 echo json_encode($output);
                 die();
            }
            else 
            {
                Message::EMPTYDATA();
            }
        }
    }

    public function showRestaurantOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $user_id = $data['user_id'];
            

            $id            = $this->Restaurant->getRestaurantID($user_id);
            if(count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $orders = array();
                // $orders = $this->Order->getRestaurantOrders($restaurant_id);

                if (isset($data['status'])) {

                    $status = $data['status'];
                    $orders = $this->Order->getActiveAndCompletedOrdersOfRestaurant($restaurant_id, $status);


                } else if (isset($data['starting_date'])) {

                    $starting_date = $data['starting_date'];
                    $ending_date = $data['ending_date'];
                    $orders = $this->Order->getOrdersBetweenTwoDates($restaurant_id, $starting_date, $ending_date);

                } else if (isset($data['hotel_accepted'])) {

                    $hotel_accepted = $data['hotel_accepted'];
                    $orders = $this->Order->getCancelledOrdersOfRestaurant($restaurant_id, $hotel_accepted);


                }


                if (count($orders) > 0) {
                    $output['code'] = 200;

                    $output['msg'] = $orders;

                    echo json_encode($output);
                    die();
                }else {


                    Message::EMPTYDATA();
                }

            }else {


                Message::EMPTYDATA();
            }

            die();
        }
    }




    public function showOrdersBetweenDates()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $min_date      = $data['min_date'];
            $max_date      = $data['max_date'];
            $restaurant_id = $data['restaurant_id'];


            $orders = $this->Order->getOrdersBetweenTwoDates($restaurant_id, $min_date, $max_date);
            //  debug($this->Order->lastQuery());

            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showUserOrders()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];




            $orders = $this->Order->getUserOrders($user_id);
            //  debug($this->Order->lastQuery());

            $output['code'] = 200;

            // $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($orders);
            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showOrderDetail()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];

            $user_id = $data['user_id'];

            $id = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {

                $restaurant_id = $id[0]['Restaurant']['id'];


                $orders = $this->Order->getOrderDetailBasedOnIDAndRestaurant($order_id, $restaurant_id);

            } else {

                $orders = $this->Order->getOrderDetailBasedOnUserID($order_id, $user_id);


            }
            if (count($orders) > 0) {
                $output['code'] = 200;

                $output['msg'] = $orders;
                echo json_encode($output);
                die();

            } else {

                Message::EmptyDATA();
                die();

            }


        }
    }





    public function editRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);





            $user_id = @$data['user_id'];


            $about  = strtolower(@$data['about']);
            $about  = ucwords($about);


            $phone            = @$data['phone'];

            $updated          = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
            $google_analytics = @$data['google_analytics'];


            $preparation_time      = @$data['preparation_time'];
            $min_order_price      =  @$data['min_order_price'];
            $delivery_free_range      = @$data['delivery_free_range'];
            $city = $data['city'];
            $state = $data['state'];
            $country = $data['country'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];
            $name = $data['name'];
            $slogan = $data['slogan'];
            $speciality = $data['speciality'];



            $restaurant_timing = @$data['restaurant_timing'];

            $restaurant['about']            = $about;
            $restaurant['updated']          = $updated;
            $restaurant['google_analytics'] = $google_analytics;
            $restaurant['user_id'] = $user_id;

            $restaurant['name'] = $name;

            $restaurant['slogan'] = $slogan;
            $restaurant['speciality'] = $speciality;
            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;

            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;

            $restaurant['phone']            = $phone;

            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['min_order_price']  =  $min_order_price;
            $restaurant['delivery_free_range'] = $delivery_free_range;
            $id            = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {

            $restaurant_id = $id[0]['Restaurant']['id'];

                //delete images-------------------

                $restaurant_detail = $this->Restaurant->getRestaurantDetail($restaurant_id);


                $image       = $restaurant_detail[0]['Restaurant']['image'];
                $cover_image = $restaurant_detail[0]['Restaurant']['cover_image'];


                $restaurant_location['restaurant_id'] = $restaurant_id;

                //   -----------------------------------------------
                if (isset($data['image']) && $data['image'] != " ") {

                    if(is_file($image)) {
                        @unlink($image);
                    }

                    $image      = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath            = Lib::uploadFileintoFolder($restaurant_id, $image, $folder_url);
                    $restaurant['image'] = $filePath;
                }

                if (isset($data['cover_image']) && $data['cover_image'] != " ") {
                    if(is_file($cover_image)) {
                        @unlink($cover_image);
                    }

                    $cover_image = $data['cover_image'];
                    $folder_url  = UPLOADS_FOLDER_URI;

                    $filePath                  = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                    $restaurant['cover_image'] = $filePath;
                }








                $this->RestaurantTiming->deleteAll(array(
                    'restaurant_id' => $restaurant_id
                ), false);

                foreach ($restaurant_timing as $k => $v) {


                    $timing[$k]['day']           = @$v['day'];
                    $timing[$k]['opening_time']  = @$v['opening_time'];
                    $timing[$k]['closing_time']  = @$v['closing_time'];
                    $timing[$k]['restaurant_id'] = $restaurant_id;

                }

                $this->RestaurantTiming->saveAll($timing);
                $this->RestaurantLocation->id = $restaurant_id;
                $this->RestaurantLocation->save($restaurant_location);

                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant);

                $rest_details   = $this->Restaurant->getRestaurantDetail($restaurant_id);
                $output['code'] = 200;
                $output['msg']  = $rest_details;
                echo json_encode($output);

            } else {

                $this->Restaurant->save($restaurant);
                $id  = $this->Restaurant->getInsertID();

                if (isset($data['image']) && $data['image'] != " ") {

                    $image = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath = Lib::uploadFileintoFolder($id, $image, $folder_url);
                    $restaurant_image['image'] = $filePath;
                    $this->Restaurant->id = $id;
                    $this->Restaurant->save($restaurant_image);

                }

                if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                    $cover_image = $data['cover_image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath = Lib::uploadFileintoFolder($id, $cover_image, $folder_url);
                    $restaurant_image['cover_image'] = $filePath;
                    $this->Restaurant->id = $id;
                    $this->Restaurant->save($restaurant_image);
                }

                foreach ($restaurant_timing as $k => $v) {


                    $timing[$k]['day']           = @$v['day'];
                    $timing[$k]['opening_time']  = @$v['opening_time'];
                    $timing[$k]['closing_time']  = @$v['closing_time'];
                    $timing[$k]['restaurant_id'] = $id;

                }
                $restaurant_location['restaurant_id'] = $id;
                $this->RestaurantLocation->save($restaurant_location);
                $this->RestaurantTiming->saveAll($timing);
                $rest_details   = $this->Restaurant->getRestaurantDetail($id);
                $output['code'] = 200;
                $output['msg'] = $rest_details;
                echo json_encode($output);


                die();



            }
        }


    }



    public function placeOrder()
    {


        $this->loadModel("Order");
        $this->loadModel("User");
        $this->loadModel("UserInfo");
        $this->loadModel("Address");
        $this->loadModel("OrderMenuItem");
        $this->loadModel("OrderMenuExtraItem");
        $this->loadModel("CouponUsed");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("Restaurant");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, true);		
			if(!is_array($data)){
				$data = json_decode($data, true);	
			}
			
			

            $user_id       = $data['user_id'];
            $quantity      = $data['quantity'];
            $payment_id    = $data['payment_id'];
            $address_id    = $data['address_id'];
            $restaurant_id = $data['restaurant_id'];
			
            $cod           = $data['cod'];
            $tax           = $data['tax'];
            $sub_total     = $data['sub_total'];
				
            $instructions  = $data['instructions'];
            $coupon_id     = $data['coupon_id'];
            $status        = 1;
            $device        = @$data['device'];
            $version     =   @$data['version'];


            $delivery_fee  = $data['delivery_fee'];
            $delivery      = $data['delivery'];
            $rider_tip     = $data['rider_tip'];
            $booking_date     = $data['booking_date'];
            $booking_day_id     = $data['booking_day_id'];
            $booking_day_time_id     = $data['booking_day_time_id'];
            $booking_time_id     = $data['booking_time_id'];
            $booking_available_status     = $data['booking_available_status'];

            $created   = $data['order_time'];
            $menu_item = $data['menu_item'];



            if (count($menu_item) < 1) {

                echo Message::ERROR();
                die();
            }

            if($this->User->iSUserExist($user_id) == 0){

                echo Message::ERROR();
                die();
            }

            if($sub_total < 1){
                echo Message::ERROR();
                die();
            }




            $price = $delivery_fee + $rider_tip + $tax + $sub_total;




            $user_details_check = $this->UserInfo->getUserDetailsFromID($user_id);
            $restaurant_detail_check = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);
            $order_number = date('ymdHis').rand(10,99); // 2021062112561201
            if(count($user_details_check) > 0 && count($restaurant_detail_check) > 0) {
                
                $order['user_id'] = $user_id;
                $order['price'] = $price;
                $order['status'] = $status;
                $order['created'] = $created;
                $order['quantity'] = $quantity;
                $order['payment_method_id'] = $payment_id;
                $order['cod'] = $cod;
                $order['version'] = $version;

                $order['address_id'] = $address_id;
                $order['sub_total'] = $sub_total;
                $order['tax'] = $tax;
                $order['device'] = $device;
                $order['delivery'] = $delivery;
                $order['rider_tip'] = $rider_tip;
                $order['restaurant_id'] = $restaurant_id;
                $order['instructions'] = $instructions;
                $order['delivery_fee'] = $delivery_fee;
                $order['order_number'] = $order_number;
                $order['booking_date'] = $booking_date?$booking_date:'0000-00-00';
                $order['booking_day'] = $booking_day_id?$booking_day_id:'';
                $order['booking_day_time'] = $booking_day_time_id?$booking_day_time_id:'';
                $order['booking_time_id'] = $booking_time_id?$booking_time_id:0;
                $order['available_status'] = $booking_available_status?$booking_available_status:0;

                if (isset($data['phone_no'])) {


                    $order['phone_no'] = $data['phone_no'];
                }

                if (isset($data['ruc_id'])) {


                    $order['ruc_id'] = $data['ruc_id'];
                }

                $this->Order->query('SET FOREIGN_KEY_CHECKS=0');


                $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant_id);
                $address_detail = $this->Address->getAddressDetail($address_id);
                /*if(count($address_detail) > 0) {




                    if ($address_detail[0]['Address']['city'] != $restaurant_location[0]['RestaurantLocation']['city']) {

                        $output['code'] = 202;
                       $output['msg'] = "Address is different from the restaurant location. Please select 'Pizza Hut' Restaurant and address should be Newyork USA ";
                        echo json_encode($output);
                        die();
                    }
        }*/

                $if_order_exist = $this->Order->isOrderExist($order);


                if (count($if_order_exist) > 0) {

                    $time_diff = Lib::time_difference($if_order_exist['Order']['created'], $created);


                    if (count($if_order_exist) > 0 && $time_diff <= 60) {

                        $output['code'] = 200;
                        $output['msg'] = "Your order has already been placed.";
                        echo json_encode($output);
                        die();

                    }
                }


                if ($payment_id > 0) {
                    $stripe_charge = $this->deductPayment($payment_id, round($price));
                    $order['stripe_charge'] = $stripe_charge;
                }


                if ($this->Order->save($order)) {
                    $order_id = $this->Order->getLastInsertId();
                    $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);


                    $restaurant_user_id = $restaurant_detail[0]['Restaurant']['user_id'];
                    $restaurant_user_details = $this->UserInfo->getUserDetailsFromID($restaurant_user_id);
                    $device_token = $restaurant_user_details['UserInfo']['device_token'];


                    Firebase::placeOrder($order_id, $restaurant_user_id, $delivery);


                    if ($coupon_id > 0) {
                        $coupon['coupon_id'] = $coupon_id;
                        $coupon['order_id'] = $order_id;
                        $coupon['created'] = $created;
                        $this->CouponUsed->save($coupon);
                    }

                    for ($i = 0; $i < count($menu_item); $i++) {

                        $order_menu_item[$i]['name'] = $menu_item[$i]['menu_item_name'];
                        $order_menu_item[$i]['quantity'] = $menu_item[$i]['menu_item_quantity'];
                        $order_menu_item[$i]['price'] = $menu_item[$i]['menu_item_price'];

                        $order_menu_item[$i]['order_id'] = $order_id;
                        $this->OrderMenuItem->saveAll($order_menu_item[$i]);
                        $order_menu_item_id = $this->OrderMenuItem->getLastInsertId();
                        if (array_key_exists('menu_extra_item', $menu_item[$i])) {

                            if (count($menu_item[$i]['menu_extra_item']) > 0 && $menu_item[$i]['menu_extra_item'] != "") {
                                for ($j = 0; $j < count($menu_item[$i]['menu_extra_item']); $j++) {


                                    $order_menu_extra_item[$j]['name'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_name'];
                                    $order_menu_extra_item[$j]['quantity'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_quantity'];
                                    $order_menu_extra_item[$j]['price'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_price'];
                                    $order_menu_extra_item[$j]['order_menu_item_id'] = $order_menu_item_id;
                                    $this->OrderMenuExtraItem->saveAll($order_menu_extra_item[$j]);
                                }
                            }
                        }
                    }
                    $order_detail = $this->Order->getOrderDetailBasedOnID($order_id);
                    
            
                    
                    /************notification*************/


                    $notification['to'] = $device_token;
                    $notification['notification']['title'] = "You have received a new order";
                    $notification['notification']['body'] = 'Order #' . $order_detail[0]['Order']['id'] . ' ' . $order_detail[0]['OrderMenuItem'][0]['name'];
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "";
                    $notification['notification']['data']= "";
                    $notification['data']['title'] = "You have received a new order";
                    $notification['data']['body'] = 'Order #' . $order_detail[0]['Order']['id'] . ' ' . $order_detail[0]['OrderMenuItem'][0]['name'];
                    $notification['data']['icon'] = "";
                    $notification['data']['badge'] = "1";
                    $notification['data']['sound'] = "default";
                    $notification['data']['type'] = "";
                    
    // CakeLog::write('debug', '$notification: '.print_r(json_encode($notification), TRUE));

                    
                    PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));


                    /********end notification***************/


                }
  //                  $output['code'] = 200;
//                    $output['msg'] = $order_number;

                if ($delivery == 1) {
                    $restaurant_will_pay = 0;


                    $distance_difference_btw_user_and_restaurant = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $address_detail[0]['Address']['lat'], $address_detail[0]['Address']['long']);

                    //convert distance in Kms from miles
                    $distance = $distance_difference_btw_user_and_restaurant['rows'][0]['elements'][0]['distance']['value'] * 0.001;


                    $min_order_price = $restaurant_detail[0]['Restaurant']['min_order_price'];
                    $delivery_free_range = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                    if ($sub_total >= $min_order_price && $distance > $delivery_free_range) { //case 1

                        $distance_difference = $distance - $delivery_free_range;
                        $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance_difference;
                        $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $delivery_free_range;
                        // $total_amount = $delivery_fee + $sub_total;

                    } else if ($sub_total < $min_order_price && $distance > $delivery_free_range) {


                        $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;


                    } else if ($sub_total > $min_order_price && $distance <= $delivery_free_range) {

                        // $total_amount = $sub_total;
                        $delivery_fee_new = "0";
                        $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;

                    } else if ($sub_total < $min_order_price && $distance <= $delivery_free_range) {
                        // $distance_difference = 5 - $distance;
                        $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;

                    }


                    $delivery_fee_add_zero_in_the_end = strlen(substr(strrchr($delivery_fee, "."), 1));
                    if ($delivery_fee_add_zero_in_the_end == 1) {


                        $delivery_fee = $delivery_fee . "0";
                    }
                    
            


                    $order_update['restaurant_delivery_fee'] = $restaurant_will_pay;
                    $order_update['total_distance_between_user_and_restaurant'] = $distance;
                    $order_update['delivery_fee_per_km'] = $restaurant_detail[0]['Tax']['delivery_fee_per_km'];
                    $order_update['delivery_free_range'] = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                    /*********/

                    $this->Order->id = $order_id;

                    if ($this->Order->save($order_update)) {


                        //$this->UserInfo->id = $user_id;

                        /*send an email*/

                        $user_details = $this->UserInfo->getUserDetailsFromID($user_id);

                        //$email_data['User'] = $user_details['User'];
                        $order_detail_email[0]['User'] = $user_details['User'];
                        $email_data['OrderDetail'] = $order_detail_email[0];
                        // $order_detail[0]['User'] = $user_details['User'];
                        // $email_data['OrderDetail'] = $order_detail[0];

                     //   CustomEmail::sendEmailPlaceOrderToUser($email_data);
                        /**********/


                    }
            }
            
                $output['code'] = 200;

                 $output['msg'] = $order_detail;
                 //$output['msg'] = 1;//$order['order_number'];
                echo json_encode($output);
                die();
            }else{

                $output['code'] = 201;

                $output['msg'] = "user id or restaurant id do not exist";
                echo json_encode($output);
                die();


            }





        }
    }
    
    function calculateDeliveryFee(){
        $this->loadModel("RestaurantLocation");
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $lat1       = $data['lat'];
        $long1      = $data['long'];
        $id          = $data['id'];
        $fee     = $data['fee'];
        

        // $lat1       = 26.210869;
        // $long1      = 50.186491;
        // $lat2       = 26.210869;
        // $long2      = 50.186491;
        // $id    = 1;
        // $fee     = 2;
        
        $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($id);
        
            // CakeLog::write('debug', '$restaurant_location: '.print_r(json_encode($restaurant_location), TRUE));
            
        // $lat1       = 26.210869;
        // $long1      = 50.186491;

            
        $lat2       = $restaurant_location[0]['RestaurantLocation']['lat'];
        $long2      = $restaurant_location[0]['RestaurantLocation']['long'];


        $url  = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&key=".GOOGLE_MAPS_KEY;
        
                    // CakeLog::write('debug', '$url: '.print_r($url, TRUE));


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);




        $output_array = json_decode($response,'true');
        
        

        

        if(!is_array($output_array)){
            return false;
        }
        if (array_key_exists('error_message', $output_array)){
            return false;

        }


        if($output_array['rows'][0]['elements'][0]['status'] =="ZERO_RESULTS"
            || $output_array['rows'][0]['elements'][0]['status'] =="NOT_FOUND" ){
            return json_encode($url);

        }


        else{
            $distance =  $output_array['rows'][0]['elements'][0]['distance']['value'] * 0.001;
            $delivery_fee = $fee * $distance;
            
            if($delivery_fee >= 1){
                $delivery_fee = round($delivery_fee);
            }
            
            // CakeLog::write('debug', '$delivery_fee: '.print_r($delivery_fee, TRUE));

            return json_encode($delivery_fee);
        }

    }


    function deductPayment($payment_id,$total)
    {
        $this->loadModel('Order');
        $this->loadModel('PaymentMethod');
        $this->loadModel('StripeCharge');




        // $expense =  $order_gig_post[0]['OrderGigPost']['extra_expense_seller'];
        $this->PaymentMethod->id = $payment_id;
        $stripe_cust_id  = $this->PaymentMethod->field('stripe');


        if (strlen($stripe_cust_id) > 1) {



            $a = array(
                'customer' => $stripe_cust_id,
                'currency' => STRIPE_CURRENCY,

                'amount' => $total * 100
            );



            $result = $this->StripeCharge->save($a);
            if (!$result) {

                $error          = $this->StripeCharge->getStripeError();
                $output['code'] = 201;

                $output['msg'] = $error;
                return $output;
                die();
            } else {
                return $result['StripeCharge']['id'];
            }


        } else {
            $output['code'] = 201;

            $output['msg'] = "Please add a card first";
            return $output;
            die();


        }

    }



    public function showRiderLocationAgainstOrder()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];
            $user_id = $data['user_id'];
            $map_change = $data['map_change'];



            $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($order_id);
            $pickup_time = $this->RiderTrackOrder->isEmptyPickUpTime($order_id);
            $on_my_way_to_user_time = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($order_id);
            $delivery_time = $this->RiderTrackOrder->isEmptyDeliveryTime($order_id);
            $order_detail = $this->Order->getOrderDetailBasedOnID($order_id);


            $status_0 = "";
            $status_1 = "";
            $status_2 = "";
            $status_3 = "";
            $status_4 = "";
            $status_5 = "";
            $status_6 = "";
            $status_7 = "";

            if ($order_detail[0]['Order']['hotel_accepted'] == 0) {

                $status_0 = "Order is in processing";
                $status_pusher[0]['order_status'] = $status_0;
                if($map_change == 1){
                    $status_pusher[0]['map_change'] = $map_change;
                }else {
                    $status_pusher[0]['map_change'] = "0";
                }
            }else {

                $status_0 = "Order is in processing";
                $status_pusher[0]['order_status'] = $status_0;
                if($map_change == 1){
                    $status_pusher[0]['map_change'] = $map_change;
                }else {
                    $status_pusher[0]['map_change'] = "0";
                }

            }

            if ($order_detail[0]['Order']['hotel_accepted'] == 1) {

                $status_1 = $order_detail[0]['Restaurant']['name'] . ' ' . "has accepted your order and processing it";
                $status_pusher[1]['order_status'] = $status_1;
                if($map_change == 1){
                    $status_pusher[1]['map_change'] = $map_change;
                }else {
                    $status_pusher[1]['map_change'] = "0";
                }

            }

            if ($order_detail[0]['RiderOrder']['id'] > 0) {
                if (Lib::multi_array_key_exists('RiderOrder', $order_detail)) {


                    $status_2 = "Order has been assigned to " . $order_detail[0]['RiderOrder']['Rider']['first_name'];
                    //$status_pusher[0]['order_status'] =  $status_0;
                    $status_pusher[2]['order_status'] = $status_2;
                    if($map_change == 1){
                        $status_pusher[2]['map_change'] = $map_change;
                    }else {
                        $status_pusher[2]['map_change'] = "1";
                    }
                }


                if ($on_my_way_to_hotel_time == 1) {


                    $status_3 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "is on the way to restaurant to pickup your order";
                    //$status_pusher[0]['order_status'] =  $status_0;
                    $status_pusher[3]['order_status'] = $status_3;
                    if($map_change == 1){
                        $status_pusher[3]['map_change'] = $map_change;
                    }else {
                        $status_pusher[3]['map_change'] = "0";
                    }

                    //  $status = "order is in processing";
                    //$status_pusher[0]['order_status'] = $status;

                }

                if ($pickup_time == 1) {


                    $status_4 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "has picked up your food";

                    $status_pusher[4]['order_status'] = $status_4;
                    if($map_change == 1){
                        $status_pusher[4]['map_change'] = $map_change;
                    }else {
                        $status_pusher[4]['map_change'] = "1";
                    }

                }
                if ($on_my_way_to_user_time == 1) {


                    $status_5 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "is on the way to you";

                    $status_pusher[5]['order_status'] = $status_5;
                    if($map_change == 1){
                        $status_pusher[5]['map_change'] = $map_change;
                    }else {
                        $status_pusher[5]['map_change'] = "0";
                    }


                }

                if ($delivery_time == 1) {


                    $status_6 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "just delivered the food";

                    $status_pusher[6]['order_status'] = $status_6;
                    if($map_change == 1){
                        $status_pusher[6]['map_change'] = $map_change;
                    }else {
                        $status_pusher[6]['map_change'] = "0";
                    }

                }

            }
            $reverse_status_pusher = array_reverse($status_pusher);

            $rider = $this->RiderOrder->getRiderDetailsAgainstOrderID($order_id);
            $result[0]['Restaurant'] = $order_detail[0]['Restaurant'];
            $result[0]['Order'] = $order_detail[0]['Order'];

            //  $rider_location = $this->RiderLocation->getRiderLocation($rider[0]['RiderOrder']['rider_user_id']);
            if (count($rider) > 0 && $pickup_time > 0) {


                //order has been assigned and picked up
                $result[0]['RiderOrder'] = $rider[0]['RiderOrder'];

                $result[0]['Rider'] = $rider[0]['Rider'];

                $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
                $result[0]['UserLocation'] = $rider[0]['Order']['Address'];
                $result[0]['RestaurantLocation']['lat'] = "";
                $result[0]['RestaurantLocation']['long'] = "";

                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


            } else if (count($rider) > 0 && $pickup_time == 0) {

                //order has been assigned but not picked up yet

                $result[0]['RiderOrder'] = $rider[0]['RiderOrder'];
                $result[0]['Rider'] = $rider[0]['Rider'];


                $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
                $result[0]['UserLocation']['lat'] = "";
                $result[0]['UserLocation']['long'] = "";
                $result[0]['RestaurantLocation'] = $rider[0]['Order']['Restaurant']['RestaurantLocation'];

                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


            } else {

                //no order has been assigned to rider...: send only restaurant location

                $restaurant_location = $this->Order->getOrderDetailBasedOnID($order_id);

                $result[0]['RiderOrder']['RiderLocation']['lat'] = "";
                $result[0]['RiderOrder']['RiderLocation']['long'] = "";
                $result[0]['Rider']['first_name'] = "";
                $result[0]['Rider']['last_name'] = "";
                $result[0]['Rider']['phone'] = "";


                $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
                $result[0]['UserLocation']['lat'] = "";
                $result[0]['UserLocation']['long'] = "";
                $result[0]['RestaurantLocation'] = $restaurant_location[0]['Restaurant']['RestaurantLocation'];

                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);

            }






        }
    }
    public function restaurantOwnerResponse()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("Setting");
        $this->loadModel("UserInfo");
        $this->loadModel('OrderMenuItem');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id       = $data['order_id'];


            $response = $data['response'];
            $reason = $data['reason'];

            $ordersInfo = $this->Order->findById($order_id);
            
            $restaurant_response['hotel_accepted'] = $response;
            if($response == 1) {


                $restaurant_response['accepted_reason'] = $reason;

                $restaurant_detail = $this->Order->getRestaurantName($order_id);


                Firebase::updateRestaurantOrderStatus($order_id, $restaurant_detail[0]['Restaurant']['name']);

                /**autometic assign**/


                $setting_details = $this->Setting->getSettingsAgainstType("auto_assign_order");

                if (count($setting_details) > 0) {

                    if ($setting_details['Setting']['value'] == 1) {
                        $rest_details = $this->RestaurantLocation->getRestaurantLocation($restaurant_detail[0]['Restaurant']['id']);

                        $this->findAvailableNearestRiders($order_id, $rest_details);
                    }

                }


                /*********/

            }  if($response == 2){


                $restaurant_response['rejected_reason'] = $reason;



            }



            $user_id = $data['user_id'];


            $id            = $this->Restaurant->getRestaurantID($user_id);



            // $restaurant_id = $id[0]['Restaurant']['id'];

            if(count($id) > 0){

                $hotel_response = $this->Order->checkAcceptedOrRejectedResponse($order_id);
                
                
                if ($ordersInfo['Order']['status'] == 4) {


                    $output['code'] = 210;
                    $output['msg']  = "Already Cancelled this Order.";
                    echo json_encode($output);
                    die();
                }
                
                $this->Order->id = $order_id;
                $user_id = $this->Order->field('user_id');
                $this->UserInfo->id = $user_id;
                $device_token = $this->UserInfo->field('device_token');
                $menu_details = $this->OrderMenuItem->getMenuItem($order_id);

                if ($hotel_response[0]['Order']['hotel_accepted'] == 0) {

                    if($response == 1) {
                        $this->Order->id = $order_id;
                        $delivery = $this->Order->field('delivery');
                        if($delivery < 1){

                            $restaurant_response['status'] = 2;

                        }
                        $this->Order->id = $order_id;
                       if ($this->Order->save($restaurant_response)) {

                            /* send push notification*/





                            if (strlen($device_token) > 10) {

                                /************notification*************/


                                $notification['to'] = $device_token;
                                $notification['notification']['title'] = "Order has been accepted by the restaurant";
                                $notification['notification']['body'] = $menu_details['OrderMenuItem']['name'].' has been accepted by '.$restaurant_detail[0]['Restaurant']['name'];
                                $notification['notification']['badge'] = "1";
                                                    $notification['notification']['sound'] = "default";
 

                                $notification['notification']['icon'] = "";
                                $notification['notification']['type'] = "";
                                $notification['notification']['data']= "";

                                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                                /********end notification***************/
                            }

                            echo Message::DATASUCCESSFULLYSAVED();


                            die();
                        } else {

                            echo Message::DATASAVEERROR();
                            die();

                        }
                    }else{

                        $this->Order->id = $order_id;
                        if ($this->Order->save($restaurant_response)) {


                            /************notification*************/


                            $notification['to'] = $device_token;
                            $notification['notification']['title'] = "Order has been rejected by the restaurant";
                            $notification['notification']['body'] = $reason;
                            $notification['notification']['badge'] = "1";
                                                $notification['notification']['sound'] = "default";
 

                            $notification['notification']['icon'] = "";
                            $notification['notification']['type'] = "";
                            $notification['notification']['data']= "";

                            PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                            //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                            /********end notification***************/



                            echo Message::DATASUCCESSFULLYSAVED();


                            die();
                        } else {

                            echo Message::DATASAVEERROR();
                            die();

                        }

                    }
                } else if ($hotel_response[0]['Order']['hotel_accepted'] == 1) {


                    $output['code'] = 201;
                    $output['msg']  = "Already Accepted";
                    echo json_encode($output);
                    die();
                } else if ($hotel_response[0]['Order']['hotel_accepted'] == 2) {


                    $output['code'] = 201;
                    $output['msg']  = "Already Rejected";
                    echo json_encode($output);
                    die();
                }
            }else{

                $output['code'] = 203;
                $output['msg']  = "restaurant do not exist";
                echo json_encode($output);
                die();

            }

        }
    }


    public function findAvailableNearestRiders($order_id,$restaurant){


        $this->loadModel('Order');
        $this->loadModel('RiderOrder');
        $this->loadModel('User');
        $this->loadModel('UserInfo');

        // $order_detail   = $this->Order->getOrderDetailBasedOnID($order_id);

        $res_lat = $restaurant['RestaurantLocation']['lat'];
        $res_long = $restaurant['RestaurantLocation']['long'];



        $json = Firebase::getRiderLocations();

        $data = json_decode($json, TRUE);



        if(count($data) > 0) {


            foreach ($data as $key => $val) {





                //echo $data['rider_lat'].'<br>';

                $distance[$key]['distance'] = Lib::distance($res_lat, $res_long, $val['rider_lat'], $val['rider_long'], "N");
                $distance[$key]['rider_id'] = $key;


            }



            array_multisort($distance, SORT_ASC, $distance);



            //$datanew = array_keys($distance, min($distance));
            foreach ($distance as $d) {


                $ifRiderExist =  $this->User->ifRiderExist($d['rider_id']);

                if($ifRiderExist > 0){


                    $ifassigned = $this->RiderOrder->checkIfOrderHasBeenAssignedToRiderOrNot($order_id, $d['rider_id']);
                    $rider_online = $this->UserInfo->checkIfRiderOnline($d['rider_id']);


                    if ($ifassigned == 0 && $rider_online == 1) {
                        $output = $this->assignOrderToRiderAutometically($d['rider_id'], 1, $order_id);

                      

                        return $output;


                    }else{



                    }
                }


            }
        }else{

            $output['code'] = 201;

            $output['msg'] = "No rider location exist in database";
            return $output;


        }



    }

    public function assignOrderToRiderAutometically($rider_user_id,$assigner_user_id,$order_id)
    {

        $this->loadModel("RiderOrder");
        $this->loadModel("Order");
        $this->loadModel("UserInfo");







        //  $rider_user_id = $data['rider_user_id'];
        //$assigner_user_id = $data['assigner_user_id'];
        //$order_id = $data['order_id'];
        $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

        $this->Order->id = $order_id;
        $delivery = $this->Order->field('delivery');
        if($delivery == 0){


            $output['code'] = 201;

            $output['msg'] = "You can't assign this order to any rider because user will himself pickup the food from the restaurant ";
            return $output;
        }

        if(isset($data['id'])){
            // $this->RiderOrder->id = $data['id'];
            $this->RiderOrder->delete($data['id']);

        }

        $rider_order['rider_user_id'] = $rider_user_id;
        $rider_order['assigner_user_id'] = $assigner_user_id;
        $rider_order['order_id'] = $order_id;
        $rider_order['assign_date_time'] = $created;
        $this->UserInfo->id = $rider_user_id;

        //$device_token = $this->UserInfo->field('device_token');
        $rider_name = $this->UserInfo->field('first_name');


        if ($this->RiderOrder->isDuplicateRecord($rider_user_id, $assigner_user_id, $order_id) <= 0) {

            if ($this->RiderOrder->save($rider_order)) {

                /*firebase*/
                $rider_order_id = $this->RiderOrder->getLastInsertId();


                $curl_date[$order_id] =
                    array (



                        'order_status' => 'Order has been assigned to '.$rider_name,
                        'map_change' => "1",




                    );

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => FIREBASE_URL."tracking_status.json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PATCH",
                    CURLOPT_POSTFIELDS => json_encode($curl_date),
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
                    ),
                ));

                $response_curl = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    // echo "cURL Error #:" . $err;
                } else {
                    //echo $response_curl;
                }


                $this->Order->id = $order_id;
                $user_id_order = $this->Order->field('user_id');
                $this->UserInfo->id = $user_id_order;
                $user_device_token = $this->UserInfo->field('device_token');
                $this->UserInfo->id = $rider_user_id;
                $rider_device_token = $this->UserInfo->field('device_token');


                $order_detail   = $this->Order->getOrderDetailBasedOnID($order_id);

                $notification['to'] = $rider_device_token;
                $notification['notification']['title'] = "Order has been assigned to the rider";
                $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
 
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";

                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /********end notification***************/


                /************notification to USER*************/





                $notification['to'] = $user_device_token;
                $notification['notification']['title'] = "Order has been assigned to the rider";
                $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
 
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";

                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /********end notification***************/

                /*firebase*/



                $curl_data2['order_id'] = $order_id;
                $curl_data2['status'] = "0";
                $curl_data2['symbol'] = $order_detail[0]['Restaurant']['Currency']['symbol'];
                $curl_data2['price'] = $order_detail[0]['Order']['price'];
                $curl_data2['restaurants'] = $order_detail[0]['Restaurant']['name'];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => FIREBASE_URL."RiderOrdersList/".$rider_user_id."/CurrentOrders/.json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($curl_data2),
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
                    ),
                ));

                $response_curl = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    // echo "cURL Error #:" . $err;
                } else {
                    $snap = json_decode($response_curl,true);
                    $name = $snap['name'];
                    $this->RiderOrder->id = $rider_order_id;
                    $this->RiderOrder->saveField('snap',$name);
                }


                $output['code'] = 200;
                $output['msg']  = "Order has been assigned successfully to Rider";
                return $output;

            } else {


                $output['code'] = 201;
                $output['msg']  = "save error";
                return $output;
            }

        } else {


            $output['code'] = 201;
            $output['msg']  = "duplicate data";
            return $output;
        }



    }
    public function verifyCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("CouponUsed");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $coupon_code   = $data['coupon_code'];
            $restaurant_id = $data['restaurant_id'];
            $coupon_exist  = $this->RestaurantCoupon->isCouponCodeExistAgainstRestaurant($coupon_code, $restaurant_id);

            if(count($coupon_exist) > 0) {

                $coupon_id = $coupon_exist[0]['RestaurantCoupon']['id'];
                $user_limit = $coupon_exist[0]['RestaurantCoupon']['limit_users'];
                $count_coupon_used = $this->CouponUsed->countCouponUsed($coupon_id);

                $coupon_user_used = $this->RestaurantCoupon->ifCouponUsedAgainstRestaurant($user_id, $coupon_code, $restaurant_id);


                if (count($coupon_exist) == 1 && $coupon_user_used == 1) {

                    $output['code'] = 201;


                    $output['msg'] = "invalid coupon code";

                    echo json_encode($output);

                    die();

                } else if (count($coupon_exist) == 1 && $coupon_user_used == 0 && $count_coupon_used < $user_limit) {

                    $coupon = $this->RestaurantCoupon->getCouponDetails($restaurant_id, $coupon_code);


                    $output['code'] = 200;


                    $output['msg'] = $coupon;

                    echo json_encode($output);

                    die();


                }else{



                    $output['code'] = 201;


                    $output['msg'] = "invalid coupon code";

                    echo json_encode($output);

                    die();
                }


            }else{


                $output['code'] = 201;


                $output['msg'] = "invalid coupon code";

                echo json_encode($output);

                die();

            }








        }
    }

    public function editUserProfile()
    {

        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id    = $data['user_id'];
            $first_name = $data['first_name'];
            $last_name  = $data['last_name'];
            // $email      = $data['email'];




            $user_info['first_name'] = $first_name;
            $user_info['last_name']  = $last_name;
            //$user_info['email']      = $email;





            $this->UserInfo->id = $user_id;
            if ($this->UserInfo->save($user_info)) {
                $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                $output['code'] = 200;

                $output['msg'] = $userDetails;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();

            }

        }
    }


    public function addBankingInfo()
    {

        $this->loadModel("BankingInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_banking_info['name']       = $data['name'];
            $rider_banking_info['account_no'] = $data['account_no'];
            $rider_banking_info['user_id']    = $data['user_id'];
            $rider_banking_info['bank_no']    = $data['bank_no'];
            $rider_banking_info['transit_no'] = $data['transit_no'];






            if (isset($data['id'])) {

                $id                    = $data['id'];
                $this->BankingInfo->id = $id;
                $this->BankingInfo->save($rider_banking_info);

                $banking_info   = $this->BankingInfo->getBankingInfo($data['user_id']);
                $output['code'] = 200;
                $output['msg']  = $banking_info;
                echo json_encode($output);


                die();
            } else
                //echo $this->RiderBankingInfo->isDuplicateRecord($data['user_id'],$data['name'], $data['transit_no'],$data['bank_no'],$data['account_no']);
                if ($this->BankingInfo->isDuplicateRecord($data['user_id'], $data['name'], $data['transit_no'], $data['bank_no'], $data['account_no']) == 0) {
                    if ($this->BankingInfo->save($rider_banking_info)) {

                        $banking_info   = $this->BankingInfo->getBankingInfo($data['user_id']);
                        $output['code'] = 200;
                        $output['msg']  = $banking_info;
                        echo json_encode($output);

                    } else {


                        echo Message::DATASAVEERROR();
                        die();
                    }
                } else {

                    echo Message::DUPLICATEDATE();
                    die();
                }

        }


    }

    public function showRiderCompletedOrders()
    {



        $this->loadModel("RiderOrder");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            if (isset($data['starting_date'])) {

                $starting_date = $data['starting_date'];
                $ending_date   = $data['ending_date'];
                $orders        = $this->RiderOrder->getRiderOrdersBetweenTwoDates($user_id, $starting_date, $ending_date);

            } else {

                $orders = $this->RiderOrder->getRiderCompletedOrders($user_id);
            }


            $output['code'] = 200;
            $output['msg']  = $orders;
            echo json_encode($output);


            die();



        }
    }



    public function showBankingInfo()
    {

        $this->loadModel("BankingInfo");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_id = $data['user_id'];

            $banking_info   = $this->BankingInfo->getBankingInfo($rider_id);
            $output['code'] = 200;
            $output['msg']  = $banking_info;
            echo json_encode($output);


            die();



        }
    }


    public function addMenu()
    {
        $this->loadModel('RestaurantMenu');
        $this->loadModel('Restaurant');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name        = $data['name'];
            $description = $data['description'];


            $user_id = $data['user_id'];

            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];
            $created       = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $restaurant_menu['name']          = $name;
            $restaurant_menu['description']   = $description;
            $restaurant_menu['restaurant_id'] = $restaurant_id;

            $restaurant_menu['created'] = $created;

            $menu = array();

            if (isset($data['image']) && $data['image'] != " ") {

                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath                 = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $restaurant_menu['image'] = $filePath;
            }

            if (isset($data['id'])) {

                if (isset($data['image']) && $data['image'] != " ") {
                    $menu = $this->RestaurantMenu->getDetails($data['id']);
                    $image_db = $menu['RestaurantMenu']['image'];
                    if (strlen($image_db) > 5) {
                        @unlink($image_db);

                    }

                    $image      = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath                 = Lib::uploadFileintoFolder(1, $image, $folder_url);
                    $restaurant_menu['image'] = $filePath;
                }

                $this->RestaurantMenu->id = $data['id'];
                $this->RestaurantMenu->save($restaurant_menu);

                $menu = $this->RestaurantMenu->getMainMenuFromID($data['id']);


            } else if ($this->RestaurantMenu->isDuplicateRecord($name, $description, $restaurant_id) == 0) {

                if ($this->RestaurantMenu->save($restaurant_menu)) {

                    $id   = $this->RestaurantMenu->getLastInsertId();
                    $menu = $this->RestaurantMenu->getMainMenuFromID($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();

        }
    }



    public function addMenuItem()
    {

        $this->loadModel('RestaurantMenu');
        $this->loadModel('RestaurantMenuItem');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name               = $data['name'];
            $description        = $data['description'];
            $restaurant_menu_id = $data['restaurant_menu_id'];
            $price              = $data['price'];
            $out_of_order       = $data['out_of_order'];
            $created            = date('Y-m-d H:i:s', time() - 60 * 60 * 4);



            $restaurant_menu_item['name']               = $name;
            $restaurant_menu_item['description']        = $description;
            $restaurant_menu_item['restaurant_menu_id'] = $restaurant_menu_id;
            $restaurant_menu_item['price']              = $price;
            $restaurant_menu_item['created']            = $created;
            $restaurant_menu_item['out_of_order']            = $out_of_order;



            if (isset($data['image']) && $data['image'] != " ") {

                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath                 = Lib::uploadFileintoFolder(1, $image, $folder_url);
                $restaurant_menu_item['image'] = $filePath;
            }


            if (isset($data['id'])) {

                if (isset($data['image']) && $data['image'] != " ") {
                    $menu = $this->RestaurantMenuItem->getDetails($data['id']);
                    $image_db = $menu['RestaurantMenuItem']['image'];
                    if (strlen($image_db) > 5) {
                        @unlink($image_db);

                    }

                    $image      = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath                 = Lib::uploadFileintoFolder(1, $image, $folder_url);
                    $restaurant_menu_item['image'] = $filePath;
                }

                $this->RestaurantMenuItem->id = $data['id'];
                $this->RestaurantMenuItem->save($restaurant_menu_item);
                $menu = $this->RestaurantMenuItem->getMenuItemFromID($data['id']);
            } else if ($this->RestaurantMenuItem->isDuplicateRecord($name, $description, $restaurant_menu_id, $price) == 0) {


                if ($this->RestaurantMenuItem->save($restaurant_menu_item)) {
                    $id   = $this->RestaurantMenuItem->getLastInsertId();
                    $menu = $this->RestaurantMenuItem->getMenuItemFromID($id);
                    $this->RestaurantMenu->id = $restaurant_menu_id;
                    $this->RestaurantMenu->saveField('has_menu_item', 1);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }

            } else {

                echo Message::DUPLICATEDATE();
                die();
            }
            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();

        }
    }

    public function addMenuExtraItem()
    {


        $this->loadModel('RestaurantMenuExtraItem');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name = $data['name'];
            //  $description = $data['description'];

            $price = $data['price'];

            $restaurant_menu_extra_section_id = $data['restaurant_menu_extra_section_id'];
            $created                          = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $restaurant_menu_extra_item['name'] = $name;
            // $restaurant_menu_extra_item['description'] = $description;

            $restaurant_menu_extra_item['price']   = $price;
            $restaurant_menu_extra_item['created'] = $created;

            $restaurant_menu_extra_item['restaurant_menu_extra_section_id'] = $restaurant_menu_extra_section_id;


            if (isset($data['id'])) {
                $this->RestaurantMenuExtraItem->id = $data['id'];
                $this->RestaurantMenuExtraItem->save($restaurant_menu_extra_item);
                $menu = $this->RestaurantMenuExtraItem->getMenuExtraItemFromID($data['id']);
            } else if ($this->RestaurantMenuExtraItem->isDuplicateRecord($name, $price, $restaurant_menu_extra_section_id) == 0) {


                if ($this->RestaurantMenuExtraItem->save($restaurant_menu_extra_item)) {
                    $id   = $this->RestaurantMenuExtraItem->getLastInsertId();
                    $menu = $this->RestaurantMenuExtraItem->getMenuExtraItemFromID($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();
        }
    }


    public function addMenuExtraSection()
    {

        $this->loadModel('Restaurant');
        $this->loadModel('RestaurantMenuExtraSection');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name    = $data['name'];
            $user_id = $data['user_id'];
            //  $description = $data['description'];

            $required      = $data['required'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];

            $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

            $restaurant_menu_item_id                                  = $data['restaurant_menu_item_id'];
            $restaurant_menu_extra_section['restaurant_menu_item_id'] = $restaurant_menu_item_id;
            $restaurant_menu_extra_section['name']                    = $name;

            $restaurant_menu_extra_section['restaurant_id'] = $restaurant_id;
            $restaurant_menu_extra_section['required']      = $required;



            if (isset($data['id'])) {
                $this->RestaurantMenuExtraSection->id = $data['id'];
                $this->RestaurantMenuExtraSection->save($restaurant_menu_extra_section);
                $section_names = $this->RestaurantMenuExtraSection->getRecentlyAddedSection($data['id']);

            } else if ($this->RestaurantMenuExtraSection->isDuplicateRecord($name, $restaurant_menu_item_id, $restaurant_id) == 0) {

                if ($this->RestaurantMenuExtraSection->save($restaurant_menu_extra_section)) {
                    $id            = $this->RestaurantMenuExtraSection->getLastInsertId();
                    $section_names = $this->RestaurantMenuExtraSection->getRecentlyAddedSection($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $section_names;
            echo json_encode($output);
            die();


        }
    }

    public function addUserDocument()
    {

        $this->loadModel("VerificationDocument");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id     = $data['user_id'];
            $description = $data['description'];

            $doc['user_id']     = $user_id;
            $doc['description'] = $description;
            if (isset($data['image']) && $data['image'] != " ") {



                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI . "/" . VERIFICATION_DOCUMENTS;

                $filePath    = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $doc['file'] = $filePath;
            }



            if ($this->VerificationDocument->save($doc)) {
                $id       = $this->VerificationDocument->getInsertID();
                $document = $this->VerificationDocument->getDocumentDetail($id);


                $output['code'] = 200;

                $output['msg'] = $document;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();

            }

        }
    }

    public function showUserDetail()
    {

        $this->loadModel("UserInfo");





        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $userDetail = $this->UserInfo->getUserDetailsFromID($user_id);


            $output['code'] = 200;

            $output['msg'] = $userDetail;
            echo json_encode($output);


            die();
        }

        }

    public function showUserDocuments()
    {

        $this->loadModel("VerificationDocument");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $documents = $this->VerificationDocument->getDocuments($user_id);


            $output['code'] = 200;

            $output['msg'] = $documents;
            echo json_encode($output);


            die();
        }
    }


    public function showTaxDetail()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Tax->getTaxDetail($id);


            $output['code'] = 200;

            $output['msg'] = $details;
            echo json_encode($output);


            die();
        }
    }


    public function deleteTax()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Tax->getTaxDetail($id);

            if(count($details) > 0){

                $this->Tax->id = $id;
                $this->Tax->delete();

                $details = $this->Tax->getTaxDetail($id);
                if(count($details) < 1){
                    $output['code'] = 200;

                    $output['msg'] = "deleted successfully";
                    echo json_encode($output);

                    die();
                }else{

                    $output['code'] = 201;

                    $output['msg'] = "something went wrong";
                    echo json_encode($output);
                    die();
                }
            }else{

                $output['code'] = 201;

                $output['msg'] = "No tax details found";
                echo json_encode($output);
                die();

            }


        }
    }


    public function addDeal()
    {

        $this->loadModel("Deal");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name        = $data['name'];
            $price       = $data['price'];
            $description = $data['description'];
            $starting_time = $data['starting_datetime'];
            $ending_time = $data['ending_datetime'];




            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];

            $deal['name']          = $name;
            $deal['price']         = $price;
            $deal['description']   = $description;
            $deal['starting_time']   = $starting_time;
            $deal['ending_time']   = $ending_time;
            $deal['restaurant_id'] = $restaurant_id;

            if (isset($data['image']) && $data['image'] != " ") {


                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath      = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $deal['image'] = $filePath;
            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                $cover_image = $data['cover_image'];
                $folder_url  = UPLOADS_FOLDER_URI;

                $filePath            = Lib::uploadFileintoFolder($user_id, $cover_image, $folder_url);
                $deal['cover_image'] = $filePath;
            }
//--------------------------------  editing-----------------------------------------
            if (isset($data['id'])) {
                $id = $data['id'];
                $deal_detail = $this->Deal->getDeal($id);
                if (isset($data['image'])) {
                    @unlink($deal_detail[0]['Deal']['image']);

                }

                if (isset($data['cover_image'])) {

                    @unlink($deal_detail[0]['Deal']['cover_image']);
                }

                $this->Deal->id = $id;
                $this->Deal->save($deal);

                $deal_detail = $this->Deal->getDeal($id);


                $output['code'] = 200;

                $output['msg'] = $deal_detail;
                echo json_encode($output);


                die();
            }
            //--------------------------------  end editing-----------------------------------------
            else if ($this->Deal->isDuplicateRecord($restaurant_id, $name, $price, $description) == 0) {


                if ($this->Deal->save($deal)) {
                    $id   = $this->Deal->getInsertID();
                    $deal = $this->Deal->getDeal($id);


                    $output['code'] = 200;

                    $output['msg'] = $deal;
                    echo json_encode($output);


                    die();
                } else {

                    echo Message::DATASAVEERROR();
                    die();

                }
            } else {

                echo Message::DUPLICATEDATE();
                die();

            }
        }
    }


    public function deleteDeal(){

        $this->loadModel("Deal");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['id'];

            $user_id       = $data['user_id'];
            $restaurant_detail            = $this->Restaurant->getRestaurantID($user_id);
            if(count($restaurant_detail)) {
                $restaurant_id = $restaurant_detail[0]['Restaurant']['id'];
                $deal = $this->Deal->getDealDetail($id,$restaurant_id);

                if(count($deal) > 0) {
                    $delete = $this->Deal->deleteDeal($id, $restaurant_id);
                    @unlink($deal[0]['Deal']['image']);
                    @unlink($deal[0]['Deal']['cover_image']);
                    if ($delete) {


                        Message::DELETEDSUCCESSFULLY();
                        die();
                    } else {
                        Message::ERROR();
                        die();

                    }
                }else{

                    Message::ACCESSRESTRICTED();
                    die();
                }  }else{


                Message::ACCESSRESTRICTED();
                die();
            }
        }

    }

    public function showRestaurantDeals()
    {

        $this->loadModel("Deal");

        $this->loadModel("Restaurant");
        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);

            if(count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $deals = $this->Deal->getRestaurantDeals($restaurant_id);


                $output['code'] = 200;

                $output['msg'] = $deals;
                echo json_encode($output);


                die();
            }else{

                Message::ACCESSRESTRICTED();
                die();

            }
        }
    }

    public function showRestaurantExtraMenuSections()
    {

        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $section_names = $this->RestaurantMenuExtraSection->getAllRestaurantSectionNames($restaurant_id);


            $output['code'] = 200;

            $output['msg'] = $section_names;
            echo json_encode($output);


            die();
        }
    }




    public function addRestaurantCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_code   = $data['coupon_code'];
            $limit_users   = $data['limit_users'];
            $discount      = $data['discount'];
            $expire_date   = $data['expire_date'];
            $type   = $data['type'];


            // CakeLog::write('debug', '$data: '.print_r($data, TRUE));



            $coupon['coupon_code']   = $coupon_code;
            $coupon['limit_users']   = $limit_users;
            $coupon['discount']      = $discount;
            $coupon['expire_date']   = $expire_date;
            $coupon['type']   = $type;
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];
                $coupon['restaurant_id']   = $restaurant_id;
                if ($this->RestaurantCoupon->isDuplicateRecord($restaurant_id, $coupon_code) == 0)
                    if ($this->RestaurantCoupon->save($coupon)) {
                        $id = $this->RestaurantCoupon->getInsertID();
                        $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupon($id);


                        $output['code'] = 200;

                        $output['msg'] = $coupon_detail;
                        echo json_encode($output);


                        die();
                    } else {

                        echo Message::DATASAVEERROR();
                        die();

                    }
            }else{

                Message::ACCESSRESTRICTED();
                die();

            }

        }
    }


    public function deleteRestaurantCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_id = $data['coupon_id'];
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];


                if ($this->RestaurantCoupon->deleteCoupon($restaurant_id, $coupon_id)) {

                    Message::DELETEDSUCCESSFULLY();
                    die();
                } else {

                    echo Message::DATASAVEERROR();
                    die();

                }
            } else {


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showRestaurantCoupons()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupons($restaurant_id);

                // CakeLog::write('debug', '$coupon_detail: '.print_r($coupon_detail, TRUE));

                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();
            }else{


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }
    public function showDeliveryAddresses()
    {

        $this->loadModel('Address');

        $this->loadModel('RestaurantLocation');
        $this->loadModel('Restaurant');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id   = $data['user_id'];
            $addresses = $this->Address->getUserDeliveryAddresses($user_id);

            if (isset($data['restaurant_id']) && isset($data['sub_total'])) {
                $restaurant_id = $data['restaurant_id'];
                $sub_total     = $data['sub_total'];

                // $total_amount = $sub_total;
                $delivery_fee = 0;
                $restaurant_will_pay = 0;


                $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant_id);

                $i = 0;
                foreach ($addresses as $address) {

                    $distance_difference_btw_user_and_restaurant = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $address['Address']['lat'], $address['Address']['long']);

                    //convert distance in Kms from miles
                    $distance =  $distance_difference_btw_user_and_restaurant['rows'][0]['elements'][0]['distance']['text'] * 1.6;



                    $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);

                    $min_order_price = $restaurant_detail[0]['Restaurant']['min_order_price'];
                    $delivery_free_range = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                    if ($sub_total >= $min_order_price && $distance > $delivery_free_range) { //case 1

                        $distance_difference = $distance - $delivery_free_range;
                        $delivery_fee        =          $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance_difference;
                        $restaurant_will_pay        =   $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $delivery_free_range;
                        // $total_amount = $delivery_fee + $sub_total;

                    } else if ($sub_total < $min_order_price && $distance > $delivery_free_range) {



                        $delivery_fee = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;


                    } else if ($sub_total > $min_order_price && $distance <= $delivery_free_range) {

                        // $total_amount = $sub_total;
                        $delivery_fee = "0";
                        $restaurant_will_pay  =  $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;

                    } else if ($sub_total < $min_order_price && $distance <= $delivery_free_range) {
                        // $distance_difference = 5 - $distance;
                        $delivery_fee = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;

                    }



                    $delivery_fee_add_zero_in_the_end = strlen(substr(strrchr($delivery_fee, "."), 1));
                    if($delivery_fee_add_zero_in_the_end == 1){


                        $delivery_fee = $delivery_fee."0";
                    }

                    $addresses[$i]['Address']['total_amount'] = (string) $sub_total;
                    $addresses[$i]['Address']['delivery_fee'] = (string) $delivery_fee;
                    $addresses[$i]['Address']['restaurant_will_pay'] = (string) $restaurant_will_pay;
                    $addresses[$i]['Address']['distance'] = (string) $distance;

                    $i++;
                }
            }
            $output['code'] = 200;
            $output['msg']  = $addresses;

            echo json_encode($output);
            die();


        }

    }



    public function subscribe()
    {

        $this->loadModel("Subscriber");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $subscribe['email'] = $data['email'];
            $subscribe['city'] = $data['city'];
            $subscribe['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if($this->Subscriber->isDuplicateRecord($subscribe) > 0){



                $output['msg'] = "you have already subscribed";
            }else{

                $this->Subscriber->save($subscribe);

                $id = $this->Subscriber->getLastInsertId();

                $result = $this->Subscriber->getLastInsertRow($id);
                $output['msg'] = $result;
            }


            $output['code'] = 200;


            echo json_encode($output);


            die();
        }
    }

    public function restaurantRequest()
    {

        $this->loadModel("RestaurantRequest");
        $this->loadModel("User");
        $this->loadModel("UserAdmin");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_request['restaurant_name'] = $data['restaurant_name'];
            $restaurant_request['contact_name'] = $data['contact_name'];
            $restaurant_request['phone'] = $data['phone'];
            $restaurant_request['email'] = $data['email'];
            $restaurant_request['address'] = $data['address'];
            $restaurant_request['description'] = $data['description'];

            $restaurant_request['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if($this->RestaurantRequest->isDuplicateRecord($restaurant_request) > 0){



                $output['msg'] = "you have already applied";
            }else{

                $this->RestaurantRequest->save($restaurant_request);




                //CustomEmail::sendEmailRestaurantRequest($email['User']['email'],$restaurant_request);
                $admin_details = $this->UserAdmin->getAll();
                foreach ($admin_details as $admin){

                    $email = $admin['UserAdmin']['email'];
                    $full_name   = $data['contact_name'];
                    $email_data['to'] = $email;
                    $email_data['name'] = $full_name;
                    $email_data['subject'] = "new restaurant request";
                    $email_data['message'] = "You have received a new restaurant request from ".$full_name. ". Please login through admin portal to view the request.";
                    // CustomEmail::sendMail($email_data);


                }



                $id = $this->RestaurantRequest->getLastInsertId();

                $result = $this->RestaurantRequest->getLastInsertRow($id);
                $output['msg'] = $result;
            }


            $output['code'] = 200;


            echo json_encode($output);


            die();
        }
    }

    public function riderRequest()
    {

        $this->loadModel("RiderRequest");
        $this->loadModel("UserAdmin");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_request['first_name'] = $data['first_name'];
            $rider_request['last_name'] = $data['last_name'];
            $rider_request['phone'] = $data['phone'];
            $rider_request['email'] = $data['email'];
            $rider_request['city'] = $data['city'];
            $rider_request['state'] = $data['state'];
            $rider_request['country'] = $data['country'];
            $rider_request['address'] = $data['address'];
            $rider_request['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if($this->RiderRequest->isDuplicateRecord($rider_request) > 0){



                $output['msg'] = "you have already applied";
            }else{

                $this->RiderRequest->save($rider_request);

                $id = $this->RiderRequest->getLastInsertId();

                $result = $this->RiderRequest->getLastInsertRow($id);
                $output['msg'] = $result;


                $admin_details = $this->UserAdmin->getAll();
                foreach ($admin_details as $admin){

                    $email = $admin['UserAdmin']['email'];
                    $full_name   = $data['first_name']. ' '.$data['last_name'];
                    $email_data['to'] = $email;
                    $email_data['name'] = $full_name;
                    $email_data['subject'] = "new rider request";
                    $email_data['message'] = "You have received a new rider request from ".$full_name. ". Please login through admin portal to view the request.";
                    // CustomEmail::sendMail($email_data);


                }





            }


            $output['code'] = 200;


            echo json_encode($output);


            die();
        }
    }
    function forgotPasswordold()
    {

        $this->loadModel('User');
        $this->loadModel('UserInfo');
        if ($this->request->isPost()) {


            $result = array();
            $json   = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $email     = $data['email'];
            // $user_info = $this->User->findByEmail($email);
            $user_info = $this->UserInfo->getUserDetailsFromEmail($email);

            $code     = Lib::randomNumber(4);

            if (!empty($user_info)) {


                $user_id = $user_info[0]['User']['id'];
                $email   = $user_info[0]['User']['email'];
                $first_name   = $user_info[0]['UserInfo']['first_name'];
                $last_name   = $user_info[0]['UserInfo']['last_name'];
                $full_name   = $first_name. ' '.$last_name;


                $response = CustomEmail::sendEmailResetPassword($email, $full_name,$code);




                if ($response) {

                    $this->User->id = $user_id;
                    $savedField     = $this->User->saveField('token', $code);
                    $result['code'] = 200;
                    $result['msg']  = "An email has been sent to " . $email . ". You should receive it shortly.";
                } else {

                    $result['code'] = 201;
                    $result['msg']  = "invalid email";


                }

            } else {

                $result['code'] = 201;
                $result['msg']  = "Email doesn't exist";
            }



            echo json_encode($result);
            die();
        }


    }

    function forgotPassword()
    {


        $this->loadModel('User');

        if ($this->request->isPost()) {


            $result = array();
            $json   = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $email     = $data['email'];



            $code     = Lib::randomNumber(4);
            $user_info = $this->User->getUserDetailsAgainstEmail($email);
            if(APP_STATUS == "demo"){

                $output['code'] = 201;
                $output['msg']  = "disabled because of demo";
                echo json_encode($output);
                die();

            }

            if (count($user_info) > 0) {



                $user_id = $user_info['User']['id'];
                $email   = $user_info['User']['email'];
                $first_name   = $user_info['UserInfo']['first_name'];
                $last_name   = $user_info['UserInfo']['last_name'];
                $full_name   = $first_name. ' '.$last_name;

                $email_data['to'] = $email;
                $email_data['name'] = $full_name;
                $email_data['subject'] = "reset your password";
                $email_data['message'] = "You recently requested to reset your password for your ".APP_NAME." account  with the e-mail address (".$email."). 
Please enter this verification code to reset your password.<br><br>Confirmation code: <b></b>".$code."<b>";
                $response = CustomEmail::sendMail($email_data);


                //  $response['ErrorCode']  = 0;
                if ($response['ErrorCode'] < 1) {

                    $this->User->id = $user_id;

                    $savedField     = $this->User->saveField('token', $code);
                    $result['code'] = 200;
                    $result['msg']  = "An email has been sent to " . $email . ". You should receive it shortly.";
                } else {

                    $result['code'] = 201;
                    $result['msg']  = "Email is not sending. Seems like you have not configured postmark correctly";


                }

            } else {

                $result['code'] = 201;
                $result['msg']  = "Email doesn't exist";
            }



            echo json_encode($result);
            die();
        }


    }

    function resetPassword()
    {

        $this->loadModel('User');

        if ($this->request->isPost()) {


            $result = array();
            $json   = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $email     = $data['email'];
            $role     = $data['role'];
            // $user_info = $this->User->findByEmail($email);


            $password     = Lib::getToken(6);
            $user_info = $this->User->findEmail($email,$role);
            if(APP_STATUS == "demo"){

                $output['code'] = 201;
                $output['msg']  = "disabled because of demo";
                echo json_encode($output);
                die();

            }

            if (count($user_info) > 0) {


                $user_id = $user_info[0]['User']['id'];
                $email   = $user_info[0]['User']['email'];
                $first_name   = $user_info[0]['UserInfo']['first_name'];
                $last_name   = $user_info[0]['UserInfo']['last_name'];
                $full_name   = $first_name. ' '.$last_name;

                $email_data['to'] = $email;
                $email_data['name'] = $full_name;
                $email_data['subject'] = "reset your password";
                $email_data['message'] = "You recently requested to reset your password for your ".APP_NAME." account. Your new password is ".$password;
                $response = CustomEmail::sendMail($email_data);


                //  $response['ErrorCode']  = 0;
                if ($response['ErrorCode'] < 1) {

                    $this->User->id = $user_id;

                    $this->User->saveField('password', $password);
                    $result['code'] = 200;
                    $result['msg']  = "New Password has been sent to your " . $email . ". You should receive it shortly.".$password;
                } else {

                    $result['code'] = 201;
                    $result['msg']  = "invalid email";


                }

            } else {

                $result['code'] = 201;
                $result['msg']  = "Email doesn't exist";
            }



            echo json_encode($result);
            die();
        }


    }

    public function verifyforgotPasswordCode()
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);
            $code = $data['code'];
            $email = $data['email'];

            $code_verify = $this->User->verifyToken($code,$email);
            $user_info = $this->UserInfo->getUserDetailsFromEmail($email);
            if (!empty($code_verify)) {
                $this->User->id = $user_info[0]['User']['id'];
                $this->User->saveField('token',$code);

                $user_info = $this->UserInfo->getUserDetailsFromEmail($email);
                $result['code'] = 200;
                $result['msg']  = $user_info;
                echo json_encode($result);
                die();
            } else {
                $result['code'] = 201;
                $result['msg']  = "invalid code";
                echo json_encode($result);
                die();
            }
        }
    }

    public function saveNewPassword()
    {
        $this->loadModel('User');
        if ($this->request->isPost()) {

            $password1                       = $this->request->data("pw1");
            $pw1                             = trim($password1);
            $password2                       = $this->request->data("pw2");
            $email                           = $this->request->data("email");
            $user_info                       = $this->User->findByEmail($email);
            $this->User->id                  = $user_info['User']['id'];
            $this->request->data['password'] = $pw1;
            $this->request->data['token']    = 0;
            if ($this->User->save($this->request->data)) {


                echo "success";
            }
        }
    }
    public function changePassword()
    {
        $this->loadModel('User');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            //$json = $this->request->data('json');
            $data = json_decode($json, TRUE);


            $user_id        = $data['user_id'];
            $this->User->id = $user_id;
            $email          = $this->User->field('email');

            $old_password   = $data['old_password'];
            $new_password   = $data['new_password'];


            if ($this->User->verifyPassword($email, $old_password)) {

                $this->request->data['password'] = $new_password;
                $this->User->id                  = $user_id;


                if ($this->User->save($this->request->data)) {

                    echo Message::DATASUCCESSFULLYSAVED();

                    die();
                } else {


                    echo Message::DATASAVEERROR();
                    die();


                }

            } else {

                echo Message::INCORRECTPASSWORD();
                die();

            }


        }

    }

    public function changePasswordForgot()
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            //$json = $this->request->data('json');
            $data = json_decode($json, TRUE);


            $email        = $data['email'];

            $new_password   = $data['password'];




            $this->request->data['password'] = $new_password;

            $email_details = $this->User->getUserDetailsAgainstEmail($email);


            $user_id = $email_details['User']['id'];
            $this->User->id = $user_id;
            if ($this->User->save($this->request->data)) {

                $user_info = $this->UserInfo->getUserDetailsFromID($user_id);
                $result['code'] = 200;
                $result['msg']  = $user_info;
                echo json_encode($result);
                die();
            } else {


                echo Message::DATASAVEERROR();
                die();


            }

        } else {

            echo Message::INCORRECTPASSWORD();
            die();




        }

    }


    public function showCountries()
    {

        $this->loadModel("Tax");
        $this->loadModel("Currency");


        if ($this->request->isPost()) {



            $currency = $this->Currency->getCurrencies();

            $taxes= $this->Tax->getTaxes();
            $states = $this->Tax->getStates();





            $output['code'] = 200;

            $output['taxes'] = $taxes;

            $output['currency'] = $currency;
            echo json_encode($output);


            die();
        }
    }

    /*-------------*/

    public function riderOrderUpdate(){


        $order_id = $this->request->query('order_id');
        $this->loadModel("RiderOrder");
        if($this->RiderOrder->isRiderOrderExistAgainstOrder($order_id) > 0){
            if($this->RiderOrder->saveField('order_id',$order_id)){

                echo "successfully added";
                die();

            }else{

                echo "something went wrong";

            }
        }else{

            echo "no order exist";
        }


    }


    public function showEarningsold()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $user_id        = $data['user_id'];
            $restaurant_details            = $this->Restaurant->getRestaurantID($user_id);

            if(count($restaurant_details) > 0) {


                $restaurant_id = $restaurant_details[0]['Restaurant']['id'];
                $currency = $restaurant_details[0]['Currency'];
                $created = $restaurant_details[0]['Restaurant']['created'];
                $formatted_registration_date = Lib::getFullMonthAndYear($created);




                $earnings = $this->Order->getTotalEarnings($restaurant_id);

                $total_stats['Currency'] = $currency;
                $total_stats['Restaurant']['created'] = "Since ". $formatted_registration_date;
                $total_stats['TotalEarning'] = $earnings[0][0];

                $add_tax_and_fee =  $earnings[0][0]['total_tax'] +  $earnings[0][0]['total_restaurant_delivery_fee'];
                $total_earning =  $earnings[0][0]['total_sub_total'] - $add_tax_and_fee;
                $total_stats['TotalEarning']['total_earning'] = (string)$total_earning;
                $total_stats['TotalEarning']['total_cash_on_delivery_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,1);
                $total_stats['TotalEarning']['total_online_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,0);

                /*weekly earnings*/

                $weekly_earnings = $this->Order->getWeeklyEarnings($restaurant_id);

                foreach ($weekly_earnings as $key => $val) {

                    $total_stats['WeeklyEarning'][$key] = $val[0];
                    $total_stats['WeeklyEarning'][$key]['week_start'] = Lib::shortMonthAndDay($val[0]['week_start']);
                    $total_stats['WeeklyEarning'][$key]['week_end'] =   Lib::shortMonthAndDay($val[0]['week_end']);
                    $add_tax_and_fee =  $val[0]['total_tax'] +  $val[0]['total_restaurant_delivery_fee'];

                    $weekly_total =  $val[0]['total_sub_total'] - $add_tax_and_fee;
                    $total_stats['WeeklyEarning'][$key]['total_earning'] = (string)$weekly_total;

                    //get orders of that week.



                    $starting_date = $val[0]['week_start'];
                    $ending_date = $val[0]['week_end'];

                    $total_stats['WeeklyEarning'][$key]['total_cash_on_delivery_orders'] = (string)$this->Order->getRestaurantCashOnDeliveryOrOnlineCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date,1);
                    $total_stats['WeeklyEarning'][$key]['total_online_orders'] = (string)$this->Order->getRestaurantCashOnDeliveryOrOnlineCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date,0);

                    $orders = $this->Order->getRestaurantCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date);
                    $total_stats['WeeklyEarning'][$key]['Orders'] = $orders;
                }

                /*------*/


                $output['code'] = 200;


                $output['msg'] = $total_stats;
                echo json_encode($output);
                die();
            }else{



                echo Message::ERROR();
                die();
            }




        }
    }


    public function showEarnings()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("Order");
        $this->loadModel("Transaction");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $user_id        = $data['user_id'];
            $restaurant_details            = $this->Restaurant->getRestaurantID($user_id);

            if(count($restaurant_details) > 0) {


                $restaurant_id = $restaurant_details[0]['Restaurant']['id'];




                $earnings = $this->Order->getPaidEarningStatements($restaurant_id);
                $earnings_restaurant = $this->Order->getRestaurantTotalEarnings($restaurant_id);
                $restaurant_transaction = $this->Transaction->getRestaurantTransaction($restaurant_id);
                // pr($earnings);
                /* $total_stats['Currency'] = $currency;
                 $total_stats['Restaurant']['created'] = "Since ". $formatted_registration_date;
                 $total_stats['TotalEarning'] = $earnings[0][0];

                 $add_tax_and_fee =  $earnings[0][0]['total_tax'] +  $earnings[0][0]['total_restaurant_delivery_fee'];
                 $total_earning =  $earnings[0][0]['total_sub_total'] - $add_tax_and_fee;
                 $total_stats['TotalEarning']['total_earning'] = (string)$total_earning;
                 $total_stats['TotalEarning']['total_cash_on_delivery_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,1);
                 $total_stats['TotalEarning']['total_online_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,0);
 */



                $output['code'] = 200;

                $admin_commission = $earnings_restaurant[0]['Restaurant']['admin_commission'];
                $output['msg']['Currency'] = $earnings_restaurant[0]['Restaurant']['Currency'];
                $output['msg']['TotalEarnings'] = $earnings_restaurant[0][0];
                $output['msg']['TotalEarnings']['you_earned'] = $earnings_restaurant[0][0]['total_price']/100 * $admin_commission ;
                $output['msg']['Transactions'] = $restaurant_transaction;
                echo json_encode($output);
                die();
            }else{



                echo Message::ERROR();
                die();
            }




        }
    }

    public function showRestaurantsSpecialities()
    {

      $this->loadModel('Category');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $image_baseurl = $data['image_baseurl'];

          // $specialities = $this->Restaurant->getRestaurantSpecialities();
            //$specialities = $this->Category->getAllCategory();  OLD CATEGORY LIST--RASHED
            
            //$html = $this->get_menu_html(0,$image_baseurl);
            
            $specialities = $this->Category->showRestaurantsSpecialities();
            $html = '<ul class="menuitems" id="menuListLeft" style="border: 1px solid rgba(0,0,0,0); margin: 0!important;">';
            $html .= '<li id="All" style="font-weight: 50; font-size: 13px; padding: 15px 0px; padding-left: 15px; color: grey;"><a onclick="highlightSpecialty(\'All\');">All Cuisines</a></li>';
            foreach($specialities as $key1 => $value1)
            {   
                
                if($this->checkImageExist($image_baseurl.strtolower($value1['Category']['icon'])) == "200" && $value1['Category']['icon']!="")
                {
                    $catImage   = $image_baseurl.strtolower($value1['Category']['icon']);
                }else
                {
                    $catImage="assets/img/noImage.png";
                }
                
              
                $category = $value1['Category']['category'];
                 $html .= '<li id="'.$value1['Category']['category'].'" style="font-weight: 50; font-size: 13px; padding: 0px; padding-left: 15px; color: grey;">';
                
                 $html .= '<img src="'.$catImage.'" width="30" height="30" align="absmiddle"/>&nbsp;&nbsp;';
				 $html .= '<a onclick="highlightSpecialty(';
				 $html .= "'".$category."'";
				 $html .= ')">';
				$html .= $value1['Category']['category'];
				 $html .= '</a>
				 				</li>';
			}
			$html .='<li style="  padding: 0px; padding-left: 15px; padding-bottom: 0; font-size: 20px;"> </li>';
			$html .= '</ul>';


            $output['code'] = 200;
            $output['msg']  = $html;
            echo json_encode($output);
            die();


        }

    }
    
    public function sendContactUsEmail(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, TRUE);
        
        // CakeLog::write('debug', '$data: '.print_r(json_decode($data), TRUE));
        
        $email = $data['email'];
        $name = $data['name'];
        $phone = $data['phone'];
        $message = $data['message'];
        
        $email_data['to'] = $email;
        $email_data['subject'] = "Contact us message from: ".$name;
        $email_data['message'] = $message."\n\n"."Phone Nª: ".$phone;
        
        $response = CustomEmail::sendMail($email_data);
        
        echo "success";

    }

    public function getRestaurantDetail()
    {
        $this->loadModel('Restaurant');

        $json = file_get_contents('php://input');
        $data = json_decode($json, TRUE);

        $user_id = $data['user_id'];
        $id        = $this->Restaurant->getRestaurantID($user_id);

        if (count($id) > 0) {
            $restaurant_id = $id[0]['Restaurant']['id'];

            $rest_details   = $this->Restaurant->getRestaurantDetail($restaurant_id);

            $output['code'] = 200;
            $output['msg']  = $rest_details;
            echo json_encode($output);
            die();

        }else{

            Message::ACCESSRESTRICTED();
            die();
        }
       
    }
    
    public function orderCancel()
    {

        $this->loadModel("Order");
        

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            

          //  $user_id = $data['user_id'];
            $order_id       = $data['order_id'];
            $description    = $data['description'];
            $user_id        = $data['user_id'];
            
           
            $orders         = $this->Order->findById($order_id);

            if(count($orders) > 0) {
                
                if($orders['Order']['status'] !=1)
                {
                    $output['code'] = 201;

                    $output['msg'] = "Order Can not be Cancel at this moment.";

                    echo json_encode($output);
                    die();
                }else{
                    $order_update = array();
                    $order_update['id']         = $order_id;
                    $order_update['status']     = 4;
                    $order_update['rejected_reason']     = $description;
                    $order_update['rejected_by']     = $user_id;
                    $order_update['rejected_date']     = date('Y-m-d H:i:s');
                    
                    if ($this->Order->save($order_update)) {
                        $output['code'] = 200;
    
                        $output['msg'] = "Order Cancel Successfully.";
    
                        echo json_encode($output);
                        die();
                    }else {
                        $output['code'] = 201;
    
                        $output['msg'] = "Order Can not be Cancel at this moment.";
    
                        echo json_encode($output);
                        die();
                    }
                }
                
            }else {
                $output['code'] = 201;

                $output['msg'] = "Order Can not be Cancel at this moment.";

                echo json_encode($output);
                die();
            }

           // die();
        }
    }
    
    public function webSliderImages()
    {
        $this->loadModel("WebSlider");

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $images = $this->WebSlider->getImages();

            $output['code'] = 200;

            $output['msg'] = $images;
            echo json_encode($output);
            die();
        }
    }
    
    // public function getRestaurantBookingDay()
    // {
    //     $this->loadModel('DeliveryBookTime');
    //     $this->loadModel('BookingTime');

    //     if ($this->request->isPost()) {

    //         $json = file_get_contents('php://input');
    //         $data = json_decode($json, TRUE);
    //       // $arrayOfIds = [1,3,5,6];
    //         $restaurant_id = $data['restaurant_id'];
    //         $array = $this->DeliveryBookTime->getBookedData($restaurant_id);
    //         $allData = [];
    //         $p = [];
    //         if(!empty($array))
    //         {

    //             foreach($array as $a)
    //             {
    //                 $p['booking_day']     = $a['DeliveryBookTime']['booking_day'];
    //                 $p['booking_time_id'] = $a['DeliveryBookTime']['booking_time_id'];
                    
    //                 $p['time'] = $this->BookingTime->getTimeData(explode(',',$p['booking_time_id']));
    //                 array_push($allData, $p);
    //             }
    //         }
			
    //         $output = array();
    //         $output['code'] = 200;
    //         $output['msg'] = $allData;
    //       // $output['msg'] = $array;
    //         echo json_encode($output);
            
    //     }
    // }
    
    public function getRestaurantBookingDay()
    {
        $this->loadModel('DeliveryBookTime');
        $this->loadModel('BookingTime');
        $this->loadModel('Order');

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
           // $arrayOfIds = [1,3,5,6];
            $restaurant_id = $data['restaurant_id'];
            $array = $this->DeliveryBookTime->getBookedData($restaurant_id);

            $db_day = [];
            foreach($array as $ab)
            {
                $db_day[] = $ab['DeliveryBookTime']['booking_day'];
            }

            $weekOfdays = array();
            $day = date('l');
			$date = date('Y-m-d');
          			
            $weekOfdays[$date] = strtolower($day);
			$day = strtotime($day);
			$weekOfdays[date('Y-m-d',strtotime("+1 day",$day))] = strtolower(date('l',strtotime("+1 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+2 day",$day))] = strtolower(date('l',strtotime("+2 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+3 day",$day))] = strtolower(date('l',strtotime("+3 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+4 day",$day))] = strtolower(date('l',strtotime("+4 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+5 day",$day))] = strtolower(date('l',strtotime("+5 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+6 day",$day))] = strtolower(date('l',strtotime("+6 day",$day)));
			
			
			//print_r($weekOfdays);
			//$daysA = array('saturday', 'sunday');

			$result = array_intersect($weekOfdays, $db_day);
          //   print_r($result);

            $allData = [];
            $p = [];
            if(!empty($result))
            {

                foreach($result as $key => $a)
                {
                    $deliveryDay = $this->DeliveryBookTime->getEachBookedData($restaurant_id, $a);
                    $p['booking_day']     = $a;
                    $p['booking_date']     = $key;
                    $p['booking_time_id'] = $deliveryDay[0]['DeliveryBookTime']['booking_time_id'];
                    //$p['time'] = $this->BookingTime->getTimeData($arrayOfIds);
                    
                    $times = $this->BookingTime->getTimeData(explode(',',$deliveryDay[0]['DeliveryBookTime']['booking_time_id']));
                   
                    if(!empty($times))
                    {
                        $i = 0;
                        foreach($times as $time)
                        {
                            $orderedData = $this->Order->getOrderedData($restaurant_id, $key, $a, $time['BookingTime']['booking_time']);
                            if(!empty($orderedData))
                            {
                                
                            }else{
                                $p['time'][$i]['BookingTime']['id'] = $time['BookingTime']['id'];
                                $p['time'][$i]['BookingTime']['booking_time'] = $time['BookingTime']['booking_time'];
                                $i++;
                            }
                            
                        }
                      // $times = '';
                    }else{
                        $p['time'] = array();
                    }
                 //   $p['time'] = $times;
              
                    array_push($allData, $p);
                }
            }
			
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $allData;
           // $output['msg'] = $array;
            echo json_encode($output);
            
        }
    }
    
    public function getRestaurantBookingDays()
    {
         $this->loadModel('DeliveryBookTime');
        $this->loadModel('BookingTime');
        $this->loadModel('Order');

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
           // $arrayOfIds = [1,3,5,6];
            $restaurant_id = $data['restaurant_id'];
            $array = $this->DeliveryBookTime->getBookedData($restaurant_id);

            $db_day = [];
            foreach($array as $ab)
            {
                $db_day[] = $ab['DeliveryBookTime']['booking_day'];
            }

            $weekOfdays = array();
            $day = date('l');
			$date = date('Y-m-d');
          			
            $weekOfdays[$date] = strtolower($day);
			$day = strtotime($day);
			$weekOfdays[date('Y-m-d',strtotime("+1 day",$day))] = strtolower(date('l',strtotime("+1 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+2 day",$day))] = strtolower(date('l',strtotime("+2 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+3 day",$day))] = strtolower(date('l',strtotime("+3 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+4 day",$day))] = strtolower(date('l',strtotime("+4 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+5 day",$day))] = strtolower(date('l',strtotime("+5 day",$day)));
			$weekOfdays[date('Y-m-d',strtotime("+6 day",$day))] = strtolower(date('l',strtotime("+6 day",$day)));
			
			
			//print_r($weekOfdays);
			//$daysA = array('saturday', 'sunday');

			$result = array_intersect($weekOfdays, $db_day);
          //   print_r($result);

            $allData = [];
            $p = [];
            if(!empty($result))
            {

                foreach($result as $key => $a)
                {
                    $deliveryDay = $this->DeliveryBookTime->getEachBookedData($restaurant_id, $a);
                     $p['booking_day']     = $deliveryDay[0]['DeliveryBookTime']['booking_day'];
                     $p['booking_date']     = $key;
                     $p['booking_time_id'] = $deliveryDay[0]['DeliveryBookTime']['booking_time_id'];
                            //$p['time'] = $this->BookingTime->getTimeData($arrayOfIds);
                    $orderedData = $this->Order->getOrderedData($restaurant_id, $key, $a);
                    
                    $orderBooking = [];
                    if(!empty($orderedData))
                    {
                        foreach($orderedData as $order)
                        {
                            $orderBooking[]  = $order['Order']['booking_time_id'];
                        }
                    }
                    
                    $p['orderedData']     = array_diff(explode(',',$deliveryDay[0]['DeliveryBookTime']['booking_time_id']), $orderBooking);
                    
                    $times = $this->BookingTime->getTimeData($p['orderedData']);
                  
                    $p['time'] = $times;
              
                    array_push($allData, $p);
                }
            }
			
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $allData;
           // $output['msg'] = $array;
            echo json_encode($output);
            
        }
    }
    
    // public function getRestaurantBookingDays()
    // {
    //     $this->loadModel('DeliveryBookTime');
    //     $this->loadModel('BookingTime');
    //     $this->loadModel('Order');

    //     if ($this->request->isPost()) {

    //         $json = file_get_contents('php://input');
    //         $data = json_decode($json, TRUE);
    //       // $arrayOfIds = [1,3,5,6];
    //         $restaurant_id = $data['restaurant_id'];
    //         $array = $this->DeliveryBookTime->getBookedData($restaurant_id);

    //         // $db_day = [];
    //         // foreach($array as $ab)
    //         // {
    //         //     $db_day[] = $ab['DeliveryBookTime']['booking_day'];
    //         // }


    //         $allData = [];
    //         $p = [];
    //         if(!empty($array))
    //         {

    //             foreach($array as $a)
    //             {
    //                 $p['id']     = $a['DeliveryBookTime']['id'];
    //                 $p['booking_day']     = $a['DeliveryBookTime']['booking_day'];
    //                 $p['booking_date']     = '';
    //                 $p['booking_time_id'] = $a['DeliveryBookTime']['booking_time_id'];
    //                 $p['time'] = $this->BookingTime->getTimeData(explode(',',$a['DeliveryBookTime']['booking_time_id']));
              
    //                 array_push($allData, $p);
    //             }
    //         }
			
    //         $output = array();
    //         $output['code'] = 200;
    //         $output['msg'] = $allData;
    //       // $output['msg'] = $array;
    //         echo json_encode($output);
            
    //     }
    // }
    
    public function showRestaurantsSpecialitiesNew()
    {
        $this->loadModel('Category');
   
          $specialities = $this->Category->get();

            $output['code'] = 200;
            $output['msg']  = $specialities;
            echo json_encode($output);
            die();


        
        // if ($this->request->isPost()) {
        //     $json = file_get_contents('php://input');
        //     $data = json_decode($json, TRUE);
            
        //      //   $this->loadModel('Category');



        //     $categories = $this->Category->getAll();
            
        //     foreach ($categories as $category) {

        //         $widthInPx = ($level + 1) * 30;
            
        //         echo '<div style="width:' . $widthInPx . '"></div>';
            
        //         if (!empty($info['childs'])) {
        //             display_comments($info['childs'], $level + 1);
        //         }
        //     }
            
        // }
        
    }
    
   
    
    public function checkImageExist($external_link)
    {
        if (@getimagesize($external_link)) 
        {
            return 200;
        } 
        else 
        {
            return 201;
        }
    }
    
    public function get_menu_html($root_id = 0,$image_baseurl)
    {
        $this->html  = array();
        $this->items = $this->get_menu_items();

        foreach ( $this->items as $item )
            $children[$item['Category']['level']][] = $item;

        // loop will be false if the root has no children (i.e., an empty menu!)
        $loop = !empty( $children[$root_id] );

        // initializing $parent as the root
        $parent = $root_id;
        $parent_stack = array();
        //if($this->checkImageExist($image_baseurl.strtolower($value1['Category']['icon'])) == "200" && $value1['Category']['icon']!="")
//                 {
//                     $catImage   = $image_baseurl.strtolower($value1['Category']['icon']);
//                 }else
//                 {
//                     $catImage=$image_baseurl."assets/img/noImage.png";
//                 }
            $catImage="assets/img/noImage.png";
        // HTML wrapper for the menu (open)
        $this->html[] = '<ul class="menuitems" id="menuListLeft" style="border: 1px solid rgba(0,0,0,0); margin: 0!important;">';
        $this->html[] = '<li style="padding: 5px; padding-left: 7px; padding-top: 15px; font-size: 16px;">Cuisines</li>';
        $this->html[] = '<li id="All" style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 7px; color: grey;">
                            <img src="'.$catImage.'" width="30" height="30" align="absmiddle"/>&nbsp;&nbsp;
                          
                            <a onclick="highlightSpecialty(\'All\');">All Cuisines</a></li>';
        
        //if($loop &&  $parent > $root_id )
        //{
            while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
            //foreach($children[$parent] as $option)
            {
                if ( $option === false )
                {
                    $parent = array_pop( $parent_stack );
    
                    // HTML for menu item containing childrens (close)
                    $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
                    $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
                }elseif ( !empty( $children[$option['value']['Category']['id']] ) )
                {
                    if($this->checkImageExist($image_baseurl.strtolower($option['value']['Category']['icon'])) == "200" && $option['value']['Category']['icon']!="")
                    {
                        $catImage   = $image_baseurl.strtolower($option['value']['Category']['icon']);
                    }else
                    {
                        $catImage="assets/img/noImage.png";
                    }
                     // $option['value']['Category']['link'], 
                    $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );
    
                    // HTML for menu item containing childrens (open)
                    $this->html[] = sprintf(
                        '%1$s<li style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 7px; color: grey;">
                         <img src="'.$catImage.'" width="30" height="30" align="absmiddle"/>&nbsp;&nbsp;<a onclick="%2$s">%3$s</a>',
                        $tab,   // %1$s = tabulation
                      "highlightSpecialty('".$option['value']['Category']['category']."')",// %2$s = link (URL)
                        $option['value']['Category']['category']   // %3$s = title
                    ); 
                    $this->html[] = $tab . "\t" . '<ul class="submenu">';
    
                    array_push( $parent_stack, $option['value']['Category']['level'] );
                    $parent = $option['value']['Category']['id'];
                }else{
                    if($this->checkImageExist($image_baseurl.strtolower($option['value']['Category']['icon'])) == "200" && $option['value']['Category']['icon']!="")
                    {
                        $catImage   = $image_baseurl.strtolower($option['value']['Category']['icon']);
                    }else
                    {
                        $catImage="assets/img/noImage.png";
                    }
                // HTML for menu item with no children (aka "leaf") 
                    $this->html[] = sprintf(
                        '%1$s<li style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 7px; color: grey;">
                        <img src="'.$catImage.'" width="30" height="30" align="absmiddle"/>&nbsp;&nbsp;<a onclick="%2$s">%3$s</a></li>',
                        str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ),   // %1$s = tabulation
                        "highlightSpecialty('".$option['value']['Category']['category']."')",   // %2$s = link (URL)
                        $option['value']['Category']['category']   // %3$s = title
                    );
                }
            }
       // }
        // while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
        // {
        //     if ( $option === false )
        //     {
        //         $parent = array_pop( $parent_stack );

        //         // HTML for menu item containing childrens (close)
        //         $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
        //         $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
        //     }
        //     elseif ( !empty( $children[$option['value']['id']] ) )
        //     {
        //          // $option['value']['link'], 
        //         $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

        //         // HTML for menu item containing childrens (open)
        //         $this->html[] = sprintf(
        //             '%1$s<li style="font-weight: 50; font-size: 13px; padding: 5px; padding-left: 7px; color: grey;"><a onclick="%2$s">%3$s</a>',
        //             $tab,   // %1$s = tabulation
        //           "highlightSpecialty('".$option['value']['category']."')",// %2$s = link (URL)
        //             $option['value']['category']   // %3$s = title
        //         ); 
        //         $this->html[] = $tab . "\t" . '<ul class="submenu">';

        //         array_push( $parent_stack, $option['value']['level'] );
        //         $parent = $option['value']['id'];
        //     }
        //     else
        //             //$option['value']['link'],
        //         // HTML for menu item with no children (aka "leaf") 
        //         $this->html[] = sprintf(
        //             '%1$s<li><a onclick="%2$s">%3$s</a></li>',
        //             str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ),   // %1$s = tabulation
        //             "highlightSpecialty('".$option['value']['category']."')",   // %2$s = link (URL)
        //             $option['value']['category']   // %3$s = title
        //         );
        // }
        $this->html[] = '<li style="  padding: 5px; padding-left: 15px; padding-bottom: 0; font-size: 20px;"> </li>';
        // HTML wrapper for the menu (close)
        $this->html[] = '</ul>';

        return implode( "\r\n", $this->html );
    }
    
    function get_menu_items()
    {
        // Change the field names and the table name in the query below to match tour needs
        $sql = $this->Category->getCategoryAll();
       // $sql = 'SELECT id, parent_id, title, link FROM menu_item ORDER BY title;';
        return $this->fetch_assoc_all( $sql );
    }
    
    function fetch_assoc_all( $result )
    {
       // $result = mysqli_query(  $this->conn, $sql );

        if ( !$result )
            return false;

        $assoc_all = array();

        foreach( $result as $fetch ){
            $assoc_all[] = $fetch;
        }
            
        //mysqli_free_result( $result );
        return $assoc_all;
    }


}


?>