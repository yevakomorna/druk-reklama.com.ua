<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Table(name="block")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\BlockDAO")
 */
class BlockEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="priority", type="integer", options={"default":0})
     */
    private $priority;

    /**
     * @ORM\Column(name="status", type="integer", options={"default":1})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SectionEntity", inversedBy="blocks")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlockImageEntity", mappedBy="block", cascade={"persist", "remove"})
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $images;

    public function __construct() {
        $this->status = self::$STATUS_VISIBLE;
        $this->images = new ArrayCollection();
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getSection() {
        return $this->section;
    }

    public function setSection($section) {
        $this->section = $section;
    }

    public function getImages() {
        return $this->images;
    }

    public function setImages($images) {
        $this->images = $images;
    }

    public function addImage($image) {
        return $this->images->add($image);
    }

    public function convertImagesNameToFileObject($rootPath) {
        if (!$this->images->isEmpty()) {
            foreach ($this->images as $image) {
                $imagePath = file_exists($image->getRealpath($rootPath)) ? new File($image->getRealpath($rootPath)) : null;
                $image->setFileNameAssert($imagePath);

            }
        } else {
            $newImage = new BlockImageEntity();
            $newImage->setFileNameAssert(null);
            $newImage->setBlock($this);
            $this->addImage($newImage);
        }
    }

}
?>