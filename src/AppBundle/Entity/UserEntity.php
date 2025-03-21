<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as FOSBaseUser;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\UserDAO")
 */
class UserEntity extends FOSBaseUser {

    public static $DEFAULT_USER_STATUS = 0;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="full_name", type="string", nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone;
    
    /**
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\Column(name="registration_date", type="datetime", nullable=true)
     */
    private $registrationDate;

    /**
     * @ORM\Column(name="registration_ip", type="string", nullable=true)
     */
    private $registrationIP;

    /**
     * @ORM\Column(name="last_login_ip", type="string", nullable=true)
     */
    private $lastLoginIp;

    /**
     * @ORM\Column(name="login_count", type="integer", nullable=true)
     */
    private $loginCount;

    /**
     * @ORM\Column(name="last_modify", type="datetime", nullable=true)
     */
    private $lastModify;

    /**
     * @ORM\Column(name="last_modify_ip", type="string", nullable=true)
     */
    private $lastModifyIp;
    
    /**
     * @ORM\Column(name="discount", type="decimal", precision=3, scale=2, nullable=true, options={"comment":"Відсоток знижки"}) 
     */
    private $discount;

    /**
     * @ORM\OneToMany(targetEntity="OrderEntity", mappedBy="user", cascade={"persist"})
    */
    private $order;

    /**
     * @ORM\OneToMany(targetEntity="OrderEntity", mappedBy="manager", cascade={"persist"})
    */
    private $managerOrder;
    
    public function __construct() {
        $this->status = self::$DEFAULT_USER_STATUS;
    }

    public function getID() {
        return $this->id;
    }

    public function getFullName() {
        return $this->fullName;
    }

    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    public function setRegistrationDate($registrationDate) {
        $this->registrationDate = $registrationDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getRegistrationIP() {
        return $this->registrationIP;
    }

    public function setRegistrationIP($ip) {
        $this->registrationIP = $ip;
    }

    public function getLastLoginIp() {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp($lastLoginIp) {
        $this->lastLoginIp = $lastLoginIp;
    }

    public function getLoginCount() {
        return $this->loginCount;
    }

    public function setLoginCount($loginCount) {
        $this->loginCount = $loginCount;
    }

    public function getLastModify() {
        return $this->lastModify;
    }

    public function setLastModify($lastModify) {
        $this->lastModify = $lastModify;
    }

    public function getLastModifyIp() {
        return $this->lastModifyIp;
    }

    public function setLastModifyIp($lastModifyIp) {
        $this->lastModifyIp = $lastModifyIp;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }
   
    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }
    
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getManagerOrder() {
        return $this->managerOrder;
    }

    public function setManagerOrder($managerOrder) {
        $this->managerOrder = $managerOrder;
    }

}
?>