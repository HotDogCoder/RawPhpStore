<?php



class WebSlider extends AppModel
{

    public $useTable = 'web_slider';


    public function getImages()
    {
        return $this->find('all');


    }


    public function getImageDetail($id)
    {
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'WebSlider.id' => $id


            ),
            ));


    }

    public function getWebSlidersCount()
    {
        return $this->find('count');
    }

    public function deleteWebSlider($id)
    {
        return $this->deleteAll(
            [
                'WebSlider.id' => $id
               
            ],
            false # <- single delete statement please
        );
    }
}

?>