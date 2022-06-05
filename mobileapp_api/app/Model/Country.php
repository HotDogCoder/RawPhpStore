<?php


class Country extends AppModel
{
    public $useTable = 'country';


    public function getDetails($id)
    {

        return $this->find('first', array(
            'conditions' => array('Country.id' => $id)
        ));

    }


    public function getCountries()
    {

        return $this->find('all',  array(
            'conditions' => array('Country.active' => 1),


            'order'=>'Country.name ASC',

        ));

    }

    public function getAll()
    {

        return $this->find('all',  array(



            'order'=>'Country.name ASC',

        ));

    }

    public function setDefaultToZero(){

        $this->updateAll(
            array('Country.default' => 0)
        );

    }



}

?>