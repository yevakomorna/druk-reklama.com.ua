<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;

/**
 * @ORM\Table(name="additionalservice_param", 
 *            uniqueConstraints={@UniqueConstraint(name="name_unique", columns={"name"})}
 *            )
 * @ORM\Entity(repositoryClass="AppBundle\Dao\AdditionalServiceParamDAO")
 */
class AdditionalServiceParamEntity extends BaseIntegerEntity {

    /**
     * @ORM\Column(name="name", type="string", nullable=false, length=190)
     */
    private $name;

    /**
     * @ORM\Column(name="value", type="string", nullable=false)
     */
    private $value;

    /**
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="AdditionalServiceEntity", inversedBy="params")
     * @ORM\JoinColumn(name="additional_service_id", referencedColumnName="id")
     */
    private $additionalService;

    public function __construct() {

    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getAdditionalService() {
        return $this->additionalService;
    }

    public function setAdditionalService($additionalService) {
        $this->additionalService = $additionalService;
    }

}
?>