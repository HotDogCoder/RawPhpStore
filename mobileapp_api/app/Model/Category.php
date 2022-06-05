<?php

class Category extends AppModel
{
    public $useTable = 'category';

    
    public function getAll()
    {
        //$this->Behaviors->attach('Containable');
        return $this->find('all',array(
            'order' => array('Category.category ASC'),
            'conditions' => array('Category.active' => 1),
            'recursive' => -1
        ));

    }
    
    public function getDetails($id)
    {

        return $this->find('first', array(
            'conditions' => array('Category.id' => $id)
        ));

    }
    
    public function getCategoriesAgainstLevel($level)
    {
        //$this->Behaviors->attach('Containable');
        return $this->find('all',array(

            //'contain' => array('Product.ProductImage','Product.Store.StoreLocation.Country'),
            'order' => array('Category.category ASC'),
            'conditions' => array(
                'Category.active' => 1,
                'Category.level' => $level

            ),
            'recursive' => -1
        ));

    }
    
    public function getAllCategory()
    {
        

        // return $this->find('all', array(
        //     'joins' => array(
        //         array(
        //             'table' => 'category',
        //             'alias' => 'CategoryD',
        //             'type' => 'LEFT',
        //             'conditions' => array(
        //                 'CategoryD.parent_id = Category.id',
        //             ),
        //         ),
        //     ),
        //         'order' => 'Category.category ASC'
        //     ));
            
            $query = "SELECT 
                            Category.id, 
                            Category.parent_id, 
                            Category.category, 
                            Category.icon, 
                            Dcategory.category as parent_name 
                      FROM 
                            category Category LEFT join category Dcategory 
                      ON Category.parent_id = Dcategory.id order by Category.category ASC";
            return $this->query($query);
    }
    

    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Category.category' => $data['category'],
                'Category.icon'=> $data['icon']
            )
        ));
    }
    
     public function getCategoryID($category)
    {
        return $this->find('all', array(
            'conditions' => array(
                'Category.category LIKE'=> "%".$category."%",
            )
        ));
    }


    public function getCategoryDetail($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Category.id' => $id,






            )
        ));
    }


    public function getCategories()
    {
        return $this->find('all');
    }

    public function getCategoriesCount()
    {
        return $this->find('count');
    }



    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['category'])) {


            $category = strtolower($this->data[$this->alias]['category']);
            $this->data['Category']['category'] = ucwords($category);
        
        }

        if(isset($this->data[$this->alias]['icon']) && $this->data[$this->alias]['icon']!="") {
        
            $icon = strtolower($this->data[$this->alias]['icon']);
            $this->data['Category']['icon'] = ucwords($icon);

        }

        return true;
    }
    
    public function showRestaurantsSpecialities()
    {
        $query = "SELECT * FROM 
                            category Category where Category.active=1 and Category.level=0 order by Category.category ASC";
            return $this->query($query);
    }
    
    public function getAllCategoryStore()
    {
        

        // return $this->find('all', array(
        //     'joins' => array(
        //         array(
        //             'table' => 'category',
        //             'alias' => 'CategoryD',
        //             'type' => 'LEFT',
        //             'conditions' => array(
        //                 'CategoryD.parent_id = Category.id',
        //             ),
        //         ),
        //     ),
        //         'order' => 'Category.category ASC'
        //     ));
            
            $query = "SELECT * FROM 
                            category Category where Category.level=0 and Category.active=1 order by Category.category ASC";
            return $this->query($query);
    }
    
    public function getCategoryAll()
    {
         $query = "SELECT * FROM 
                            category Category where Category.active=1 order by Category.category ASC";
            return $this->query($query);
    }
    
}
?>