<?php


class RestaurantCategory extends AppModel
{

    public $useTable = 'restaurant_category';

    
    public function isDuplicateRecord($rest_id,$cat_id)
    {
        return $this->find('count', array(
            'conditions' => array(

                'RestaurantCategory.restaurant_id' => $rest_id,

                'RestaurantCategory.category_id'=> $cat_id,




            )
        ));
    }

    public function getRestaurantCategories($restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCategory.restaurant_id' => $restaurant_id

            )
        ));
    }


    public function getRestaurantCategory($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCategory.id' => $id

            )
        ));
    }

    
   
public function deleteCategory($restaurant_id,$id){


    return $this->deleteAll
    (['RestaurantCategory.restaurant_id'=>$restaurant_id,
            'RestaurantCategory.id'=>$id]);

}


}
?>