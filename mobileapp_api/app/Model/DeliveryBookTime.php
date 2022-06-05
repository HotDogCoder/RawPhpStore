<?php



class DeliveryBookTime extends AppModel
{

    public $useTable = 'delivery_book_time';
    public $primayKey = 'delivery_book_id';


    public function getAllResBookingTime($id)
    {
        return $this->find('all', array(
                'conditions' => array('restaurant_id' => $id)
        ));
    }
    
    public function getBookedData($id)
    {
        return $this->find('all', array(
                'conditions' => array('restaurant_id' => $id, 
                'day_status'    => 1
                )
        ));
    }
    
    public function getEachBookedData($id, $day)
    {
        return $this->find('all', array(
                'conditions' => array('restaurant_id' => $id, 
                'day_status'    => 1,
                'booking_day'   => $day
                )
        ));
    }

}


?>