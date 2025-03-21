<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\ProductDAO")
 */
class ProductEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="title_ua", type="string", length=255, nullable=true)
     */
    private $titleUa;

    /**
     * @ORM\Column(name="price", type="decimal", precision=10,  scale=2)
     */
    private $price;

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
    
}
?>