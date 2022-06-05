<?php

define('APP_NAME', 'weydrop');
define('APP_STATUS', 'demo'); // live or demo
// define('API_KEY', '156c4675-9608-4591-b2ec-427503464aac');
//define('API_KEY', 'AIzaSyB6y3kgq2FGNkoYyzkUcWtURVZMzpmpARw');

define('TEST_MOBILE_NO', '---'); //e.g +13472720544 // no should be with the country code
// define('BASE_URL', 'https://www.weydrop.com/mobileapp_api/'); //example  http://abc.com/mobileapp_api/
define('BASE_URL', 'http://dev.weydrop.com/mobileapp_api/'); //example  http://abc.com/mobileapp_api/
date_default_timezone_set('Asia/Karachi');


define('RADIUS', '35'); //value will be in KM. If you set 1000 it means it will fetch the restaurants which comes in 1000 km radius


define('UPLOADS_FOLDER_URI', 'app/webroot/uploads');
define('VERIFICATION_DOCUMENTS', 'verification_documents');
define('PREP_REGISTRATION_SUBJECT', 'Confirm your Weydrop Registration');
define('VERIFICATION_PHONENO_MESSAGE', 'Your Weydrop verification code is');

//DATABASE
define('DATABASE_USER', 'fahadsaeedahmed_weydrop');
define('DATABASE_PASSWORD', ')2bw@?V6eP0j');
define('DATABASE_NAME', 'fahadsaeedahmed_dev');


//PostMark
define('POSTMARK_SERVER_API_TOKEN', '21cc001b-3b35-43ac-af2c-daed9e8092df');
define('SUPPORT_EMAIL', 'info@weydrop.com');
define('TEST_EMAIL', 'info@weydrop.com'); //test@gmail.com
define('GOOGLE_MAPS_KEY', 'AIzaSyCZja2jZkxh8iRN3cMN30bmIytKfrMnJfE');

//Twilio
define('TWILIO_ACCOUNTSID', 'AC4ef0ee92be16d575410d1a64ff5aacda');
define('TWILIO_AUTHTOKEN', 'cde579d967c8d8a33fb929f84a9f4b57');
define('TWILIO_NUMBER', '+12133195110'); // put the registered number here
// define('TWILIO_NUMBER', 'Weydrop'); // put the registered number here

//Firebase
define('FIREBASE_PUSH_NOTIFICATION_KEY', 'AAAAHBhZQCQ:APA91bGDCmVJsMx1Yrxy6syRhxED5RU9q0hOYxgznw33gPoLxnGpM8n47XlQSlsTuS67Xfn4103XgxoyXUHV6MBAvp53XgkWcqEu8Gbt0_WpkHPMB5PDnlufkJG1J1Xwg6pA7qVtvngO');  

define('FIREBASE_URL', 'https://weydrop-aa960.firebaseio.com/');//https://foodies-abcd.firebaseio.com/


//***************************STRIPE************************//
define('STRIPE_API_KEY','sk_test_RQiRu7OwOWRmdG6YwfumBNIx00ymjNKmqw');

define('STRIPE_CURRENCY', 'usd');

//***************************PAYPAL************************//
define("PAYPAL_CURRENCY", "USD");
define("PAYPAL_CLIENT_ID", "paypal client id here");
define("PAYPAL_CLIENT_SECRET", "cleint secret here");

//***************************END PAYPAL************************//
//Testing


define('DEBUG_VALUE', 2); //0 means no errors will display on the screen. 2 means all the errors






?>


