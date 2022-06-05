<?php



class Transaction extends AppModel
{
    public $useTable = 'transaction';

    public $belongsTo = array(

        'Restaurant' => array(
            'className' => 'Restaurant',
            'foreignKey' => 'restaurant_id',


        ),

        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'rider_user_id',


        ),

    );

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(

                'Transaction.id' => $id





            )
        ));
    }


    public function getCount()
    {
        return $this->find('count');






    }

    public function checkDuplicate($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Transaction.amount' => $data['amount'],
                'Transaction.paid_date' => $data['paid_date'],
                'Transaction.pay_via' => $data['pay_via']





            )
        ));
    }

    public function getRestaurantTransaction($restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Transaction.restaurant_id' => $restaurant_id




            ),
            'recursive'=>-1
        ));
    }

    public function getTransactions()
    {
        return $this->find('all');
    }

    public function getAll()
    {
        return $this->find('all');

    }





}