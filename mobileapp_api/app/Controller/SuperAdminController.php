<?php
App::uses('Lib', 'Utility');
App::uses('Firebase', 'Lib');
App::uses('Postmark', 'Utility');
App::uses('Message', 'Utility');

App::uses('CustomEmail', 'Utility');
App::uses('Security', 'Utility');
App::uses('PushNotification', 'Utility');



class SuperAdminController extends AppController
{


    public $autoRender = false;
    public $layout = false;
    //101 - something already in the db
    //100 - success
    //102 - invalid



    // public function beforeFilter()
    // {

    //     $json = file_get_contents('php://input');
    //     $json_error = Lib::isJsonError($json);

    //     if( !function_exists('apache_request_headers') ) {
    //         $headers =  Lib::apache_request_headers();
    //     }else {
    //         $headers = apache_request_headers();
    //     }


    //     if ($json_error == "false") {
    //           if(APP_STATUS == "demo") {

    //               $client_api_key = 0;
    //             if (array_key_exists("Api-Key", $headers) ) {
    //                 $client_api_key = $headers['Api-Key'];

    //             }else if (array_key_exists("API-KEY", $headers)){

    //                 $client_api_key = $headers['API-KEY'];
    //             }


    //             if($client_api_key > 0) {


    //                 if ($client_api_key != API_KEY) {

    //                     Message::ACCESSRESTRICTED();
    //                     die();

    //                 }
    //             }else {
    //                 $output['code'] = 201;
    //                 $output['msg'] = "API KEY is missing";

    //                 echo json_encode($output);
    //                 die();

    //             }

    //         }
    //         return true;


    //     } else {

    //         $output['code'] = 202;
    //         $output['msg'] = $json_error;

    //         echo json_encode($output);
    //         die();


    //     }

    // }



