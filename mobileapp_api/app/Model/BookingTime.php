<?php



class BookingTime extends AppModel
{

    public $useTable = 'booking_time';


    public function getBookingAllTime()
    {
        return $this->find('all');
    }
    
    public function bookingList()
    {
        return $this->find('all', array(
                'conditions' => array('b_status' => 1)
        ));
    }

    public function isTimeAlreadyExist($booking_time, $booking_id){
        if($booking_id)
        {
            return $this->find('count', array(
                'conditions' => array('booking_time' => $booking_time, 
                    'id !=' => $booking_id)
            ));
        }else{
            return $this->find('count', array(
                'conditions' => array('booking_time' => $booking_time)
            ));
        }
        

    }
    
    public function getBooking($id){

        return $this->find('first', array(
            'conditions' => array('BookingTime.id' => $id)
        ));

    }
    //   'BookingTime.id IN' => '('.$booking_id.')')
    public function getTimeData($booking_id)
    {
        return $this->find('all', array(
            'conditions' => array('BookingTime.id' => $booking_id)
        ));
        
    }

    
    
}


?>