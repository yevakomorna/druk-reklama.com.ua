<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="order_service")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\OrderServiceDAO")
 */
class OrderServiceEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="title_ua", type="string", length=255, nullable=true)
     */
    //дублювання, але для того, якшо видалять товар щоб була назва (зкльопувати якшо 2-рівнева назва)
    private $titleUa;

    /**
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    // ціна на момент оформлення замовлення
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="AdditionalServiceEntity", cascade={"persist"})
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="OrderEntity", inversedBy="orderService", cascade={"persist"} )
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    public function __construct() {

    }

    public function getTitleUa() {
        return $this->titleUa;
    }

    public function setTitleUa($titleUa) {
        $this->titleUa = $titleUa;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getService() {
        return $this->service;
    }

    public function setService($service) {
        $this->service = $service;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }
}
?>