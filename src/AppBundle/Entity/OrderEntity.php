<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AppBundle\Dao\OrderDAO")
 */
class OrderEntity extends BaseIntegerEntity {
    private static $STATUS_NOT_CONFIRMED = 0;
    private static $USER_ORDER_UPLOADS_DIR = "/web/user/uploads/";

    /**
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(name="model_width", type="string", length=255, nullable=true)
     */
    private $modelWidth;

    /**
     * @ORM\Column(name="model_height", type="string", length=255, nullable=true)
     */
    private $modelHeight;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\Column(name="full_name", type="string", nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="paymentMethod", type="string", length=255, nullable=true)
     */
    private $paymentMethod;

    /**
     * @ORM\Column(name="delivery_city", type="string", length=255, nullable=true)
     */
    private $deliveryCity;

    /**
     * @ORM\Column(name="delivery_department", type="string", length=255, nullable=true)
     */
    private $deliveryDepartment;

    /**
     * @ORM\Column(name="comment", type="string", length=1000, nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="OrderProductEntity", mappedBy="order", cascade={"all"})
     */
    private $orderProduct;

    /**
     * @ORM\OneToMany(targetEntity="OrderServiceEntity", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
    private $orderService;

    /**
     * @ORM\ManyToOne(targetEntity="SectionEntity", inversedBy="order")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="order")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="managerOrder")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    public function __construct() {
        $this->status = self::$STATUS_NOT_CONFIRMED;
        $this->date = new \DateTime("now");
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getOrderService() {
        return $this->orderService;
    }

    public function setOrderService($orderService) {
        $this->orderService = $orderService;
    }

    public function addOrderService($orderService) {
        $this->orderService->add($orderService);
    }

    public function clearOrderService() {
        $this->orderService->clear();
    }

    public function removeOrderService($element) {
        $this->orderService->removeElement($element);
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function getOrderProduct() {
        return $this->orderProduct;
    }

    public function setOrderProduct($orderProduct) {
        $this->orderProduct = $orderProduct;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getManager() {
        return $this->manager;
    }

    public function setManager($manager) {
        $this->manager = $manager;
    }

    public function getModelWidth() {
        return $this->modelWidth;
    }

    public function setModelWidth($modelWidth) {
        $this->modelWidth = $modelWidth;
    }

    public function getModelHeight() {
        return $this->modelHeight;
    }

    public function setModelHeight($modelHeight) {
        $this->modelHeight = $modelHeight;
    }

    public function getSection() {
        return $this->section;
    }

    public function setSection($section) {
        $this->section = $section;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getFullName() {
        return $this->fullName;
    }

    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function getDeliveryCity() {
        return $this->deliveryCity;
    }

    public function setDeliveryCity($deliveryCity) {
        $this->deliveryCity = $deliveryCity;
    }

    public function getDeliveryDepartment() {
        return $this->deliveryDepartment;
    }

    public function setDeliveryDepartment($deliveryDepartment) {
        $this->deliveryDepartment = $deliveryDepartment;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getOrderServicesID($returnArray = false) {
        $resault = [];
        if ($this->orderService) {
            foreach ($this->orderService as $orderService) {
                $resault[] = $orderService->getService()->getID();
            }
        }
        if ($returnArray) {
            return $resault;
        } else {
            return implode(",", $resault);
        }
    }

    public function modelFileExist($rootDir, $returnAbsPuth = false) {
        if (!$this->getModel()) {
            return false;
        }

        $filePath = $rootDir . self::$USER_ORDER_UPLOADS_DIR . $this->getUser()->getID() . "/" . $this->getModel();

        return file_exists($filePath) ? ($returnAbsPuth ? $filePath : true) : false;
    }

    public function getModelWebPuth() {
        return self::$USER_ORDER_UPLOADS_DIR . $this->getUser()->getID() . "/" . $this->getModel();
    }

    public function getModelType() {
        $types = ["tiff" => "TIFF", "tif" => "TIFF", "cdr" => "CDR", "cdr" => "CDR"];
        return $types[pathinfo(self::$USER_ORDER_UPLOADS_DIR . $this->getUser()->getID() . "/" . $this->getModel(), PATHINFO_EXTENSION)];
    }

}
?>