<?php



class PhoneNoVerification extends AppModel
{
    public $useTable = 'phone_no_verification';

    // public $belongsTo = array(
    //     'User' => array(
    //         'className' => 'User',
    //         'foreignKey' => 'user_id',
    //         'type' => 'RIGHT',
    //         'fields' => array('User.id','User.email','User.active','User.role') //'User.type'
    //     )


    // );

 public function verifyCode($phone_no,$code){

        return $this->find('count', array(
            'conditions' => array(
                 'PhoneNoVerification.phone_no' => $phone_no,
                 'PhoneNoVerification.code' => $code
            )


        ));

   } 


   // getting user_id for which code created
   public function userupdate($phone_no,$code){

          return $this->find('first', array(
              'conditions' => array(
                   'PhoneNoVerification.phone_no' => $phone_no,
                   'PhoneNoVerification.code' => $code,
                   // 'PhoneNoVerification.created >=' => date('Y-m-d H:i:s', strtotime('-1 hour'))
              )


          ));

     } 
}