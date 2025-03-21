<?php
namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Overlay\Animation;
use Ivory\GoogleMap\Overlay\Icon;
use Ivory\GoogleMap\Base\Point;
use Ivory\GoogleMap\Base\Size;
use Ivory\GoogleMap\Overlay\InfoWindow;

/**
 * @Service("CategoryDataService")
 */
class CategoryDataService {

    /**
     * @Inject
     */
    public $translator;
    
    /**
     * @Inject
     */    
    public $session;
    
    /**
     * @Inject("kernel")
     */
    public $kernel;
    
    /**
     * @Inject("security.token_storage")
     */
    public $tokenStorage;

    /**
     * @Inject("GlobalContentService")
     */
    public $globalContentService;

    /**
     * @Inject("SectionDAO")
     */
    public $SectionDAO;

    /**
     * @Inject("OrderDAO")
     */
    public $OrderDAO;
    
    public function getCategoryData($category_name) {
        $data = [];
        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);
        $additionalServiceID = $category->getAdditionalService() ? $category->getAdditionalService()->getID() : null; 
        $data['category'] = $category;

        $configDefault = ['carousel' => true, 'htmlTree' => true];
        $configTemplate = ["contact_page" => ['map' => true], 
                           "order_page"=>['additionalService'=> ['parentId'=> $additionalServiceID],
                                          "currentOrder" => true  
                                         ],
                            // to do: перенести в сервіс якись і в OrderController             
                           "order_confirm"=>['orderConfirmAdditionalService'=> true,
                                          "currentOrder" => true,
                                         ]              
                          ];
        $template = $category->getTemplate();

        $config = isset($configTemplate[$template]) ? array_merge($configTemplate[$template], $configDefault) : $configDefault;
        
        foreach ($config as $func => $args) {
            $data[$func] = $this->returnFuncCall($func, $args);
        }

