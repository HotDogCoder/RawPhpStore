<?php 


class RestaurantMenu extends AppModel
{

 public $useTable = 'restaurant_menu';

    public $hasMany = array(
        'RestaurantMenuItem' => array(
            'className' => 'RestaurantMenuItem',
            'foreignKey' => 'restaurant_menu_id',
            'dependent'=> true,



        ),
    );

    public function isDuplicateRecord($name,$description,$restaurant_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RestaurantMenu.name'=> $name,
                'RestaurantMenu.description'=> $description,

                'RestaurantMenu.restaurant_id'=> $restaurant_id



            )
        ));
    }

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(




                'RestaurantMenu.id'=> $id



            )
        ));
    }

    public function getMainMenu($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain'=>array('RestaurantMenuItem'),
            'conditions' => array(

               'RestaurantMenu.restaurant_id'=> $restaurant_id



            ),
            'order' => 'RestaurantMenu.index ASC',
        ));
    }

    public function getMainMenuFromID($id)
    {

        return $this->find('all', array(

            'conditions' => array(

                'RestaurantMenu.id'=> $id



            )
        ));
    }


    public function deleteMainMenu($menu_id,$restaurant_id){


        return $this->deleteAll(array(

            'RestaurantMenu.id'=>$menu_id,
        'RestaurantMenu.restaurant_id'=>$restaurant_id

            ),true);

    }








}

    ?>