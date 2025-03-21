<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="additionalservice")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\AdditionalServiceDAO")
 */
class AdditionalServiceEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="title_ua", type="string", nullable=true)
     */
    private $titleUa;

    /**
     * @ORM\Column(name="status", type="integer", options={"default":1})
     */
    private $status;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="AdditionalServiceEntity")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="AdditionalServiceEntity", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AdditionalServiceEntity", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="AdditionalServiceParamEntity", mappedBy="additionalService")
     * @ORM\JoinColumn(name="id", referencedColumnName="additional_service_id")
     */
    private $params;
    
    public function __construct() {
        $this->status = self::$STATUS_VISIBLE;
        $this->params = new ArrayCollection();
    }

    public function getTitleUa() {
        return $this->titleUa;
    }

    public function setTitleUa($titleUa) {
        $this->titleUa = $titleUa;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getRoot() {
        return $this->root;
    }

    public function setParent(AdditionalServiceEntity $parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getLvl() {
        return $this->lvl;
    }

    public function getTitleI18n($locale) {
        $locale = ucfirst($locale);
        return ($this->{'title' . $locale}) ? $this->{'title' . $locale} : $this->{'title' . ucfirst(self::$DEFAULT_LOCALE)};
    }

    public function getArrayForTree($locale) {
        $resalt = [];
        $resalt['id'] = $this->getID();
        $resalt['titleUa'] = $this->getTitleUa();
        $resalt['title'] = $this->getTitleI18n($locale);
        $resalt['status'] = $this->getStatus();
        $resalt['lft'] = $this->lft;
        $resalt['rgt'] = $this->rgt;
        $resalt['lvl'] = $this->lvl;

        return $resalt;
    }
}
?>