        return $data;
    }

    private function returnFuncCall($func, $args) {
        $args = is_array($args) ? $args : [];

        return $this->{"get" . ucfirst($func)}($args);
    }
    
    private function getCurrentOrder($args) {
        extract($args);
        $order = null; 
        $user = $this->tokenStorage->getToken()->getUser();
        // get from sesssion
        if ($orderID = $this->session->get("order_id")) {
           $order = $this->OrderDAO->findBy(["id"=> $orderID, "user"=> $user]);
           $order = (count($order) > 0) ? $order[0] : null;
        }
        
        // get from baze
        if (!$order) {
           $order = $this->OrderDAO->findBy(["user"=> $user, "status"=> 0]);
           $order = (count($order) > 0) ? $order[0] : null;
        }
        
        if ($order) {
            if (!$order->modelFileExist($this->kernel->getAbsRootDir())) {
                // по невідомі причині файлу на сервері немає
                $order->setModel(null);
            }
            $this->session->set("order_id", $order->getID());
        } 

        return $order;
    }
    
    private function getCarousel($args) {
        extract($args);

        return $this->SectionDAO->findOneBy(['name' => 'carousel']);
    }
    
    private function getAdditionalService($args) {
        extract($args);
        
        return ($parentId) ? $this->globalContentService->getServises($parentId) : null;
        
    }
    
    private function getOrderConfirmAdditionalService($args) {
        extract($args);
        $order = null;
        $user = $this->tokenStorage->getToken()->getUser();
        // get from sesssion
        if ($orderID = $this->session->get("order_id")) {
           $order = $this->OrderDAO->findBy(["id"=> $orderID, "user"=> $user]);
           $order = (count($order) > 0) ? $order[0] : null;
        }
        
        // get from baze
        if (!$order) {
           $order = $this->OrderDAO->findBy(["user"=> $user, "status"=> 0]);
           $order = (count($order) > 0) ? $order[0] : null;
        }
        
        return ($order) ? $this->globalContentService->getServises($order->getSection()->getAdditionalService()->getID(), $order->getID()) : null;
        
    }
     
    private function getHtmlTree($args) {
        extract($args);

        return $this->globalContentService->getMainMenu();
    }

    private function getMap($args) {
        extract($args);

        $map = new Map();
        $map->setHtmlId('contacts_map_canvas');
        $map->setAutoZoom(false);
        $map->setCenter(new Coordinate('49.8450809', '24.0250263'));
        $map->setMapOption('zoom', 17);
        $map->setStylesheetOptions(['width' => '100%', 'height' => '500px']);

        $marker = new Marker(new Coordinate('49.844390', '24.021599'));
        $marker->setAnimation(Animation::DROP);
        $icon = new Icon('/web/img/Map-Marker-PNG-Picture.png');
        $marker->setIcon($icon);
        $infoWindow = new InfoWindow('
        
<div style="display:inline-block;font-family: roboto; margin:10px 0; padding:">
  <div style="float:left; margin-left:5px" ><img src="/web/img/butterfly_small.png" /></div>
  <div style="float:left; text-transform:uppercase;line-height: 100%;font-family: roboto;color: red;font-size: 17px; text-transform:uppercase; padding:3px 0 0 3px; text-align:left" >широкоформатний<br />друк</div>
  <div style="clear:both; margin-bottom:11px;"></div>
  <div style="font-size:17px; color:#000; margin-bottom:11px;">Вул. Шпитальна 17, <span style="font-size:15px">вхід у дворі</span></div>
  
    <div style="float:left; margin-left:36px" ><img src="/web/img/phone_blue_small.png" style="width:26px; margin:1px 4px 0 0" /><br />
    <img src="/web/img/vodafone_small_sim.png" style="width:26px; margin:5px 4px 0 0" /><br />
    <img src="/web/img/kyivstar_small_sim.png" style="width:26px; margin:5px 4px 0 0" />
    
  </div>
  <div style="float:left; text-transform:uppercase;line-height: 137%;font-family: roboto;font-weight: bold;font-size: 17px; text-transform:uppercase; padding:4px 0 0 7px" >
		032 245 20 75<br />
		050 59 44 900<br />
  068 18 46 317</div>
  <div style="float:left; margin-left:6px" ><img src="/web/img/viber_small_sim.png" style="width:26px; margin:31px 0px 0 3px" /><br />

    
  </div>
  <div></div>
  
      <div style="float:left; margin-left:36px" ><img src="/web/img/email_blue_small.png" style="width: 26px;margin: 3px 0 0 0;" /></div>
  <div style="float:left; line-height: 130%;font-family: roboto;font-size: 17px; padding:4px 0 0 9px" >
  zrdruk@gmail.com
    </div>
  <div style="clear:both"></div>
  
</div>
        
        
        ');
        $marker->setInfoWindow($infoWindow);
        $map->getOverlayManager()->addMarker($marker);

        $marker = new Marker(new Coordinate('49.844012', '24.024690'));
        $marker->setAnimation(Animation::DROP);
        $icon = new Icon('/web/img/Map-Marker-PNG-Picture.png');
        $marker->setIcon($icon);
        $infoWindow = new InfoWindow('
        <div style="display:inline-block;font-family: roboto; margin:10px 0; padding:">
  <div style="float:left; margin-left:50px" ><img src="/web/img/butterfly_small.png" /></div>
  <div style="float:left; text-transform:uppercase;line-height: 100%;font-family: roboto;color: red;font-weight: bold;font-size: 17px; text-transform:uppercase; padding:3px 0 0 3px" >зовнішня<br />
    &nbsp;реклама</div>
  <div style="clear:both; margin-bottom:11px;"></div>
  <div style="font-size:17px; color:#000; margin-bottom:11px;">Вул. Городоцька 7, <span style="font-size:15px">2-й поверх код (149)</span></div>
  
    <div style="float:left; margin-left:36px" ><img src="/web/img/phone_blue_small.png" style="width:26px; margin:13px 4px 0 0" /></div>
  <div style="float:left; text-transform:uppercase;line-height: 130%;font-family: roboto;font-weight: bold;font-size: 17px; text-transform:uppercase; padding:4px 0 0 9px" >
		032 240 37 25<br />
		032 240 37 55
    </div>
  <div style="clear:both; margin-bottom:11px"></div>
  
      <div style="float:left; margin-left:36px" ><img src="/web/img/email_blue_small.png" style="width: 26px;margin: 3px 0 0 0;" /></div>
  <div style="float:left; line-height: 130%;font-family: roboto;font-size: 17px; padding:4px 0 0 9px" >
zovnreklama@gmail.com
    </div>
  <div style="clear:both"></div>
  
</div>
        
        ');
        $marker->setInfoWindow($infoWindow);
        $map->getOverlayManager()->addMarker($marker);

        return $map;
    }

}
?>