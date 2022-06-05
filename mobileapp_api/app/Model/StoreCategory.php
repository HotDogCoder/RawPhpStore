<?php


class  StoreCategory extends AppModel
{
    public $useTable = 'store_category';

    public $hasMany = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'category_id',

        ),


    );





    public function getDetails($id)
    {

        return $this->find('first', array(
            'conditions' => array('StoreCategory.id' => $id)
        ));

    }



    public function getAll()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all',array(

            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('StoreCategory.id DESC'),
            'conditions' => array('StoreCategory.active' => 1),
            'recursive' => -1
        ));

    }

    public function getCategoriesAgainstLevel($level)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all',array(

            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('StoreCategory.id DESC'),
            'conditions' => array(
                'StoreCategory.active' => 1,
                'StoreCategory.level' => $level

            ),
            'recursive' => -1
        ));

    }

    public function getAllAgainstStoreID($store_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all',array(

            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('StoreCategory.id DESC'),
            'conditions' => array(
                'StoreCategory.active' => 1,
                'StoreCategory.store_id' => $store_id),
            'recursive' => -1
        ));

    }


    public function getFeaturedCategories()
    {
        $this->Behaviors->attach('Containable');


        return $this->find('all',array(
            'conditions' => array(
                'StoreCategory.featured' => 1,
                'StoreCategory.active' => 1),
            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('StoreCategory.id ASC'),
        ));

    }

    public function getFeaturedCategoriesAgainstStore($store_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all',array(
            'conditions' => array(
                'StoreCategory.featured' => 1,
                'StoreCategory.active' => 1,
                'StoreCategory.store_id' =>$store_id


                ),
            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),

            'order' => array('StoreCategory.id ASC'),
        ));

    }


    public function getCategoriesAgainstStore($store_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all',array(
            'conditions' => array(
                'StoreCategory.store_id' => $store_id,
                'StoreCategory.active' => 1

            ),
            'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('StoreCategory.id ASC'),
        ));

    }













}

?>
