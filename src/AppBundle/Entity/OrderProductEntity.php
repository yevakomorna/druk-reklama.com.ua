<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="order_product")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\OrderProductDAO")
 */
class OrderProductEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="title_ua", type="string", length=255, nullable=true)
     */
    //дублювання, але для того, якшо видалять товар щоб була назва
    private $titleUa;

    /**
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    // ціна на момент оформлення замовлення
    private $price;

    /**
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="ProductEntity", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="OrderEntity", inversedBy="orderProduct")
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

    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }
}
?>