    public function index(){

        $output['code'] = "200";
        $output['msg'] = "Congratulations!. You have configured your admin api correctly";

        echo json_encode($output);
        die();




    }
    public function registerRider()
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');
        $this->loadModel('RiderLocation');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];

            $phone = $data['phone'];
            $note = @$data['note'];
            $device_token = $data['device_token'];
            $role = $data['role'];
            $city = $data['city'];
            $country = $data['country'];
            $address_to_start_shift = $data['address_to_start_shift'];




            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['active'] = 1;
                $user['role'] = $role;
                $user['created'] = date('Y-m-d H:i:s', time());


                $count = $this->User->isEmailAlreadyExist($email);


                if ($count && $count > 0) {
                    echo Message::DATAALREADYEXIST();
                    die();

                } else {

                    $lib = new Lib;
                    $key = Security::hash(CakeText::uuid(), 'sha512', true);


                    if (!$this->User->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $user_id = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;

                    $user_info['device_token'] = $device_token;
                    $user_info['first_name'] = $first_name;
                    $user_info['note'] = $note;
                    $user_info['last_name'] = $last_name;
                    $user_info['phone'] = $phone;
                    if(isset($data['rider_fee'])){

                        $user_info['rider_fee'] = $data['rider_fee'];
                    }
                    $rider_location['city'] = $city;
                    $rider_location['country'] = $country;
                    $rider_location['address_to_start_shift'] = $address_to_start_shift;
                    $rider_location['user_id'] = $user_id;


                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }

                    if (!$this->RiderLocation->save($rider_location)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $output = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function blockRestaurant()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $block = $data['block'];
            $this->Restaurant->id = $id;


            if ($this->Restaurant->saveField('block',$block)) {

                $restaurant_details = $this->Restaurant->getRestaurantDetail($id);

                $output['code'] = 200;
                $output['msg'] = $restaurant_details;

                echo json_encode($output);
                die();
            }else{

                Message::ERROR();
                die();
            }
        }
    }


    public function login() //changes done by irfan
    {
        $this->loadModel('UserAdmin');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            // $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = strtolower($data['email']);
            $password = $data['password'];
            //  $device_token = $data['device_token'];
            // $userData['msg'] = ;

            if ($email != null && $password != null) {
                $userData = $this->UserAdmin->loginAllUsers($email, $password);

                if ($userData) {
                    $user_id = $userData[0]['UserAdmin']['id'];

                    // $this->UserInfo->id = $user_id;
                    // $savedField = $this->UserInfo->saveField('device_token', $device_token);

                    $output = array();
                    $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


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



    public function addUser() //admin can add multiple users and assign roles
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $phone = $data['phone'];

            $role = $data['role'];


            //file_put_contents(Variables::$UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['active'] = 1;
                $user['role'] = $role;
                $user['created'] = date('Y-m-d H:i:s', time());


                $count = $this->User->isEmailAlreadyExist($email);


                if ($count && $count > 0) {
                    echo Message::DATAALREADYEXIST();
                    die();

                } else {

                    $lib = new Lib;
                    $key = Security::hash(CakeText::uuid(), 'sha512', true);


                    if (!$this->User->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $user_id = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;


                    //$user_info['first_name'] = $first_name;
                    //$user_info['last_name'] = $last_name;
                    $user_info['full_name'] = $first_name." ".$last_name;
                    $user_info['phone'] = $phone;


                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $output = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function addAdminUser() //admin can add multiple users and assign roles
    {


        $this->loadModel('UserAdmin');


        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];


            $role = $data['role'];

            $role_name = $data['role_name'];
            $phone = $data['phone'];


            //file_put_contents(Variables::$UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['first_name'] = $first_name;
                $user['last_name'] = $last_name;
                $user['phone'] = $phone;
                $user['active'] = 1;
                $user['role'] = $role;
                $user['role_name'] = $role_name;
                $user['created'] = date('Y-m-d H:i:s', time());


                $count = $this->UserAdmin->isEmailAlreadyExist($email);


                if ($count && $count > 0) {
                    echo Message::DATAALREADYEXIST();
                    die();

                } else {



                    if (!$this->UserAdmin->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }




                    $id = $this->UserAdmin->getLastInsertId();
                    $output = array();
                    $userDetails = $this->UserAdmin->getUserDetailsFromID($id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function showAdminUsers()
    {

        $this->loadModel("UserAdmin");


        if ($this->request->isPost()) {


            $users = $this->UserAdmin->getAllUsers();


            $output['code'] = 200;

            $output['msg'] = $users;
            echo json_encode($output);


            die();
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

                // $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($deals);
                $output['msg'] = $deals;
                echo json_encode($output);


                die();
            }else{

                Message::ACCESSRESTRICTED();
                die();

            }
        }
    }
    public function showRestaurantOrders()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {


            $restaurant_orders = $this->Restaurant->getRestaurantOrders();


            $output['code'] = 200;

            $output['msg'] = $restaurant_orders;
            echo json_encode($output);


            die();
        }
    }

    public function updateSingleRestaurant()
    {

        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json     = file_get_contents('php://input');
            $data     = json_decode($json, TRUE);



            $restaurant_id = $data['restaurant_id'];
            $status = $data['single_restaurant'];

            $details = $this->Restaurant->getSingleRestaurantDetail();

            if(count($details) > 0){

                $id = $details['Restaurant']['id'];
                $this->Restaurant->id = $id;
                $this->Restaurant->saveField('single_restaurant',0);

            }


            $this->Restaurant->id = $restaurant_id;
            $this->Restaurant->saveField('single_restaurant',$status);
            $details = $this->Restaurant->getSingleRestaurantDetail();

            if(count($details) > 0) {

                $output['code'] = 200;
                $output['msg'] = $details;
                echo json_encode($output);
                die();

            }else{

                Message::EmptyDATA();
                die();

            }


        }
    }


    public function editUserProfile()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("User");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $email = $data['email'];
            $phone = $data['phone'];
            if(isset($data['rider_fee'])){

                $user_info['rider_fee'] = $data['rider_fee'];
            }


            $user_info['first_name'] = $first_name;
            $user_info['last_name'] = $last_name;
            $user_info['phone'] = $phone;
            $user['email'] = $email;


            $this->UserInfo->id = $user_id;
            $this->User->id = $user_id;
            if ($this->UserInfo->save($user_info) && $this->User->save($user)) {
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

    public function editUserPassword()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("User");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $password = $data['password'];

            $info['password'] = $password;



            $this->User->id = $user_id;
            if ($this->User->save($info)){
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

    public function editAdminUserPassword()
    {


        $this->loadModel("UserAdmin");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $password = $data['password'];

            $info['password'] = $password;



            $this->UserAdmin->id = $user_id;
            if ($this->UserAdmin->save($info)){
                $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);


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
    public function EnableOrDisableAdminUser()
    {


        $this->loadModel("UserAdmin");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $active = $data['active'];
            $user_id = $data['user_id'];


            $this->UserAdmin->id = $user_id;

            if ($this->UserAdmin->saveField("active",$active)) {
                $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);


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


    public function showAllUsers()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $users = $this->User->getAllUsers();


            $output['code'] = 200;

            $output['msg'] = $users;
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


    public function deleteAdminAccount()
    {

        $this->loadModel("UserAdmin");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $details = $this->UserAdmin->getUserDetailsFromID($user_id);

            if (count($details) > 0) {
                $count_users = $this->UserAdmin->countAdminUsers();
                if ($count_users > 1) {
                    $this->UserAdmin->id = $user_id;
                    $this->UserAdmin->delete();


                    $output['code'] = 200;

                    $output['msg'] = "deleted successfully";
                    echo json_encode($output);

                    die();
                } else {

                    $output['code'] = 201;

                    $output['msg'] = "last admin user cannot be deleted";
                    echo json_encode($output);
                    die();
                }
            } else {

                $output['code'] = 201;

                $output['msg'] = "No User  found";
                echo json_encode($output);
                die();

            }


        }
    }


    public function deleteVerificationDocument()
    {

        $this->loadModel("VerificationDocument");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->VerificationDocument->getDetails($id);

            if (count($details) > 0) {
               $file =  $details['VerificationDocument']['file'];
                @unlink($file);


                    $this->VerificationDocument->id = $id;
                    $this->VerificationDocument->delete();


                    $output['code'] = 200;

                    $output['msg'] = "deleted successfully";
                    echo json_encode($output);

                    die();

            } else {

                $output['code'] = 201;

                $output['msg'] = "No document  found";
                echo json_encode($output);
                die();

            }


        }
    }


    public function editUserInfo()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("User");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $column = $data['column'];
            $value = $data['value'];


            $user_details = $this->UserInfo->getUserDetailsFromID($id);

            if(count($user_details) > 0 ){
                $this->UserInfo->schema();
                if(!empty($this->UserInfo->schema($column))) {

                    $this->UserInfo->id = $id;
                    $this->UserInfo->saveField($column,$value);

                    $user_details = $this->UserInfo->getUserDetailsFromID($id);


                    $output['code'] = 200;
                    $output['msg'] = $user_details;
                    echo json_encode($output);
                    die();
                }else  if(!empty($this->User->schema($column))) {

                    $this->User->id = $id;
                    $this->User->saveField($column,$value);

                    $user_details = $this->UserInfo->getUserDetailsFromID($id);


                    $output['code'] = 200;
                    $output['msg'] = $user_details;
                    echo json_encode($output);
                    die();

                }else{

                    $output['code'] = 201;
                    $output['msg'] = "column do not exist";
                    echo json_encode($output);
                    die();


                }



            }else{

                Message::EmptyDATA();
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

    public function showUsersBasedOnSearchKeyword()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $keyword = $data['keyword'];
            $users = $this->UserInfo->searchUser($keyword);


            $output['code'] = 200;

            $output['msg'] = $users;
            echo json_encode($output);


            die();
        }
    }



    public function showPlatformTotalEarnings()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("Currency");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $currency = $this->Currency->getAllCurrency();
            if(isset($data['start_date']) && isset($data['end_date'])){

                $start_date = $data['start_date'];
                $end_date = $data['end_date'];

                $platform_earnings = $this->Order->getPlatformTotalEarningsAgainstStartAndEndDate($start_date,$end_date);

                $restaurant_earnings = $this->Order->getRestaurantWiseTotalEarningsAgainstStartAndEndDate($start_date,$end_date);
                foreach ($restaurant_earnings as $res_key=>$res_val){
                    $restaurant_id = $res_val['Restaurant']['id'];
                    $restaurant_unpaid = $this->Order->getRestaurantUnpaid($restaurant_id);
                    $restaurant_earnings[$res_key][0]['unpaid'] = $restaurant_unpaid[0]['unpaid_total'];


                }

                $rider_earnings = $this->RiderOrder->getRiderEarnings();

                $rider_total = 0;
                if(count($rider_earnings) > 0) {

                    foreach ($rider_earnings as $key=>$val) {


                        $total_orders = $val[0]['total_orders'];
                        $rider_fee = $val['Rider']['rider_fee'];
                        $single_rider_earning = $rider_fee * $total_orders;
                        $rider_total = $rider_total + $single_rider_earning;
                        $rider_earnings[$key]['Rider']['earning'] = $single_rider_earning;





                        $rider_orders = $this->RiderOrder->getRiderOrders($val['Rider']['user_id']);
                        $order_ids = array();
                        foreach($rider_orders as $key_rider_order=>$rider_order_val){
                            $order_ids[$key_rider_order] = $rider_order_val['RiderOrder']['order_id'];


                        }

                        $rider_total_tip =  $this->Order->getTotalRiderTip($order_ids);

                        $rider_earnings[$key]['Rider']['total_rider_tip'] = $rider_total_tip[0][0]['total_rider_tip'];

                        /********* unpaid**********/
                        $unpaid_earnings_count_orders = $this->RiderOrder->getRiderUnPaidEarnings($val['Rider']['user_id']);
                        $rider_unpaid_orders = $this->RiderOrder->getRiderUnpaidOrders($val['Rider']['user_id']);
                        $rider_unpaid_earnings =  $unpaid_earnings_count_orders * $rider_fee;


                        foreach($rider_unpaid_orders as $key_rider_order_unp=>$rider_order_val_unp){
                            $order_ids_unp[$key_rider_order_unp] = $rider_order_val_unp['RiderOrder']['order_id'];


                        }

                        $rider_total_tip_unp =  $this->Order->getTotalRiderTip($order_ids_unp);

                        $unpaid = $rider_total_tip_unp[0][0]['total_rider_tip'] + $rider_unpaid_earnings;
                        $rider_earnings[$key]['Rider']['unpaid'] = $unpaid;

                        /********* unpaid**********/

                    }


                }




        }else {

                $platform_earnings = $this->Order->getPlatformTotalEarnings();
                $restaurant_earnings = $this->Order->getRestaurantWiseTotalEarnings();

                foreach ($restaurant_earnings as $res_key=>$res_val){
                    $restaurant_id = $res_val['Restaurant']['id'];
                    $restaurant_unpaid = $this->Order->getRestaurantUnpaid($restaurant_id);
                    $restaurant_earnings[$res_key][0]['unpaid'] = $restaurant_unpaid[0]['unpaid_total'];


                }
                $rider_earnings = $this->RiderOrder->getRiderEarnings();

                                    // CakeLog::write('debug', '$rider_earnings: '.print_r(json_encode($rider_earnings), TRUE));
                $rider_total = 0;
                if (count($rider_earnings) > 0) {

                    foreach ($rider_earnings as $key=>$val) {

                        $total_orders = $val[0]['total_orders'];
                        $rider_fee = $val['Rider']['rider_fee'];
                        $single_rider_earning = $rider_fee * $total_orders;
                        $rider_total = $rider_total + $single_rider_earning;
                        $rider_earnings[$key]['Rider']['earning'] = $single_rider_earning;

                        $rider_orders = $this->RiderOrder->getRiderOrders($val['Rider']['user_id']);
                        $order_ids = array();
                        foreach($rider_orders as $key_rider_order=>$rider_order_val){
                            $order_ids[$key_rider_order] = $rider_order_val['RiderOrder']['order_id'];


                        }

                        $rider_total_tip =  $this->Order->getTotalRiderTip($order_ids);


                        $rider_earnings[$key]['Rider']['total_rider_tip'] = $rider_total_tip[0][0]['total_rider_tip'];

                        /********* unpaid**********/
                        $unpaid_earnings_count_orders = $this->RiderOrder->getRiderUnPaidEarnings($val['Rider']['user_id']);
                        $rider_unpaid_orders = $this->RiderOrder->getRiderUnpaidOrders($val['Rider']['user_id']);
                        $rider_unpaid_earnings =  $unpaid_earnings_count_orders * $rider_fee;


                        foreach($rider_unpaid_orders as $key_rider_order_unp=>$rider_order_val_unp){
                            $order_ids_unp[$key_rider_order_unp] = $rider_order_val_unp['RiderOrder']['order_id'];


                        }

                        $rider_total_tip_unp =  $this->Order->getTotalRiderTip($order_ids_unp);

                        $unpaid = $rider_total_tip_unp[0][0]['total_rider_tip'] + $rider_unpaid_earnings;
                        $rider_earnings[$key]['Rider']['unpaid'] = $unpaid;

                        /********* unpaid**********/
                    }


                }
            }

            if($platform_earnings[0][0]['total_orders'] > 0){
            $output['code'] = 200;
                $output['msg']['Currency'] = $currency[0]['Currency'];
            $output['msg']['PlatformEarnings'] = $platform_earnings[0][0];
                $output['msg']['RiderEarnings'] = $rider_earnings;
            $output['msg']['PlatformEarnings']['rider_total_earnings'] = $rider_total + $platform_earnings[0][0]['total_rider_tip'];
            $output['msg']['RestaurantEarnings'] = $restaurant_earnings;
            //$output['msg']['RestaurantEarnings']['Restaurant'] = $restaurant_earnings['Restaurant'];
            echo json_encode($output);


            die();
        }else{
                Message::EmptyDATA();
                die();


            }
    }

    }

    public function showRestaurantTotalEarnings()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $restaurant_id = $data['restaurant_id'];

            $restaurant_earnings = $this->Order->getRestaurantTotalEarnings($restaurant_id);



            $output['code'] = 200;

            $output['msg'] = $restaurant_earnings[0][0];
            //$output['msg']['RestaurantEarnings'] = $restaurant_earnings;
            //$output['msg']['RestaurantEarnings']['Restaurant'] = $restaurant_earnings['Restaurant'];
            echo json_encode($output);


            die();
        }
    }

    public function addTransaction()
    {

        $this->loadModel("Transaction");
        $this->loadModel("Order");
        $this->loadModel("RiderOrder");

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $transaction['amount'] = $data['amount'];

            $transaction['paid_date'] = $data['paid_date'];
            $transaction['pay_via'] = $data['pay_via'];
            $transaction['note'] = $data['note'];
            $transaction['type'] = $data['type'];
            $transaction['created'] = date('Y-m-d H:i:s', time());


            $duplicate = $this->Transaction->checkDuplicate($transaction);

           if($duplicate < 1){
               if($transaction['type'] == "restaurant") {
                   $transaction['restaurant_id'] = $data['restaurant_id'];
                   $this->Transaction->save($transaction);
                   $id = $this->Transaction->getInsertID();
                   $this->Order->saveRestaurantTransactionID($id,$data['restaurant_id']);





               }else if($transaction['type'] == "rider") {
                   $transaction['rider_user_id'] = $data['rider_user_id'];
                   $this->Transaction->save($transaction);
                   $id = $this->Transaction->getInsertID();
                  // $this->Order->saveRestaurantTransactionID($id,$data['restaurant_id']);
                   $this->RiderOrder->saveRiderTransactionID($id, $data['rider_user_id']);
               }

               $details = $this->Transaction->getDetails($id);

               $output['code'] = 200;

               $output['msg'] = $details;
               echo json_encode($output);


               die();
           }else{

               echo Message::DUPLICATEDATE();
               die();
           }

        }
    }

    public function showTransactions()
    {

        $this->loadModel("Transaction");


        if ($this->request->isPost()) {





            $restaurant_transactions = $this->Transaction->getTransactions();


            $output['code'] = 200;

            $output['msg'] = $restaurant_transactions;
            echo json_encode($output);


            die();
        }
    }


    public function showRestaurants()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {


            $restaurants = $this->Restaurant->getAllRestaurants();

            $i = 0;
            foreach ($restaurants as $rest) {
                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }
                $i++;

            }
            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();
        }
    }

    public function showNonActiveRestaurants()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {


            $restaurants = $this->Restaurant->getNonActiveRestaurants();




            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();
        }
    }

    public function showRestaurantsBasedOnSearchKeyword()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $keyword = $data['keyword'];


            $restaurants = $this->Restaurant->searchRestaurant($keyword);

            $i = 0;
            foreach ($restaurants as $rest) {
                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }
                $i++;

            }
            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();
        }
    }


    public function editRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("Tax");
        $this->loadModel("Currency");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name = $data['name'];
            $slogan = $data['slogan'];
            $about = $data['about'];
            $notes = $data['notes'];

            $min_order_price = $data['min_order_price'];
            $delivery_free_range = $data['delivery_free_range'];
            $preparation_time = $data['preparation_time'];
            $tax_free = $data['tax_free'];
            $google_analytics = $data['google_analytics'];
            $phone = $data['phone'];
            $time_zone = $data['timezone'];
            $menu_style = $data['menu_style'];
            $promoted = $data['promoted'];
            $created = date('Y-m-d H:i:s', time());

            $city = $data['city'];
            $restaurant_id = $data['restaurant_id'];
            $state = $data['state'];

            $country = $data['country'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];
            $currency_id = $data['currency_id'];
            $tax_id = $data['tax_id'];
            $speciality = $data['speciality'];



            $restaurant_timing = $data['restaurant_timing'];








            $restaurant['name'] = $name;
            $restaurant['slogan'] = $slogan;
            $restaurant['about'] = $about;


            //$restaurant['delivery_fee'] = $delivery_fee;
            $restaurant['phone'] = $phone;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['timezone'] = $time_zone;
            $restaurant['menu_style'] = $menu_style;
            $restaurant['promoted'] = $promoted;
            $restaurant['speciality'] = $speciality;
            $restaurant['notes'] = $notes;
            $restaurant['min_order_price'] = $min_order_price;
            $restaurant['delivery_free_range'] = $delivery_free_range;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['tax_free'] = $tax_free;
            $restaurant['google_analytics'] = $google_analytics;

            $restaurant['currency_id'] = $currency_id;
            $restaurant['tax_id'] = $tax_id;


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;

            $restaurant_details = $this->Restaurant->getRestaurantDetail($restaurant_id);

            if (isset($data['image']) && $data['image'] != " ") {
                $image_db = $restaurant_details[0]['Restaurant']['image'];


                @unlink($image_db);


                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $image, $folder_url);
                $restaurant['image'] = $filePath;


            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {
                $cover_image_db = $restaurant_details[0]['Restaurant']['cover_image'];
                @unlink($cover_image_db);
                $cover_image = $data['cover_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                $restaurant['cover_image'] = $filePath;

            }


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;


            $this->RestaurantTiming->deleteAll(array(
                'restaurant_id' => $restaurant_id
            ), false);

            foreach ($restaurant_timing as $k => $v) {


                $timing[$k]['day'] = $v['day'];
                $timing[$k]['opening_time'] = $v['opening_time'];
                $timing[$k]['closing_time'] = $v['closing_time'];
                $timing[$k]['restaurant_id'] = $restaurant_id;

            }

            $this->RestaurantTiming->saveAll($timing);
            $this->RestaurantLocation->id = $restaurant_id;
            $this->RestaurantLocation->save($restaurant_location);
            $this->Restaurant->id = $restaurant_id;


            if($this->Restaurant->save($restaurant)) {


                $restaurant_details = $this->Restaurant->getRestaurantDetail($restaurant_id);


                $output['code'] = 200;
                $output['msg'] = $restaurant_details;
                echo json_encode($output);

                die();


            }else{


                echo Message::DATASAVEERROR();
                die();

            }



        }


    }







    public function addRestaurantTiming(){
        $this->loadModel("RestaurantTiming");

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];
            $restaurant_timing = $data['restaurant_timing'];

            $details = $this->RestaurantTiming->getDetails($restaurant_id);
            if(count($details) > 0){

                $this->RestaurantTiming->deleteRestaurantTiming($restaurant_id);
            }


            foreach ($restaurant_timing as $k => $v) {


                $timing[$k]['day'] = $v['day'];
                $timing[$k]['opening_time'] = $v['opening_time'];
                $timing[$k]['closing_time'] = $v['closing_time'];
                $timing[$k]['restaurant_id'] = $restaurant_id;

            }

            $this->RestaurantTiming->saveAll($timing);

            $details = $this->RestaurantTiming->getDetails($restaurant_id);

            if(count($details) > 0) {

                $output['code'] = 200;
                $output['msg'] = $details;
                echo json_encode($output);

                die();

            }else{

                Message::EmptyDATA();
                die();

            }
        }

    }






    public function showAllOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $status = $data['status'];

            foreach ($status as $key => $val) {

                $status[$key] = $val['status'];

            }
            $orders = $this->Order->getAllOrdersSuperAdmin($status);
            if($status==0){

                //$orders = $this->Order->getAllOrdersSuperAdmin();
            }else if($status==3){
                //$orders = $this->Order->getRejectedOrAcceptedOrdersSuperAdmin(2);
            }else {
                //$orders = $this->Order->getAllOrdersAccordingToStatusSuperAdmin($status);
            }
            foreach ($orders as $key => $val) {

                $rider_details =  $this->RiderOrder->getRiderDetailsAgainstOrderID($val['Order']['id']);



                /*  if($val['Order']['payment_method_id'] == 0){
                      $orders[$key]['PaymentMethod']['stripe'] = "";
                      $orders[$key]['PaymentMethod']['paypal'] = "";
                      $orders[$key]['PaymentMethod']['created'] = "";
                      $orders[$key]['PaymentMethod']['user_id'] = "";
                      $orders[$key]['PaymentMethod']['id'] = "";
                      $orders[$key]['PaymentMethod']['default'] = "";
                  }
   */

                if(count($rider_details) > 0){




                    $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($val['Order']['id']);
                    $pickup_time             = $this->RiderTrackOrder->isEmptyPickUpTime($val['Order']['id']);
                    $on_my_way_to_user_time  = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($val['Order']['id']);
                    $delivery_time           = $this->RiderTrackOrder->isEmptyDeliveryTime($val['Order']['id']);

                    if ($on_my_way_to_hotel_time == 1 && $pickup_time == 0 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {



                        $msg = "On the way to restaurant";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {




                        $msg = "Order collected from restaurant";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 0) {




                        $msg = "On the way to customer ";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 1) {




                        $msg = "Delivered";

                    } else  if($rider_details[0]['RiderOrder']['accept_reject_status'] == 1){


                        $msg = "order has been accepted by the rider";
                    }else if($rider_details[0]['RiderOrder']['accept_reject_status'] == 0){


                        $msg = "Waiting for the rider to accept the order";
                    }


                    $orders[$key]['Order']['RiderOrder']= $rider_details[0]['RiderOrder'];
                    $orders[$key]['Order']['RiderOrder']['order_status'] = $msg;
                    $orders[$key]['Order']['RiderOrder']['Rider']= $rider_details[0]['Rider'];
                    $orders[$key]['Order']['RiderOrder']['Assigner']= $rider_details[0]['Assigner'];


                }else{

                    $orders[$key]['Order']['RiderOrder']= array();

                }
            }


            $output['code'] = 200;

            $output['msg'] =  $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showAllOrdersAutoLoad()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {



            $orders = $this->Order->getOnlyOrdersAccordingToStatusSuperAdmin();


            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }


    /*public function showOrderDetail()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];
            $orders = $this->Order->getOrderDetailBasedOnID($order_id);


               $orders[0]['Currency'] = $orders[0]['Restaurant']['Currency'];
               $orders[0]['Tax'] = $orders[0]['Restaurant']['Tax'];
               $orders[0]['RestaurantLocation'] = $orders[0]['Restaurant']['RestaurantLocation'];

            if(array_key_exists("Rider",$orders[0]['RiderOrder'])) {
                $orders[0]['Rider'] = $orders[0]['RiderOrder']['Rider'];
                unset($orders[0]['RiderOrder']['Rider']);
            }
            if(array_key_exists("RiderLocation",$orders[0]['RiderOrder'])) {
                $orders[0]['RiderLocation'] = $orders[0]['RiderOrder']['RiderLocation'];
                unset($orders[0]['RiderOrder']['RiderLocation']);
            }
                    $i=0;
           foreach($orders[0]['OrderMenuItem'] as $menu_item){

               $orders[0]['OrderMenuItem'][$i]['id'] = $menu_item['id'];
               $orders[0]['OrderMenuItem'][$i]['order_id'] = $menu_item['order_id'];
               $orders[0]['OrderMenuItem'][$i]['name'] = $menu_item['name'];
               $orders[0]['OrderMenuItem'][$i]['quantity'] = $menu_item['quantity'];
               $orders[0]['OrderMenuItem'][$i]['price'] = $menu_item['price'];
               $orders[0]['OrderMenuItem'][$i]['deal_description'] = $menu_item['deal_description'];

               if(count( $menu_item['OrderMenuExtraItem']) > 0){
                   $j = 0;
                   foreach( $menu_item['OrderMenuExtraItem'] as $extra_menu_item){

                       $orders[0]['OrderMenuExtraItem'][$j]['id'] = $extra_menu_item['id'];
                       $orders[0]['OrderMenuExtraItem'][$j]['order_menu_item_id'] = $extra_menu_item['order_menu_item_id'];
                       $orders[0]['OrderMenuExtraItem'][$j]['name'] = $extra_menu_item['name'];
                       $orders[0]['OrderMenuExtraItem'][$j]['quantity'] = $extra_menu_item['quantity'];
                       $orders[0]['OrderMenuExtraItem'][$j]['price'] = $extra_menu_item['price'];
                       unset($orders[0]['OrderMenuItem'][$i]['OrderMenuExtraItem'][$j]);
                       $j++;
                   }

               }
              // unset($orders[0]['OrderMenuItem'][$i]);
              $i++;
           }

               unset($orders[0]['Restaurant']['Currency']);
               unset($orders[0]['Restaurant']['Tax']);
               unset($orders[0]['Restaurant']['RestaurantLocation']);




            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }*/

    public function showOrderDetail()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];

            //$user_id = $data['user_id'];






            $orders = $this->Order->getOrderDetailBasedOnOrderIDSuperAdmin($order_id);


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

    public function showWeeklyAndMonthlyOrders()
    {

        $this->loadModel("Order");



        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            if(isset($data['restaurant_id']) && !isset($data['start_date'])){

                $orders =  $this->Order->getOnlyRestaurantOrders($data['restaurant_id']);
            }else if(isset($data['restaurant_id']) && isset($data['start_date'])){

                $orders =  $this->Order->getOnlyRestaurantOrdersBetweenTwoDates($data['restaurant_id'],$data['start_date'],$data['end_date']);
            }else if(!isset($data['restaurant_id']) && isset($data['start_date'])){

                $orders =  $this->Order-> getOnlyOrdersBetweenTwoDates($data['start_date'],$data['end_date']);
            }else if(!isset($data['restaurant_id']) && !isset($data['start_date'])){

                $orders =  $this->Order-> getAllOnlyOrders();
            }

            /* $add_day_in_start_date = new DateTime($start_date);
             $add_day_in_start_date->modify('+1 day');
             $start_date_next_day =  $add_day_in_start_date->format('Y-m-d');

             $add_day_in_end_date = new DateTime($start_date);
             $add_day_in_end_date->modify('+1 day');
             $end_date_next_day =  $add_day_in_end_date->format('Y-m-d');
 */








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

    public function assignOrderToRider()
    {

        $this->loadModel("RiderOrder");
        $this->loadModel("Order");
        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_user_id = $data['rider_user_id'];
            $assigner_user_id = $data['assigner_user_id'];
            $order_id = $data['order_id'];
            $created = date('Y-m-d H:i:s', time());

            $this->Order->id = $order_id;
            $delivery = $this->Order->field('delivery');
            if($delivery == 0){


                $output['code'] = 201;

                $output['msg'] = "You can't assign this order to any rider because user will himself pickup the food from the restaurant ";
                echo json_encode($output);


                die();
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

                    /****/

                    /************notification to RIDER*************/
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


                    echo Message::DATASUCCESSFULLYSAVED();

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




    public function showRiderOrders()
    {

        $this->loadModel("RiderOrder");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_user_id = $data['rider_user_id'];
            $orders = $this->RiderOrder->getAllRiderOrders($rider_user_id);


            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderTimings()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $riderTimings = $this->User->getAllRidersTimings();

            if(count($riderTimings) > 0){

                foreach ($riderTimings  as $key => $val) {

                    if(count($val['RiderTiming']) > 0) {
                        $timings[$key] = $val;

                    }
                }
            }
            $output['code'] = 200;

            $output['msg'] = $timings;
            echo json_encode($output);


            die();
        }
    }

    public function confirmRiderTiming()
    {



        $this->loadModel("RiderTiming");
        $this->loadModel("UserInfo");




        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $id = $data['id'];
            $admin_confirm = $data['admin_confirm'];






            $this->RiderTiming->id = $id;
            $this->RiderTiming->saveField('admin_confirm',$admin_confirm);


            $result         = $this->RiderTiming->getRiderTimingAgainstID($id);

            $user_id = $result[0]['RiderTiming']['user_id'];
            $this->UserInfo->id = $user_id;
            $device_token = $this->UserInfo->field('device_token');





            if (strlen($device_token) > 10) {


                /************************notification*********************************/
                $notification['to'] = $device_token;
                $notification['notification']['title'] = "Your shift has been approved";
                $notification['notification']['body'] = 'Please confirm your schedule!';
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";

                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));

                /************************end notification*********************************/
            }
            $output['code'] = 200;
            $output['msg']  = $result;
            echo json_encode($output);
            die();







        }

    }

    public function editRiderLocation()
    {

        $this->loadModel("RiderLocation");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $rider_location['city'] = $data['city'];
            $rider_location['country'] = $data['country'];
            $rider_location['address_to_start_shift'] = $data['address_to_start_shift'];


            $this->RiderLocation->id = $id;
            if($this->RiderLocation->save($rider_location)){

                $rider_location =  $this->RiderLocation->getRiderLocationAgainstID($id);

                $output['code'] = 200;

                $output['msg'] = $rider_location;
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
    public function showRiders()
    {

        $this->loadModel("User");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderLocation");
        $this->loadModel("Currency");


        if ($this->request->isPost()) {


            // $riders = $this->User->getAllRiders();
            $online_riders = $this->User->getOnlineOfflineRiders(1);
            $offline_riders = $this->User->getOnlineOfflineRiders(0);

            $riders = $this->User->getAllRiders();


            foreach ($online_riders as $key => $val) {

                $user_id = $val['UserInfo']['user_id'];
                $riderLocation = $this->RiderLocation->getRiderLocation($user_id);
                $online_riders[$key]['RiderLocation'] = $riderLocation;
                $total_rider_orders = $this->RiderOrder->countRiderAssignOrders($user_id);
                $online_riders[$key]['UserInfo']['total_orders'] = $total_rider_orders;

            }

            foreach ($offline_riders as $key => $val) {

                $user_id = $val['UserInfo']['user_id'];
                $riderLocation = $this->RiderLocation->getRiderLocation($user_id);
                $offline_riders[$key]['RiderLocation'] = $riderLocation;
                $total_rider_orders = $this->RiderOrder->countRiderAssignOrders($user_id);
                $offline_riders[$key]['UserInfo']['total_orders'] = $total_rider_orders;
            }

            $currency = $this->Currency->getAllCurrency();
            $result['OnlineRiders'] = $online_riders;
            $result['OfflineRiders'] = $offline_riders;
            $result['Riders'] = $riders;
            $result['Currency'] = $currency[0]['Currency'];



            $output['code'] = 200;

            $output['msg'] = $result;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderRequests()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $request = $this->User->getAllInactiveRiders();


            $output['code'] = 200;

            $output['msg'] = $request;
            echo json_encode($output);


            die();
        }
    }



    public function deleteRiderRequest()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];


            if ($this->RiderRequest->delete($id)) {

                Message::DELETEDSUCCESSFULLY();
                die();
            }else{

                echo Message::EmptyDATA();
                die();

            }
        }
    }


    public function editRiderRequest()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $rider_request['first_name'] = $data['first_name'];
            $rider_request['last_name'] = $data['last_name'];
            $rider_request['phone'] = $data['phone'];
            $rider_request['email'] = $data['email'];
            $rider_request['city'] = $data['city'];
            $rider_request['state'] = $data['state'];
            $rider_request['country'] = $data['country'];
            $rider_request['address'] = $data['address'];

            $this->RiderRequest->id = $id;
            $this->RiderRequest->save($rider_request);
            $result = $this->RiderRequest->getLastInsertRow($id);


            $output['msg'] = $result;
            $output['code'] = 200;
            echo json_encode($output);
            die();
        }
    }

    public function showRestaurantRequests()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $request = $this->RestaurantRequest->getAllRestaurantRequests();


            $output['code'] = 200;

            $output['msg'] = $request;
            echo json_encode($output);


            die();
        }
    }

    public function deleteRestaurantRequest()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];


            if ($this->RestaurantRequest->delete($id)) {

                Message::DELETEDSUCCESSFULLY();
                die();
            }else{

                echo Message::EmptyDATA();
                die();

            }
        }
    }


    public function editRestaurantRequest()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $restaurant_request['restaurant_name'] = $data['restaurant_name'];
            $restaurant_request['contact_name'] = $data['contact_name'];
            $restaurant_request['phone'] = $data['phone'];
            $restaurant_request['email'] = $data['email'];
            $restaurant_request['address'] = $data['address'];
            $restaurant_request['description'] = $data['description'];


            $this->RestaurantRequest->id = $id;
            $this->RestaurantRequest->save($restaurant_request);
            $result = $this->RestaurantRequest->getLastInsertRow($id);


            $output['msg'] = $result;
            $output['code'] = 200;
            echo json_encode($output);
            die();
        }
    }


    public function addRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("Tax");
        $this->loadModel("Currency");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");
        $this->loadModel("RestaurantCategory");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name = $data['name'];
            $slogan = $data['slogan'];
            //$speciality = $data['speciality'];
            $categories = $data['categories'];
            $about = $data['about'];
            $min_order_price = $data['min_order_price'];
            $delivery_free_range = $data['delivery_free_range'];
            $tax_free = $data['tax_free'];
            $phone = $data['phone'];
            $time_zone = $data['timezone'];
            $city = $data['city'];
            $state = $data['state'];
            $country = $data['country'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];

            $notes = $data['notes'];

            $currency_id = $data['currency_id'];
            $tax_id = $data['tax_id'];
            $type = $data['type'];

            $preparation_time = $data['preparation_time'];




            /* $tax = $this->Tax->getTaxID($state, $country);
             $currency = $this->Currency->getCurrencyID($country);
             if (count($tax) == 1) {

                 $tax_id               = $tax[0]['Tax']['id'];
                 $restaurant['tax_id'] = $tax_id;
             } else {

                 $output['code'] = 205;

                 $output['msg'] = "Please first add Tax Detail";
                 echo json_encode($output);


                 die();

             }

             if (count($currency) == 1) {

                 $currency_id = $currency[0]['Currency']['id'];
                 $restaurant['currency_id'] = $currency_id;
             } else {

                 $output['code'] = 205;

                 $output['msg'] = "Please first add Currency Detail First";
                 echo json_encode($output);


                 die();

             }
 */



