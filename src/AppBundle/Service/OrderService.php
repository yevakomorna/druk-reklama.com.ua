<?php
namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;


/**
 * @Service("OrderService")
 */
class OrderService {
    
    public function calculateOrder($order) {
        $resault = ['sumAll'=>1, 'sumAditionalServise'=> 2 , 'sumMaket'=> 3  ];
        
        //.......        
        

        return $resault;
    }

}
?>