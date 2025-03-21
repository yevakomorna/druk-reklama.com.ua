<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\SectionDAO")
 */
class SectionEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="title_ua", type="string", nullable=true)
     */
    private $titleUa;

    /**
     * @ORM\Column(name="template", type="string")
     */
    private $template;

    /**
     * @ORM\Column(name="description_ua", type="text", nullable=true)
     */
    private $descriptionUa;

    /**
     * @ORM\Column(name="description_two_ua", type="text", nullable=true)
     */
    private $descriptionTwoUa;

    /**
     * @ORM\Column(name="status", type="integer", options={"default":1})
     */
    private $status;
    
    /**
     * @ORM\Column(name="tape_width", type="integer", nullable=true)
     */
    private $tapeWidth;
    
    /**
     * @ORM\Column(name="area_edge", type="integer", nullable=true)
     */
    private $areaEdge;

    /**
     * @ORM\Column(name="area_price_min", type="integer", nullable=true)
     */
    private $areaPriceMin;

    /**
     * @ORM\Column(name="area_price_max", type="integer", nullable=true)
     */
    private $areaPriceMax;

    /**
     * @ORM\Column(name="area_price_volume_1000", type="integer", nullable=true)
     */
    private $areaPriceVolume1000;
    
    /**
     * @ORM\Column(name="area_price_volume_5000", type="integer", nullable=true)
     */
    private $areaPriceVolume5000;
    
    /**
     * @ORM\Column(name="area_price_volume_single_5000", type="integer", nullable=true)
     */
    private $areaPriceVolumeSingle5000;

    /**
     * @ORM\Column(name="css_class", type="string", nullable=true)
     */
    private $cssClass;

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
     * @ORM\ManyToOne(targetEntity="SectionEntity")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="SectionEntity", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="SectionEntity", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlockEntity", mappedBy="section")
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $blocks;

    /**
     * @ORM\OneToOne(targetEntity="AdditionalServiceEntity")
     * @ORM\JoinColumn(name="additional_service_id", referencedColumnName="id")
     */
    private $additionalService;

    /**
     * @ORM\OneToMany(targetEntity="OrderEntity", mappedBy="section")
     */
    private $order;

    public function __construct() {
        $this->status = self::$STATUS_VISIBLE;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getTitleUa() {
        return $this->titleUa;
    }

    public function setTitleUa($titleUa) {
        $this->titleUa = $titleUa;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function getDescriptionUa() {
        return $this->descriptionUa;
    }

    public function setDescriptionUa($descriptionUa) {
        $this->descriptionUa = $descriptionUa;
    }
    
    public function getDescriptionTwoUa() {
        return $this->descriptionTwoUa;
    }

    public function setDescriptionTwoUa($descriptionTwoUa) {
        $this->descriptionTwoUa = $descriptionTwoUa;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getBlocks() {
        return $this->blocks;
    }

    public function setBlocks($blocks) {
        $this->blocks = $blocks;
    }

    public function getAdditionalService() {
        return $this->additionalService;
    }

    public function setAdditionalService($additionalService) {
        $this->additionalService = $additionalService;
    }

    public function getRoot() {
        return $this->root;
    }

    public function setParent(SectionEntity $parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getLvl() {
        return $this->lvl;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }
    
    public function getTapeWidth() {
        return $this->tapeWidth;
    }

    public function setTapeWidth($tapeWidth) {
        $this->tapeWidth = $tapeWidth;
    }
    
    public function getAreaEdge() {
        return $this->areaEdge;
    }

    public function setAreaEdge($areaEdge) {
        $this->areaEdge = $areaEdge;
    }
    
    public function getAreaPriceMin() {
        return $this->areaPriceMin;
    }

    public function setAreaPriceMin($areaPriceMin) {
        $this->areaPriceMin = $areaPriceMin;
    }
    
    public function getAreaPriceMax() {
        return $this->areaPriceMax;
    }

    public function setAreaPriceMax($areaPriceMax) {
        $this->areaPriceMax = $areaPriceMax;
    }
    
    public function getAreaPriceVolume1000() {
        return $this->areaPriceVolume1000;
    }

    public function setAreaPriceVolume1000($areaPriceVolume1000) {
        $this->areaPriceVolume1000 = $areaPriceVolume1000;
    }
    
    public function getAreaPriceVolume5000() {
        return $this->areaPriceVolume5000;
    }

    public function setAreaPriceVolume5000($areaPriceVolume5000) {
        $this->areaPriceVolume5000 = $areaPriceVolume5000;
    }
    
    public function getAreaPriceVolumeSingle5000() {
        return $this->areaPriceVolumeSingle5000;
    }

    public function setAreaPriceVolumeSingle5000($areaPriceVolumeSingle5000) {
        $this->areaPriceVolumeSingle5000 = $areaPriceVolumeSingle5000;
    }
        
    public function getTitleI18n($locale) {
        $locale = ucfirst($locale);
        return ($this->{'title' . $locale}) ? $this->{'title' . $locale} : $this->{'title' . ucfirst(self::$DEFAULT_LOCALE)};
    }

    public function getDescriptionI18n($locale) {
        $locale = ucfirst($locale);
        return ($this->{'description' . $locale}) ? $this->{'description' . $locale} : $this->{'description' . ucfirst(self::$DEFAULT_LOCALE)};
    }
    
    public function getDescriptionTwoI18n($locale) {
        $locale = ucfirst($locale);
        return ($this->{'descriptionTwo' . $locale}) ? $this->{'descriptionTwo' . $locale} : $this->{'descriptionTwo' . ucfirst(self::$DEFAULT_LOCALE)};
    }
}
?>