<?php



class Product extends AppModel
{

    public $useTable = 'product';


    public function getAllProductByStore($store_id)
    {
         return $this->find('all', array(
                'conditions' => array('store_id' => $store_id, 'p_status !=' => 'D')
        ));
        
    //     return $this->find('all',array('fields' => array('Product.*','Category.*'),
    //     'joins'=>array(
    //             array(
    //                 'table'=>'Product',
    //                 'type'=>'inner',
    //                 'conditions'=>array(
    //                     'Product.category_id=Category.id', 
    //                     'store_id' => $store_id)
    //                 )
    //             )
    //         )
    //   );
    
        // return $this->find('All', 
        //     'joins' => array( 'product.category_id = category.id'),
        //     'conditions'    => array(
        //         'store_id'  => $store_id
        //     )
        // );
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

    public function getTimeData($booking_id)
    {
        return $this->find('all', array(
            'conditions' => array('BookingTime.id' => $booking_id)
        ));
        
    }
    
    public function getStoreItems($storeId)
    {
        $query = "SELECT res.id as res_id, res.image,
                    re_i.id as item_id,
                    re_i.name as product_title,
                    re_i.description as description,
                    re_i.price as price,
                    re_i.image as item_image,
                    re_i.created,
                    c.category as category
                FROM 
                restaurant_menu res, 
                restaurant_menu_item re_i, 
                category c 
            WHERE res.restaurant_id=$storeId 
            and re_i.restaurant_menu_id = res.id 
            and res.category_id = c.id";
        return $this->query($query);
    }
   
    public function getStoreEachItems($storeId, $itemId)
    {
        $query = "SELECT res.id as res_id, res.image,
                    re_i.id as item_id,
                    re_i.name as product_title,
                    re_i.description as description,
                    re_i.price as price,
                    re_i.p_price as p_price,
                    re_i.image as item_image,
                    re_i.created,
                    c.category as category,
                    c.id as category_id
                FROM 
                restaurant_menu res, 
                restaurant_menu_item re_i, 
                category c 
            WHERE res.restaurant_id=$storeId 
            and re_i.restaurant_menu_id = res.id 
            and res.category_id = c.id and re_i.id=$itemId";
        return $this->query($query);
    }

    
    
}


?>