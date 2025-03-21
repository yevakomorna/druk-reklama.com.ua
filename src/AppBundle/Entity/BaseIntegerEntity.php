<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseIntegerEntity implements \Serializable {

    protected static $STATUS_VISIBLE = 1;
    protected static $STATUS_HIDDEN = 0;
    protected static $DEFAULT_LOCALE = 'ua';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getID() {
        return $this->id;
    }

    public function serialize() {
        return serialize(array($this->id));
    }

    public function unserialize($serialized) {
        list($this->id) = unserialize($serialized);
    }
}
?>