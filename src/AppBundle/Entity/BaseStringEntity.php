<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseStringEntity implements \Serializable {

	/**
	 * @ORM\Column(type="string")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
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