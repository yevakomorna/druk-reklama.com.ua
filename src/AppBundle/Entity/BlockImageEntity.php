<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="block_image")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\BlockImageDAO")
 */
class BlockImageEntity extends BaseIntegerEntity {

    public static $PHOTO_DIR = "/web/content/block/image/";

    private static $IMAGE_MAIN_PRIORITY = 0;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    private $fileName;

    /**
     * @ORM\Column(name="priority", type="integer", options={"default":0})
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BlockEntity", inversedBy="images")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="block_id", referencedColumnName="id")
     * })
     */
    private $block;

    /**
     * @Assert\File(maxSize = "2M", mimeTypesMessage = "Please upload a valid Picture")
     */
    public $fileNameAssert;

    public function __construct() {
        $this->priority = self::$IMAGE_MAIN_PRIORITY;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getBlock() {
        return $this->block;
    }

    public function setBlock($block) {
        $this->block = $block;
    }

    public function setFileNameAssert($fileNameAssert) {
        $this->fileNameAssert = $fileNameAssert;
    }

    public function getFileNameAssert() {
        return $this->fileNameAssert;
    }

    public function getFull() {
        return self::$PHOTO_DIR . $this->fileName;
    }

    public function getRealpath($rootPath) {
        return $rootPath . self::$PHOTO_DIR . $this->fileName;
    }

    public function remuveFromStorage($rootPath) {
        if (file_exists($this->getRealpath($rootPath))) {
            unlink($this->getRealpath($rootPath));
        }

    }

}
?>