if(isset($data['admin_commission'])){

    $admin_commission = $data['admin_commission'];
    $restaurant['admin_commission'] = $admin_commission;
}



            $restaurant['name'] = $name;

            $restaurant['slogan'] = $slogan;
          //  $restaurant['speciality'] = $speciality;
            $restaurant['about'] = $about;

            $restaurant['notes'] = $notes;

            $restaurant['phone'] = $phone;


            $restaurant['timezone'] = $time_zone;




            $restaurant['min_order_price'] = $min_order_price;
            $restaurant['delivery_free_range'] = $delivery_free_range;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['tax_free'] = $tax_free;


            $restaurant['currency_id'] = $currency_id;
            $restaurant['type'] = $type;
            
            $restaurant['tax_id'] = $tax_id;


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;


            //update
            if (isset($data['id'])) {

                $restaurant_id = $data['id'];

                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant);

                $this->RestaurantLocation->id = $restaurant_id;
                $this->RestaurantLocation->save($restaurant_location);


                $this->RestaurantCategory->deleteAll(array('RestaurantCategory.restaurant_id' => $restaurant_id), false);
                foreach ($categories as $value) {
                        $restaurant_category = [];
                        $restaurant_category['restaurant_id'] = $restaurant_id;
                        $restaurant_category['category_id'] = $value;
                        $this->RestaurantCategory->create(false);
                        $this->RestaurantCategory->save($restaurant_category);
                }


                $rest_details = $this->Restaurant->getRestaurantDetail($restaurant_id);
                $output['code'] = 200;
                $output['msg'] = $rest_details;
                echo json_encode($output);
                    die();
            } else if ($this->Restaurant->isDuplicateRecord($name, $slogan, $phone, $about) == 0) {

                $added_by = $data['added_by'];
                $promoted = $data['promoted'];
                $google_analytics = $data['google_analytics'];
                $menu_style = $data['menu_style'];
                $created = date('Y-m-d H:i:s', time());


                $restaurant['google_analytics'] = $google_analytics;
                $restaurant['promoted'] = $promoted;
                $restaurant['added_by'] = $added_by;
                $restaurant['menu_style'] = $menu_style;
                $restaurant['created'] = $created;
                $email = strtolower($data['email']);

                $password = $data['password'];
                $first_name = $data['first_name'];
                $last_name = $data['last_name'];
                $user_id = $this->registerRestaurantUser($email, $password, $first_name, $last_name, $phone, $type);

                $restaurant['user_id'] = $user_id;

                if ($this->Restaurant->save($restaurant)) {


                    $restaurant_timing = $data['restaurant_timing'];

                    $id = $this->Restaurant->getLastInsertId();
                    $rest_details = $this->Restaurant->getRestaurantDetail($id);
                    $restaurant_location['restaurant_id'] = $id;
                    $this->RestaurantLocation->save($restaurant_location);

                    foreach ($categories as $value) {
                        $restaurant_category = [];
                        $restaurant_category['restaurant_id'] = $id;
                        $restaurant_category['category_id'] = $value;
                        $this->RestaurantCategory->create(false);
                        $this->RestaurantCategory->save($restaurant_category);
                    }



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

                        $timing[$k]['day'] = $v['day'];
                        $timing[$k]['opening_time'] = $v['opening_time'];
                        $timing[$k]['closing_time'] = $v['closing_time'];
                        $timing[$k]['restaurant_id'] = $id;

                    }

                    $this->RestaurantTiming->saveAll($timing);


                    //CustomEmail::welcomeStudentEmail($email);
                
                    $output['code'] = 200;
                    $output['msg'] = $rest_details;
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

    public function registerRestaurantUser($email, $password, $first_name, $last_name, $phone, $type)
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');


        //$json = file_get_contents('php://input');


        //file_put_contents(UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

        if ($email != null && $password != null) {


            $user['email'] = $email;
            $user['password'] = $password;
            //$user['role'] = "hotel";
            $user['role'] = $type == 'restaurant'?'hotel':$type;

            $user['active'] = 1;
            $user['created'] = date('Y-m-d H:i:s', time());


            $count = $this->User->isEmailAlreadyExist($email);


            if ($count && $count > 0) {
                echo Message::DATAALREADYEXIST();
                die();

            } else {

                $lib = new Lib;
                $key = Security::hash(CakeText::uuid(), 'sha512', true);


                if (!$this->User->save($user)) {
                    echo Message::DATASAVEERROR();
                    die();
                }


                $user_id = $this->User->getInsertID();
                $user_info['user_id'] = $user_id;


                $user_info['full_name'] = $first_name." ".$last_name;
                //$user_info['last_name'] = $last_name;
                $user_info['phone'] = $phone;


                if (!$this->UserInfo->save($user_info)) {
                    echo Message::DATASAVEERROR();
                    die();
                }


                return $user_id;


            }
        } else {
            echo Message::ERROR();
        }

    }


    public function addRestaurantImage()

    {

        $this->loadModel("Restaurant");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];
            if (isset($data['image']) && $data['image'] != " ") {

                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $image, $folder_url);
                $restaurant_image['image'] = $filePath;
                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant_image);

            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                $cover_image = $data['cover_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                $restaurant_image['cover_image'] = $filePath;
                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant_image);





                die();
            }

            $rest_details = $this->Restaurant->getRestaurantDetail($restaurant_id);
            $output['code'] = 200;
            $output['msg'] = $rest_details;
            echo json_encode($output);
            die();
        }
    }


    public function addRestaurantCoverImage()

    {

        $this->loadModel("Restaurant");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                $cover_image = $data['cover_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                $restaurant_image['cover_image'] = $filePath;
                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant_image);






            }

            $rest_details = $this->Restaurant->getRestaurantDetail($restaurant_id);
            $output['code'] = 200;
            $output['msg'] = $rest_details;
            echo json_encode($output);
            die();
        }
    }
    public function addRestaurantcatagoryImage()

    {

        $this->loadModel("Restaurant");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            if (isset($data['catagory_image']) && $data['catagory_image'] != " ") {

                $catagory_image = $data['catagory_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $catagory_image, $folder_url);
                $restaurant_catagory_image['catagory_image'] = $filePath;
                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant_catagory_image);






            }

            $rest_details = $this->Restaurant->getRestaurantDetail($restaurant_id);
            $output['code'] = 200;
            $output['msg'] = $rest_details;
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
            $created       = date('Y-m-d H:i:s', time());


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
            $created            = date('Y-m-d H:i:s', time());



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
            $created                          = date('Y-m-d H:i:s', time());


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

            $created = date('Y-m-d H:i:s', time());

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

    public function showRestaurantsMenu()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            $menus = $this->Restaurant->getRestaurantMenusForMobile($restaurant_id);


            $output['code'] = 200;

            $output['msg'] =  $menus;
            echo json_encode($output);


            die();

        }
    }
    public function showRestaurantDetail()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            $restaurant_detail = $this->Restaurant->getRestaurantDetailInfoSuperAdmin($restaurant_id);




            $output['code'] = 200;

            $output['msg'] = $restaurant_detail;
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


    public function addAppSliderImage()
    {


        $this->loadModel('AppSlider');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $image = $data['image'];
            $user_id = $data['user_id'];

            if (isset($data['image']) && $data['image'] != " ") {

                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $image['image'] = $filePath;
            }


            if (isset($data['id'])) {
                $id = $data['id'];
                $app_slider = $this->AppSlider->getImageDetail($id);
                $image_path = $app_slider[0]['AppSlider']['image'];

                @unlink($image_path);

                $this->AppSlider->id = $id;
                $this->AppSlider->save($image);
                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else if ($this->AppSlider->save($image)) {

                echo Message::DATASUCCESSFULLYSAVED();

                die();
            } else {


                echo Message::DATASAVEERROR();
                die();
            }


        }
    }

    public function showAppSliderImages()
    {

        $this->loadModel("AppSlider");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $images = $this->AppSlider->getImages();


            $output['code'] = 200;

            $output['msg'] = $images;
            echo json_encode($output);


            die();
        }
    }

    public function deleteAppSliderImage()
    {

        $this->loadModel("AppSlider");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $app_slider = $this->AppSlider->getImageDetail($id);
            if (count($app_slider) > 0) {
                $image_path = $app_slider[0]['AppSlider']['image'];

                @unlink($image_path);
                if ($this->AppSlider->deleteAppSlider($id)) {

                    Message::DELETEDSUCCESSFULLY();


                    die();

                } else {

                    Message::ERROR();


                    die();

                }


            } else {

                $output['code'] = 202;

                $output['msg'] = "no image exist";
                echo json_encode($output);
                die();
            }


        }
    }

    public function addWebSliderImage()
    {


        $this->loadModel('WebSlider');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $image = $data['image'];
            $user_id = $data['user_id'];

            if (isset($data['image']) && $data['image'] != " ") {

                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $image['image'] = $filePath;
            }


            if (isset($data['id'])) {
                $id = $data['id'];
                $app_slider = $this->WebSlider->getImageDetail($id);
                $image_path = $app_slider[0]['WebSlider']['image'];

                @unlink($image_path);

                $this->WebSlider->id = $id;
                $this->WebSlider->save($image);
                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else if ($this->WebSlider->save($image)) {

                echo Message::DATASUCCESSFULLYSAVED();

                die();
            } else {


                echo Message::DATASAVEERROR();
                die();
            }


        }
    }


    public function showWebSliderImages()
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

    public function deleteWebSliderImage()
    {
        $this->loadModel("WebSlider");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $id = $data['id'];

            $app_slider = $this->WebSlider->getImageDetail($id);
            if (count($app_slider) > 0) {
                $image_path = $app_slider[0]['WebSlider']['image'];

                @unlink($image_path);
                if ($this->WebSlider->deleteWebSlider($id)) {
                    Message::DELETEDSUCCESSFULLY();
                    die();
                } else {
                    Message::ERROR();
                    die();
                }
            } else {
                $output['code'] = 202;
                $output['msg'] = "no image exist";
                echo json_encode($output);
                die();
            }
        }
    }


    public function showCurrencies()
    {

        $this->loadModel("Currency");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');


            $currencies = $this->Currency->getAllCurrency();


            $output['code'] = 200;

            $output['msg'] = $currencies;
            echo json_encode($output);


            die();
        }
    }

    public function showCategories()
    {

        $this->loadModel("Category");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');


            $categories = $this->Category->getAllCategory();


            $output['code'] = 200;

            $output['msg'] = $categories;
            echo json_encode($output);


            die();
        }
    }




    public function deleteCurrency()
    {

        $this->loadModel("Currency");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Currency->getCurrencyDetail($id);

            if(count($details) > 0){

                $this->Currency->id = $id;
                $this->Currency->delete();

                $details = $this->Currency->getCurrencyDetail($id);
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

                $output['msg'] = "No Currency details found";
                echo json_encode($output);
                die();

            }


        }
    }

    public function deleteCategory()
    {

        $this->loadModel("Category");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Category->getCategoryDetail($id);

            if(count($details) > 0){

                $this->Category->id = $id;
                $this->Category->delete();

                $details = $this->Category->getCategoryDetail($id);
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

                $output['msg'] = "No Category details found";
                echo json_encode($output);
                die();

            }


        }
    }

    public function showCurrencyDetail()
    {

        $this->loadModel("Currency");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Currency->getCurrencyDetail($id);


            $output['code'] = 200;

            $output['msg'] = $details;
            echo json_encode($output);


            die();
        }
    }

    public function showCategoryDetail()
    {

        $this->loadModel("Category");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $details = $this->Category->getCategoryDetail($id);


            $output['code'] = 200;

            $output['msg'] = $details;
            echo json_encode($output);


            die();
        }
    }


    public function getRestaurantCategories()
    {

        $this->loadModel("RestaurantCategory");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $details = $this->RestaurantCategory->getRestaurantCategories($restaurant_id);


            $output['code'] = 200;

            $output['msg'] = $details;
            echo json_encode($output);


            die();
        }
    }

    public function editMainMenuIndex()
    {

        $this->loadModel("RestaurantMenu");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $menu = $data['menu'];

            foreach ($menu as $k => $v) {


                $this->RestaurantMenu->id = $v['menu_id'];
                $this->RestaurantMenu->saveField('index',$v['index']);


            }

            $output['code'] = 200;

            $output['msg'] = "updated";
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



    public function addTax()
    {


        $this->loadModel('Tax');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $tax['city'] = $data['city'];
            $tax['state'] = $data['state'];
            $tax['country'] = $data['country'];
            $tax['tax'] = $data['tax'];
            $tax['country_code'] = $data['country_code'];
            $tax['delivery_time'] = $data['delivery_time'];
            $tax['delivery_fee_per_km'] = $data['delivery_fee_per_km'];


            if (isset($data['id'])) {

                $tax_id = $data['id'];
                $this->Tax->id = $tax_id;

                if ($this->Tax->save($tax)) {

                    $detail = $this->Tax->getTaxDetail($tax_id);

                    $output['code'] = 200;
                    $output['msg'] = $detail;
                    echo json_encode($output);

                    die();
                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                $count = $this->Tax->isDuplicateRecord($data['city'], $data['state'], $data['country']);
                if ($count == 0) {
                    if ($this->Tax->save($tax)) {
                        $id = $this->Tax->getLastInsertId();
                        $detail = $this->Tax->getTaxDetail($id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
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
    }
    public function addSettings()
    {

        $this->loadModel("Setting");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $type = $data['type'];
            $value = $data['value'];


            $setting_details = $this->Setting->getSettingsAgainstType($type);

            if(count($setting_details) > 0){

                $this->Setting->id = $setting_details['Setting']['id'];
                $this->Setting->saveField('value',$value);

                $setting_details = $this->Setting->getDetails($setting_details['Setting']['id']);


                $output['code'] = 200;

                $output['msg'] = $setting_details;
                echo json_encode($output);


                die();

            }

            $setting['type'] = $type;
            $setting['value'] = $value;



            $this->Setting->save($setting);

            $id = $this->Setting->getInsertID();

            $setting_details = $this->Setting->getDetails($id);


            $output['code'] = 200;

            $output['msg'] = $setting_details;
            echo json_encode($output);


            die();


        }
    }


    public function showSettings()
    {

        $this->loadModel("Setting");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');


            $settings = $this->Setting->getAll();


                $new_array = array();


              foreach ($settings as $key=>$val){

                    $type = $val['Setting']['type'];
                  $new_array[$type] = $val['Setting'];


            }


            $output['code'] = 200;

            $output['msg'] = $new_array;
            echo json_encode($output);


            die();
        }
    }





    public function addCurrency()
    {


        $this->loadModel('Currency');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $currency['country'] = $data['country'];
            $currency['currency'] = $data['currency'];
            $currency['code'] = $data['code'];
            $currency['symbol'] = $data['symbol'];


            if (isset($data['id'])) {

                $currency_id = $data['id'];
                $isDataExist = $this->Currency->getCurrencyDetail($currency_id);
                if (count($isDataExist) > 0) {
                    $this->Currency->id = $currency_id;

                    if ($this->Currency->save($currency)) {

                        $detail = $this->Currency->getCurrencyDetail($currency_id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
                        echo json_encode($output);

                        die();
                    } else {


                        echo Message::DATASAVEERROR();
                        die();
                    }
                } else {


                    echo Message::EmptyDATA();
                    die();

                }
            } else {

                $count = $this->Currency->isDuplicateRecord($currency);
                if ($count == 0) {
                    if ($this->Currency->save($currency)) {
                        $id = $this->Currency->getLastInsertId();
                        $detail = $this->Currency->getCurrencyDetail($id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
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
    }

    public function addCategory()
    {


        $this->loadModel('Category');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $category['category'] = $data['category'];
            $category['parent_id'] = $data['parent_id'];

            if (isset($data['icon']) && $data['icon'] != "") {

                    $image = $data['icon'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath = Lib::uploadFileintoFolder("", $image, $folder_url);
                    $category['icon'] = $filePath;
            }

            if (isset($data['id'])) {

                $category_id = $data['id'];

                $isDataExist = $this->Category->getCategoryDetail($category_id);
                if (count($isDataExist) > 0) {
                    $this->Category->id = $category_id;

                    if (isset($data['icon']) && $data['icon'] != "" && file_exists($isDataExist['Category']['icon'])) {
                           unlink($isDataExist['Category']['icon']);
                    }



                    if ($this->Category->save($category)) {

                        $detail = $this->Category->getCategoryDetail($category_id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
                        echo json_encode($output);

                        die();
                    } else {


                        echo Message::DATASAVEERROR();
                        die();
                    }
                } else {


                    echo Message::EmptyDATA();
                    die();

                }
            } else {

                $count = $this->Category->isDuplicateRecord($category);
                if ($count == 0) {
                    if ($this->Category->save($category)) {
                        $id = $this->Category->getLastInsertId();
                        $detail = $this->Category->getCategoryDetail($id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
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
            // $id        = $this->Restaurant->getRestaurantID($user_id);
            $id        = $user_id;
            if (count($id) > 0) {
                // $restaurant_id = $id[0]['Restaurant']['id'];
                $restaurant_id = $id;
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


    public function addRestaurantCoupon_old()
    {

        $this->loadModel("RestaurantCoupon");
        //$this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_code   = $data['coupon_code'];
            $limit_users   = $data['limit_users'];
            $discount      = $data['discount'];
            $expire_date   = $data['expire_date'];
            $restaurant_id = $data['restaurant_id'];
            $type = $data['type'];


            CakeLog::write('debug', '$data: '.print_r($data, TRUE));



            $coupon['coupon_code']   = $coupon_code;
            $coupon['limit_users']   = $limit_users;
            $coupon['discount']      = $discount;
            $coupon['expire_date']   = $expire_date;
            $coupon['type']   = $type;
            $coupon['restaurant_id']   = $restaurant_id;
            //$id        = $this->Restaurant->getRestaurantID($user_id);

            if(isset($data['id'])){

                $this->RestaurantCoupon->id = $data['id'];
                $this->RestaurantCoupon->save($coupon);
                $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupon( $data['id']);


                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();

            }else{


                if ($this->RestaurantCoupon->isDuplicateRecord($restaurant_id, $coupon_code) == 0) {
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


                    Message::DUPLICATEDATE();
                    die();
                }




            }

        }
    }


    public function promotedRestaurant()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $promoted = $data['promoted'];
            $id = $data['id'];

           $detail =  $this->Restaurant->getRestaurantDetail($id);
            if(count($detail) > 0) {
                $this->Restaurant->id = $id;
                $this->Restaurant->saveField('promoted', $promoted);

                $output['code'] = 200;

                $output['msg'] = "updated";
                echo json_encode($output);


                die();
            }else{

                echo Message::EmptyDATA();
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
            // $id        = $this->Restaurant->getRestaurantID($user_id);
            $id        = $user_id;

            // CakeLog::write('debug', '$id: '.print_r($id, TRUE));

            if (count($id) > 0) {
                // $restaurant_id = $id[0]['Restaurant']['id'];


                if ($this->RestaurantCoupon->deleteCoupon($user_id, $coupon_id)) {

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
            // $id        = $this->Restaurant->getRestaurantID($user_id);
            $id        = $user_id;

            if (count($id) > 0) {
                // $restaurant_id = $id[0]['Restaurant']['id'];

                $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupons($id);


                $output['code'] = 200;

                $output['msg'] = $coupon_detail;


                $json_return = json_encode($output);

                // CakeLog::write('debug', '$json_return: '.print_r($json_return, TRUE));

                echo $json_return;


                die();
            }else{


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showTablesCount()
    {

        $this->loadModel("Currency");
        $this->loadModel("AppSlider");
        $this->loadModel("WebSlider");
        $this->loadModel("Tax");
        $this->loadModel("Restaurant");
        $this->loadModel("UserAdmin");
        $this->loadModel("User");
        $this->loadModel("RiderRequest");
        $this->loadModel("Transaction");
        $this->loadModel("RestaurantRequest");
        $this->loadModel("Category");

        if ($this->request->isPost()) {




            $count=array();
            $count['currency_count'] = $this->Currency->getCurrenciesCount();
            $count['app_sliders_count'] = $this->AppSlider->getAppSlidersCount();
            $count['web_sliders_count'] = $this->WebSlider->getWebSlidersCount();
            $count['taxes_count'] = $this->Tax->getTaxesCount();
            $count['restaurant_count'] = $this->Restaurant->getRestaurantCount();
            $count['stores_count'] = $this->Restaurant->getStoreCount();
            $count['user_admin_count'] = $this->UserAdmin->getUserAdminCount();
            $count['user_count'] = $this->User->getUsersCount("user");
            $count['rider_count'] = $this->User->getUsersCount("rider");
            $count['rider_request_count'] = $this->RiderRequest->getCount();
            $count['transaction_count'] = $this->Transaction->getCount();
            $count['restaurant_request_count'] = $this->RestaurantRequest->getCount();
            $count['category_count'] = $this->Category->getCategoriesCount();

            $output['code'] = 200;

            $output['msg'] = $count;
            echo json_encode($output);


            die();





        }
    }
    public function showRestaurantCouponWhoseRestaurantIDisZero()
    {

        $this->loadModel("RestaurantCoupon");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $restaurant_id = $data['restaurant_id'];


            $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupons($restaurant_id);

            if (count($coupon_detail) > 0) {
                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();
            } else {


                Message::EMPTYDATA();
                die();
            }
        }
    }
    public function addOpenShift()
    {


        $this->loadModel('OpenShift');
        $this->loadModel('User');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $openshift['date'] = $data['date'];
            $openshift['starting_time'] = $data['starting_time'];
            $openshift['ending_time'] = $data['ending_time'];
            $openshift['created'] = date('Y-m-d H:i:s', time());


            if(isset($data['id'])){

                $id = $data['id'];
                $this->OpenShift->id = $id;
                $this->OpenShift->save($openshift);

            }else if ($this->OpenShift->save($openshift)) {

                $id = $this->OpenShift->getLastInsertId();

                $riders = $this->User->getAllRiders();
                foreach ($riders as $rider) {

                    $device_token = $rider['UserInfo']['device_token'];





                    if (strlen($device_token) > 10) {

                        /************notification*************/


                        $notification['to'] = $device_token;
                        $notification['notification']['title'] = "Open shift available now";
                        $notification['notification']['body'] = 'Tap to Book your shift';
                        $notification['notification']['badge'] = "1";
                                $notification['notification']['sound'] = "default";
                        $notification['notification']['icon'] = "";
                        $notification['notification']['type'] = "";
                        $notification['notification']['data']= "";

                        PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                        //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                        /********end notification***************/




                    }

                }

            }



            $open_Shift_detail = $this->OpenShift->getOpenShiftDetail($id);


            $output['code'] = 200;

            $output['msg'] = $open_Shift_detail;
            echo json_encode($output);


            die();


        }
    }

    public function showOpenShifts()
    {

        $this->loadModel("OpenShift");


        if ($this->request->isPost()) {


            $shifts = $this->OpenShift->getOpenShifts();


            $output['code'] = 200;

            $output['msg'] = $shifts;
            echo json_encode($output);


            die();
        }
    }

    public function deleteOpenShift()
    {


        $this->loadModel('OpenShift');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            if($this->OpenShift->delete($id)){

                Message::DELETEDSUCCESSFULLY();
                die();

            }else{

                Message::ERROR();
                die();
            }







        }
    }


    public function showAlltaxes()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {


            $taxes = $this->Tax->getTaxes();


            $output['code'] = 200;

            $output['msg'] = $taxes;
            echo json_encode($output);


            die();
        }
    }

    public function chat()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $chat = array();
            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $sender_id = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $message = $data['message'];
            $datetime = $data['created'];

            $chat['sender_id'] = $sender_id;
            $chat['receiver_id'] = $receiver_id;
            $chat['message'] = $message;
            $chat['created'] = $datetime;
            $isChatExist = $this->Chat->getUserChat($sender_id, $receiver_id);

            if (count($isChatExist) > 0) {

                $id = $isChatExist[0]['Chat']['conversation_id'];

                $chat['conversation_id'] = $id;
                if ($this->Chat->save($chat)) {
                    Message::DATASUCCESSFULLYSAVED();

                }

            } else {

                if ($this->Chat->save($chat)) {
                    $id = $this->Chat->getInsertID();
                    $this->Chat->id = $id;
                    $conversation['conversation_id'] = $id;
                    if ($this->Chat->save($conversation)) {
                        Message::DATASUCCESSFULLYSAVED();

                    }
                }

            }
            /*  if($this->Chat->save($chat)){

            Message::DATASUCCESSFULLYSAVED();

            }else{

            echo Message::DATASAVEERROR();

            }*/
        }
    }


    public function updateVerificationDocumentStatus()
    {


        $this->loadModel('VerificationDocument');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $doc['status'] = $data['status'];


            $this->VerificationDocument->id = $id;
            if ($this->VerificationDocument->save($doc)) {
                $result = $this->VerificationDocument->getDocumentDetail($id);


                $output['code'] = 200;
                $output['msg'] = $result;
                echo json_encode($output);

                die();
            } else {


                echo Message::DATASAVEERROR();
                die();
            }


        }
    }

    public function showAllUserVerificationDocuments()
    {

        $this->loadModel("VerificationDocument");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id = $data['user_id'];

            $docs = $this->VerificationDocument->getDocuments($user_id);


            $output['code'] = 200;

            $output['msg'] = $docs;
            echo json_encode($output);


            die();
        }
    }

    public function getConversation()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $sender_id = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $user_id = $data['user_id'];
            $userMessage = $this->Chat->getUserChat($sender_id, $receiver_id);


            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id'] = $userMessage[$i]['Chat']['id'];
                $message[$i]['Chat']['sender_id'] = $userMessage[$i]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id'] = $userMessage[$i]['Chat']['receiver_id'];
                $message[$i]['Chat']['message'] = $userMessage[$i]['Chat']['message'];
                $message[$i]['Chat']['created'] = $userMessage[$i]['Chat']['created'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i]['Chat']['conversation_id'];


                if ($userMessage[$i]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i]['sender_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i]['sender_info']['profile_img'];


                } else if ($userMessage[$i]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i]['receiver_info']['last_name'];
                    //  $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $message;
            echo json_encode($output);


        }

    }

    public function chatInbox()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];


            $userMessage = $this->Chat->showUserInbox($user_id);
            //print_r($userMessage);
            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id'] = $userMessage[$i][0]['Chat']['id'];
                $message[$i]['Chat']['sender_id'] = $userMessage[$i][0]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id'] = $userMessage[$i][0]['Chat']['receiver_id'];
                $message[$i]['Chat']['message'] = $userMessage[$i][0]['Chat']['message'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i][0]['Chat']['conversation_id'];
                $message[$i]['Chat']['created'] = $userMessage[$i][0]['Chat']['created'];


                if ($userMessage[$i][0]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i][0]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i][0]['sender_info']['last_name'];
                    //  $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i][0]['sender_info']['profile_img'];


                } else if ($userMessage[$i][0]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i][0]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i][0]['receiver_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i][0]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }

            // debug($this->User->lastQuery());

            $output = array();
            $output['code'] = 200;
            $output['msg'] = $message;
            echo json_encode($output);


        }
    }

    public function showCountries()
    {

        $this->loadModel("Currency");
        $this->loadModel("Tax");


        if ($this->request->isPost()) {


            $countries = $this->Currency->getCountries();

            $cities = $this->Tax->getCities();
            $states = $this->Tax->getStates();

            $output['code'] = 200;

            $output['msg']['countries'] = $countries;
            $output['msg']['cities'] = $cities;
            $output['msg']['states'] = $states;


            echo json_encode($output);


            die();
        }
    }


    public function showUsersCount()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $user_count = $this->User->getUsersCount('user');
            $rider_count = $this->User->getUsersCount('rider');
            $hotel_count = $this->User->getUsersCount('hotel');
            $total_users_count = $this->User->getTotalUsersCount();

            $msg['user_count'] = $user_count;
            $msg['rider_count'] = $rider_count;
            $msg['hotel_count'] = $hotel_count;
            $msg['total_users_count'] = $total_users_count;


            $output['code'] = 200;

            $output['msg'] = $msg;
            echo json_encode($output);


            die();
        }
    }

    public function updateRiderOnlineStatus()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $online = 0;
            $this->UserInfo->id = $user_id;
            if ($this->UserInfo->saveField('online', $online)) {


                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else {

                echo Message::ERROR();

                die();

            }

        }


    }


    public function sendPushNotification()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $users = $data['users'];
            $txt = $data['txt'];

            if($users == "all"){

              $users =  $this->UserInfo->getAll();

            }else if(isset($data['role'])){
                $role = $data['role'];


                $users =  $this->UserInfo->getUsersBasedOnRole($role);

            }

          if(count($users) > 0){


                foreach ($users as $user){


                    $device_token = $user['UserInfo']['device_token'];

                    if(strlen($device_token) > 15){


                        $notification['to'] = $device_token;
                        $notification['notification']['title'] = "";
                        $notification['notification']['body'] = $txt;
                        $notification['notification']['badge'] = "1";
                                $notification['notification']['sound'] = "default";
                        $notification['notification']['icon'] = "";



                        $notification['data']['title']="";
                        $notification['data']['body'] = $txt;

                        $notification['data']['icon'] = "";
                        $notification['data']['badge'] = "1";
                        $notification['data']['sound'] = "default";


                        $result = PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));




                    }
                }
          }

            $output['code'] = 200;

            $output['msg'] = "sucessfully sent";
            echo json_encode($output);


            die();

        }


    }

    public function userBlockStatus()
    {

        $this->loadModel("User");
        $this->loadModel("UserInfo");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];


            if(isset($data['active'])){
                $active = $data['active'];
                if($active  == 2){


              /*********************** check if user is a restaurant then delete restaurant as well*******************/


                    $restaurant_detail = $this->Restaurant->isRestaurantExist($user_id);
                    if(count($restaurant_detail) > 0) {

                        $this->Restaurant->deleteRestaurant($restaurant_detail['Restaurant']['id']);
                    }

                    /*********************** end delete restaurant*******************/


                    $this->User->id = $user_id;
                    $this->User->delete();

                    $this->UserInfo->id = $user_id;
                    $this->UserInfo->delete();


                    $save = true;

                }else {
                    $this->User->id = $user_id;
                    $this->User->saveField('active', $active);
                    $save = true;

                }

            }

            if(isset($data['block'])){

                $block = $data['block'];
                $this->User->id = $user_id;
                $this->User->saveField('block', $block);
                $save = true;

            }

            if ($save) {


                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else {

                echo Message::ERROR();

                die();

            }

        }


    }



	public function showUserStores(){

		$this->loadModel('Store');





		if ($this->request->isPost()) {
			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);

			$user_id = $data['user_id'];




			$stores = $this->Store->getUserStores($user_id);








			$output['code'] = 200;

			$output['msg'] = $stores;


			echo json_encode($output);


			die();


		}


	}

	public function showStoreDetail(){

		$this->loadModel('Store');





		if ($this->request->isPost()) {
			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);

			$store_id = $data['store_id'];




			$store_detail = $this->Store->getDetails($store_id);








			$output['code'] = 200;

			$output['msg'] = $store_detail;


			echo json_encode($output);


			die();


		}


	}


	public function showCategoriesBasedOnStores(){

		$this->loadModel('StoreCategory');





		if ($this->request->isPost()) {
			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);


			$store_id = $data['store_id'];



			$categories = $this->StoreCategory->getCategoriesAgainstStore($store_id);








			$output['code'] = 200;

			$output['msg'] = $categories;


			echo json_encode($output);


			die();


		}


	}

	public function addStoreCategory()
	{



		$this->loadModel('StoreCategory');

		if ($this->request->isPost()) {


			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);

			$cat['name'] =  $data['name'];
			// $cat['store_id'] =  $data['store_id'];

			$cat['description'] =  $data['description'];
			$cat['level'] = $data['level'];

			if(isset($data['id'])){

				$id = $data['id'];



				if (isset($data['image'])) {

					$details =  $this->StoreCategory->getDetails($id);
					$image_db = $details['Category']['image'];
					if (strlen($image_db) > 5) {
						@unlink($image_db);

					}

					$image = $data['image'];
					$folder_url = UPLOADS_FOLDER_CATEGORY_URI;

					$filePath = Utility::uploadFileintoFolder($id, $image, $folder_url);
					$cat['image'] = $filePath;



				}



				$this->StoreCategory->id = $id;
				$this->StoreCategory->save($cat);
				$details =  $this->StoreCategory->getDetails($id);


				$output['code'] = 200;
				$output['msg'] = $details;
				echo json_encode($output);
				die();

			}else {

				if (isset($data['image'])) {

					$image = $data['image'];
					$folder_url = UPLOADS_FOLDER_CATEGORY_URI;

					$filePath = Utility::uploadFileintoFolder(1, $image, $folder_url);
					$cat['image'] = $filePath;


				}


				$this->StoreCategory->save($cat);
				$id = $this->StoreCategory->getInsertID();
				$details = $this->StoreCategory->getDetails($id);

				$output['code'] = 200;
				$output['msg'] = $details;
				echo json_encode($output);
				die();


			}

		}




	}

	public function showStoreCategories(){

		$this->loadModel('Category');

		if ($this->request->isPost()) {
			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);

			$categories = $this->Category->getAll();
			if(isset($data['category_id'])){

				$categories = $this->StoreCategory->getDetails($data['category_id']);
			}

			if(isset($data['level'])){

				$categories = $this->StoreCategory->getCategoriesAgainstLevel($data['level']);
			}






			$output['code'] = 200;

			$output['msg'] = $categories;



			echo json_encode($output);


			die();


		}


	}

	public function deleteStoreCategory(){

		$this->loadModel('Category');





		if ($this->request->isPost()) {
			$json = file_get_contents('php://input');
			$data = json_decode($json, TRUE);

			$category_id = $data['category_id'];



			$this->Category->id = $category_id;
			$this->Category->saveField('active',0);





			$output['code'] = 200;

			$output['msg'] = "deleted";


			echo json_encode($output);


			die();


		}


	}
	
	 public function allBooking()
    {

        $this->loadModel('BookingTime');
        
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $allBooking = $this->BookingTime->getBookingAllTime();
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $allBooking;
            echo json_encode($output);
            
        }
    }

    public function createBookingTime()
    {
        $this->loadModel('BookingTime');
       
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $booking_time = $data['booking_time'];
            $status_id = $data['status_id'];
            $booking_id = $data['booking_id'];
            
            if ($booking_time != null && $status_id != null && $booking_id == null) {


                $array['booking_time'] = $booking_time;
                $array['b_status'] = $status_id;

                $array['created_date'] = date('Y-m-d', time());


                $count = $this->BookingTime->isTimeAlreadyExist($booking_time);


                if ($count && $count > 0) {
                    echo "Booking Time already Exist.";
                    die();

                } else {

                   
                    if (!$this->BookingTime->save($array)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $id = $this->BookingTime->getInsertID();
                    
                    $output = array();
                    $output['code'] = 200;
                     $output['msg'] = 'Save Successfully';
                    echo json_encode($output);


                }
            }else if ($booking_time != null && $status_id != null && $booking_id != null) {


                $array['id'] = $booking_id;
                $array['booking_time'] = $booking_time;
                $array['b_status'] = $status_id;

                $array['created_date'] = date('Y-m-d', time());


                $count = $this->BookingTime->isTimeAlreadyExist($booking_time, $booking_id);


                if ($count && $count > 0) {
                    echo "Booking Time already Exist.";
                    die();

                } else {

                   
                    if (!$this->BookingTime->save($array)) {
                        echo "Booking Time Update error";
                        die();
                    }
                    
                    $output = array();
                    $output['code'] = 200;
                    $output['msg'] = '';
                    echo json_encode($output);


                }
            }else {
                echo Message::ERROR();
            }
        }
    }

    public function showBookingTime()
    {
        $this->loadModel('BookingTime');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $bookingData = $this->BookingTime->getBooking($id);
            if(!empty($bookingData))
            {
                    $output = array();
                    $output['code'] = 200;
                    $output['msg'] = $bookingData;
                    echo json_encode($output);
            }
            else {
                echo Message::ERROR();
            }
        }
    }
    
    public function bookingTimeList()
    {

        $this->loadModel('BookingTime');
        
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $allBooking = $this->BookingTime->bookingList();
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $allBooking;
            echo json_encode($output);
            
        }
    }
    
    public function getAllResBookingTime()
    {
        $this->loadModel('DeliveryBookTime');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['restaurant_id'];
            
            $bookingData = $this->DeliveryBookTime->getAllResBookingTime($id);
            if(!empty($bookingData))
            {
                    $output = array();
                    $output['code'] = 200;
                    $output['msg'] = $bookingData;
                    echo json_encode($output);
            }else{
                $headings = array('saturday','sunday','monday','tuesday','wednesday','thursday','friday');
                
                foreach($headings as $head)
                {
                    $array['restaurant_id'] = $id;
                    $array['booking_day'] = $head;
                    $array['day_status'] = 0;
                    $array['booking_time_id'] = "";
                    
                    if (!$this->DeliveryBookTime->saveall($array)) {
                        echo "Booking Availablity Information error";
                        die();
                    }
                }
                
                $bookingData = $this->DeliveryBookTime->getAllResBookingTime($id);
                    
                $output = array();
                $output['code'] = 200;
                $output['msg'] = $bookingData;
                echo json_encode($output);
            }
        }
    }
    
    public function updateDeliveryTime()
    {
        $this->loadModel('DeliveryBookTime');

        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

           
            
            $this->DeliveryBookTime->id = $data['delivery_book_id'];
			$this->DeliveryBookTime->saveField('day_status',$data['day_status']);
			$this->DeliveryBookTime->saveField('booking_time_id',$data['booking_time_id']);
			
           
            $output = array();
            $output['code'] = 200;
            $output['msg'] = '';
           // $output['msg'] = $array;
            echo json_encode($output);
            
        }
    }
    
    public function bookingStatusUpdate()
    {
        $this->loadModel('Restaurant');

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
            $this->Restaurant->id = $data['restaurant_id'];
			$this->Restaurant->saveField('booking_available',$data['booking_available']);
			
            $output = array();
            $output['code'] = 200;
            $output['msg'] = '';
           // $output['msg'] = $array;
            echo json_encode($output);
            
        }
    }
    
    public function getAllOrdersRejectRestaurant()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
           
            $orders = $this->Order->getAllOrdersRejectRestaurant();
          
            foreach ($orders as $key => $val) {

                $rider_details =  $this->RiderOrder->getRiderDetailsAgainstOrderID($val['Order']['id']);

                if(count($rider_details) > 0){




                    $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($val['Order']['id']);
                    $pickup_time             = $this->RiderTrackOrder->isEmptyPickUpTime($val['Order']['id']);
                    $on_my_way_to_user_time  = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($val['Order']['id']);
                    $delivery_time           = $this->RiderTrackOrder->isEmptyDeliveryTime($val['Order']['id']);

                    if ($on_my_way_to_hotel_time == 1 && $pickup_time == 0 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {



                        $msg = "On the way to restaurant";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {




                        $msg = "Order collected from restaurant";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 0) {




                        $msg = "On the way to customer ";

                    } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 1) {




                        $msg = "Delivered";

                    } else  if($rider_details[0]['RiderOrder']['accept_reject_status'] == 1){


                        $msg = "order has been accepted by the rider";
                    }else if($rider_details[0]['RiderOrder']['accept_reject_status'] == 0){


                        $msg = "Waiting for the rider to accept the order";
                    }


                    $orders[$key]['Order']['RiderOrder']= $rider_details[0]['RiderOrder'];
                    $orders[$key]['Order']['RiderOrder']['order_status'] = $msg;
                    $orders[$key]['Order']['RiderOrder']['Rider']= $rider_details[0]['Rider'];
                    $orders[$key]['Order']['RiderOrder']['Assigner']= $rider_details[0]['Assigner'];


                }else{

                    $orders[$key]['Order']['RiderOrder']= array();

                }
            }


            $output['code'] = 200;

            $output['msg'] =  $orders;
            echo json_encode($output);


            die();
        }
    }
    
    
    public function showStores()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {


            $restaurants = $this->Restaurant->getAllStores();

            $i = 0;
            foreach ($restaurants as $rest) {
                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }
                $i++;

            }
            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();
        }
    }
    
    public function stoerProducts()
    {
       // $this->loadModel("Restaurant");
        $this->loadModel("Product");
        
        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
            $store_id = $data['store_id'];
            
            $products = $this->Product->getAllProductByStore($store_id);
          
                $output['code'] = 200;
                $output['msg'] =  $products;
                echo json_encode($output);
           
            die();
        }else{
            $output['code'] = 202;
              echo  $output['msg'] =  false;
              echo json_encode($output);
                die();
        }
    }
    
    
    public function storeProductsFromRes()
    {
       // $this->loadModel("Restaurant");
        $this->loadModel("Product");
        $this->loadModel("Category");
        $this->loadModel("RestaurantMenu");
        $this->loadModel('RestaurantMenuItem');
        
        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
            $store_id = $data['store_id'];
            
            //$products = $this->Product->getAllProductByStore($store_id);
            $products = $this->Product->getStoreItems($store_id);
          
                $output['code'] = 200;
                $output['msg'] =  $products;
                echo json_encode($output);
           
            die();
        }else{
            $output['code'] = 202;
              echo  $output['msg'] =  false;
              echo json_encode($output);
                die();
        }
    }
    
    public function addProduct()
    {
        $this->loadModel("Product");
        $this->loadModel("Category");
        $this->loadModel("RestaurantMenu");
        $this->loadModel('RestaurantMenuItem');
        
         if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
           
            
            $item_id            = $data['id'];
            $category_id            = $data['category_id'];
            $restaurant_id          = $data['store_id'];
            $cat                    = $this->Category->getCategoryDetail($category_id);
            
            $name                   = $data['product_title'];
            $description            = $data['product_description'];
            $price                  = $data['price'];
            $sale_price             = $data['sale_price'];
            $user_id                = $data['userid'];
            $out_of_order       = '';
            $created                = date('Y-m-d H:i:s', time());
            
            
            if($item_id)
            {
                 $menu = $this->RestaurantMenu->find('all', array(
                        'conditions'    => array(
                                'RestaurantMenu.restaurant_id'  => $restaurant_id,
                                'RestaurantMenu.category_id'    => $category_id
                            )
                    ));
                if(!empty($menu))
                {
                    $restaurant_menu_id = $menu[0]['RestaurantMenu']['id'];
                }else{
                     $category_name           = $cat[0]['Category']['category'];
                     $category_description    = $cat[0]['Category']['category'];
                    
                    $restaurant_menu['name']          = $category_name;
                    $restaurant_menu['category_id']   = $category_id;
                    $restaurant_menu['description']   = $category_description;
                    $restaurant_menu['created']       = date('Y-m-d H:i:s', time());
                    $restaurant_menu['restaurant_id'] = $restaurant_id;
                    $restaurant_menu['active']        = 1;
                    $restaurant_menu['has_menu_item'] = 1;
                    $restaurant_menu['index']         = 0;
                    
                    if ($this->RestaurantMenu->save($restaurant_menu)) {
    
                        $restaurant_menu_id   = $this->RestaurantMenu->getLastInsertId();
                   }
                }
                
                $restaurant_menu_item['id']                 = $item_id;
                $restaurant_menu_item['name']               = $name;
                $restaurant_menu_item['description']        = $description;
                $restaurant_menu_item['restaurant_menu_id'] = $restaurant_menu_id;
                $restaurant_menu_item['image']              = '';
                $restaurant_menu_item['price']              = $price;
                $restaurant_menu_item['p_price']            = $sale_price;
                $restaurant_menu_item['created']            = $created;
                $restaurant_menu_item['out_of_order']       = $out_of_order;
                $this->RestaurantMenuItem->save($restaurant_menu_item);
                
                if($item_id)
                {
                    $output['code'] = 200;
                    $output['msg'] =  $restaurant_menu_item;
                    echo json_encode($output);
                }else{
                    $output['code'] = 201;
                    $output['msg'] =  'Updating data error';
                    echo json_encode($output);
                }
                
            }else{
                
                $menu = $this->RestaurantMenu->find('all', array(
                    'conditions'    => array(
                            'RestaurantMenu.restaurant_id'  => $restaurant_id,
                            'RestaurantMenu.category_id'    => $category_id
                        )
                ));
                
               // $restaurant_menu_id = $menu[0]['RestaurantMenu']['id'];
                //  $output['code'] = 200;
                //     $output['msg'] =  $cat;
                //     echo json_encode($output);
                // exit;
                if(!empty($menu))
                {
                    $restaurant_menu_id = $menu[0]['RestaurantMenu']['id'];
                }else{
                     $category_name           = $cat[0]['Category']['category'];
                     $category_description    = $cat[0]['Category']['category'];
                    
                    $restaurant_menu['name']          = $category_name;
                    $restaurant_menu['category_id']   = $category_id;
                    $restaurant_menu['description']   = $category_description;
                    $restaurant_menu['created']       = date('Y-m-d H:i:s', time());
                    $restaurant_menu['restaurant_id'] = $restaurant_id;
                    $restaurant_menu['active']        = 1;
                    $restaurant_menu['has_menu_item'] = 1;
                    $restaurant_menu['index']         = 0;
                    
                    if ($this->RestaurantMenu->save($restaurant_menu)) {
    
                        $restaurant_menu_id   = $this->RestaurantMenu->getLastInsertId();
                    //       $output['code'] = 200;
                    //     $output['msg'] =  $cat;
                    //     echo json_encode($output);
                    // exit;
                        //$menu = $this->RestaurantMenu->getMainMenuFromID($restaurant_menu_id);
                    }
                }
            
            
                $restaurant_menu_item['name']               = $name;
                $restaurant_menu_item['description']        = $description;
                $restaurant_menu_item['restaurant_menu_id'] = $restaurant_menu_id;
                $restaurant_menu_item['image']              = '';
                $restaurant_menu_item['price']              = $price;
                $restaurant_menu_item['p_price']            = $sale_price;
                $restaurant_menu_item['created']            = $created;
                $restaurant_menu_item['out_of_order']       = $out_of_order;
                $this->RestaurantMenuItem->save($restaurant_menu_item);
                $id   = $this->RestaurantMenuItem->getLastInsertId();
                    
                
              
                if($id)
                {
                    $output['code'] = 200;
                    $output['msg'] =  $restaurant_menu_item;
                    echo json_encode($output);
                }else{
                    $output['code'] = 201;
                    $output['msg'] =  'Saving data error';
                    echo json_encode($output);
                }
            }
                
           
            die();
        }
    }
    
    public function getProduct()
    {
        $this->loadModel("Product");
        $this->loadModel("RestaurantMenu");
        $this->loadModel('RestaurantMenuItem');
        
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
            $id        = $data['id'];
            $store_id  = $data['store_id'];
           
            
           $products = $this->Product->getStoreEachItems($store_id, $id);
          
                
               
            if(count($products)>0)
            {
                $output['code'] = 200;
                $output['msg'] =  $products;
                echo json_encode($output);
            }else{
                $output['code'] = 201;
                $output['msg'] =  '';
                echo json_encode($output);
            }
                
           
            die();
        }
    }
    
    public function deletedProduct()
    {
        $this->loadModel("Product");
        $this->loadModel("RestaurantMenu");
        $this->loadModel('RestaurantMenuItem');
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            
            //$this->Product->id = $data['id'];
			//$this->Product->saveField('p_status',$data['deleted']);
            // $resItem = $this->RestaurantMenuItem->find('all', array(
            //         'conditions'    => array(
            //             'RestaurantMenuItem.id' => $data['id']  
            //         )
            //     )
            // );
            // $resId = $resItem[0]['RestaurantMenuItem']['restaurant_menu_id'];
            $this->RestaurantMenuItem->id = $data['id'];
            $this->RestaurantMenuItem->delete();
            
            // $this->RestaurantMenu->id = $resId;
            // $this->RestaurantMenu->delete();
            
            $output['code'] = 200;
            $output['msg'] = $resItem;
           // $output['msg'] = $array;
            echo json_encode($output);
        }
    }
    
    public function showCategoriesNew(){

        $this->loadModel('Category');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $categories = $this->Category->getAll();
            // $output['code'] = 200;

            // $output['msg'] = $categories;



            // echo json_encode($output);


            // die();
            if(isset($data['category_id'])){

                $categories = $this->Category->getDetails($data['category_id']);
            }

            if(isset($data['level'])){

                $categories = $this->Category->getCategoriesAgainstLevel($data['level']);
            }

            $output['code'] = 200;

            $output['msg'] = $categories;



            echo json_encode($output);


            die();


        }


    }
    
    public function addCategoryNew()
    {

        $this->loadModel('Category');
        $this->loadModel('RestaurantMenu');

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $cat['name'] =  $data['name'];
            $cat['category'] =  $data['name'];
           // $cat['store_id'] =  $data['store_id'];

            $cat['description'] =  $data['description'];
            $cat['level'] = $data['level'];

            if(isset($data['id'])){

                $id = $data['id'];

                if (isset($data['image'])) {

                    $details =  $this->Category->getDetails($id);
                    $image_db = $details['Category']['image'];
                    if (strlen($image_db) > 5) {
                        @unlink($image_db);

                    }

                    $image = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                   // $filePath = Utility::uploadFileintoFolder($id, $image, $folder_url);
                    $filePath = Lib::uploadFileintoFolder($id, $image, $folder_url);
                    $cat['image'] = $filePath;
                    $cat['icon'] = $filePath;



                }
                
                $res = $this->RestaurantMenu->find('all', array(
                    'conditions' => array(
                            'RestaurantMenu.category_id'    => $id
                        )
                ));
                
                if(!empty($res))
                {
                    foreach($res as $re)
                    {
                        $qw['name'] = $data['name'];
                        $qw['description'] = $data['name'];
                        
                        $this->RestaurantMenu->id = $re['RestaurantMenu']['id'];
                        $this->RestaurantMenu->save($qw);       
                        
                        // $this->RestaurantMenu->id = $re['RestaurantMenu']['id'];
                        // $this->RestaurantMenu->saveField('description', $data['description']);       
                             
                    }
                }

                $this->Category->id = $id;
                $this->Category->save($cat);
                $details =  $this->Category->getDetails($id);


                $output['code'] = 200;
                $output['msg'] = $details;
                echo json_encode($output);
                die();

            }else {

                if (isset($data['image'])) {
                    $image = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                   // $filePath = Utility::uploadFileintoFolder(1, $image, $folder_url);
                    $filePath = Lib::uploadFileintoFolder(1, $image, $folder_url);
                    $cat['image'] = $filePath;
                    $cat['icon'] = $filePath;


                }


                $this->Category->save($cat);
                $id = $this->Category->getInsertID();
                $details = $this->Category->getDetails($id);

                $output['code'] = 200;
                $output['msg'] = $details;
                echo json_encode($output);
                die();

            }

        }
    }
    
    public function deleteCategoryNew()
    {
        $this->loadModel('Category');
        $this->loadModel('RestaurantMenu');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $category_id = $data['category_id'];
            
             $res = $this->RestaurantMenu->find('all', array(
                    'conditions' => array(
                            'RestaurantMenu.category_id'    => $category_id
                        )
            ));
            
            if(!empty($res))
            {
                foreach($res as $re)
                {
                    $this->RestaurantMenu->id = $re['RestaurantMenu']['id'];
                    $this->RestaurantMenu->saveField('status_cat',0);       
                }
            }
            
            //    where('category_id',$category_id);
             
            
            $this->Category->id = $category_id;
            $this->Category->saveField('active',0);
            
           

            $output['code'] = 200;

            $output['msg'] = "deleted";


            echo json_encode($output);


            die();


        }
    }
    
    public function showCategoriesStore()
    {

        $this->loadModel("Category");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');


            $categories = $this->Category->getAllCategoryStore();


            $output['code'] = 200;

            $output['msg'] = $categories;
            echo json_encode($output);


            die();
        }
    }
    
}
?>
