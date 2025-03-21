<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\DiExtraBundle\Annotation\Inject;

use AppBundle\Entity\OrderServiceEntity;
use AppBundle\Form\Type\OrderConfirmType;

class OrderController extends Controller {

    /**
     * @Inject
     */
    protected $session;

    /**
     * @Inject("form.factory")
     */
    public $formFactory;

    /**
     * @Inject("kernel")
     */
    public $kernel;

    /**
     * @Inject("liip_imagine.cache.manager")
     */
    protected $cacheManager;

    /**
     * @Inject("security.token_storage")
     */
    protected $tokenStorage;

    /**
     * @Inject("OrderDAO")
     */
    protected $OrderDAO;

    /**
     * @Inject("OrderServiceDAO")
     */
    protected $OrderServiceDAO;

    /**
     * @Inject("SectionDAO")
     */
    protected $SectionDAO;

    /**
     * @Inject("AdditionalServiceDAO")
     */
    protected $AdditionalServiceDAO;

    /**
     * @Inject("CategoryDataService")
     */
    public $categoryDataService;

    /**
     * @Inject("mailService")
     */
    public $mailService;
    
    /**
     * @Security("has_role('ROLE_USER')") 
     * @Method({"POST"})
     * @Route("/order_dell_img", name="order_dell_img")
     */
    public function orderDellImg() {
        $status = "error";
        $user = $this->tokenStorage->getToken()->getUser();

        if ($orderID = $this->session->get("order_id")) {
            $order = $this->OrderDAO->findBy(["id" => $orderID, "user" => $user]);
            $order = (count($order) > 0) ? $order[0] : null;
        }
        if ($order && $modelFile = $order->modelFileExist($this->kernel->getAbsRootDir(), true)) {
            unlink($modelFile);
            $this->cacheManager->remove($order->getModelWebPuth(), 'order_tiff');
            $order->setModel(null);
            $this->OrderDAO->save($order);
            $status = "ok";
        }

        return new JsonResponse(["status" => $status]);
    }

    /**
     * @Security("has_role('ROLE_USER')") 
     * @Method({"POST"})
     * @Route("/order_add", name="order_add")
     */
    public function orderAdd(Request $request) {
        $status = "error";
        $user = $this->tokenStorage->getToken()->getUser();

        if ($orderID = $this->session->get("order_id")) {
            $order = $this->OrderDAO->findBy(["id" => $orderID, "user" => $user]);
            $order = (count($order) > 0) ? $order[0] : null;
        }

        $postFields = $request->request->all();

        $orderServicesID = [];
        if (isset($postFields['additional_service'])) {
            foreach ($postFields['additional_service'] as $serviceID => $dum) {
                $orderServicesID[] = $serviceID;
            }
        }

        if (isset($postFields['additional_service_item'])) {
            foreach ($postFields['additional_service_item'] as $serviceID => $dum) {
                $orderServicesID[] = $serviceID;
            }
        }
        if ($oldOrderService = $order->getOrderService()) {
            //var_dump(get_class($oldOrderService));
            $this->OrderServiceDAO->deleteAll($oldOrderService);
        }

        if (count($orderServicesID) > 0) {
            foreach ($orderServicesID as $serviceID) {
                $OrderService = new OrderServiceEntity();
                $OrderService->setService($this->AdditionalServiceDAO->findOneById($serviceID));
                $OrderService->setOrder($order);

                $order->addOrderService($OrderService);
            }
        }

        $order->setModelWidth($postFields['set_maket_size_w']);
        $order->setModelHeight($postFields['set_maket_size_h']);
        $order->setSection($this->SectionDAO->findOneById($postFields['order_category']));
        //$order->setStatus(1);

        $this->OrderDAO->save($order);
        $status = "ok";

        return new JsonResponse(["status" => $status]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/order_confirm", name="order_confirm")
     */
    public function orderConfirm(Request $request) {

        $data = $this->categoryDataService->getCategoryData("order_confirm");
        $user = $this->tokenStorage->getToken()->getUser();

        $option = [];

        $formData = ["selfCheckout" => "м. Львів, вул. Шпитальна 17, вхід у дворі"];
        if ($user->getFullName()) {
            $formData["name"] = $user->getFullName();
        }
        if ($user->getPhone()) {
            $formData["phone"] = $user->getPhone();
        }
        
        if ($user->getEmail()) {
            $formData["email"] = $user->getEmail();
        }
        $formData["paymentMethod"] = $data["currentOrder"]->getPaymentMethod();
        
        $form = $this->formFactory->create(OrderConfirmType::class, $formData, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderAditionalFild = $form->getData();
            $currentOrder = $data["currentOrder"];

            $currentOrder->setFullName($orderAditionalFild["name"]);
            $currentOrder->setPhone($orderAditionalFild["phone"]);
            $currentOrder->setEmail($orderAditionalFild["email"]);
            $currentOrder->setPaymentMethod($orderAditionalFild["paymentMethod"]);
            $currentOrder->setDeliveryCity($orderAditionalFild["city"]);
            $currentOrder->setDeliveryDepartment($orderAditionalFild["department"]);
            $currentOrder->setComment($orderAditionalFild["comment"]);
            $currentOrder->setStatus(1);

            $this->OrderDAO->save($currentOrder);
            
            // перенести в крон все шо стосується закидування на ФТП (бо якшо великий файл то клієнт буде довго чекати)
            /*$ftp = new \FtpClient\FtpClient();
            $ftp->connect('91.226.5.101');
            $ftp->login('druk', '123456');
            
            $modelFile = $currentOrder->modelFileExist($this->kernel->getAbsRootDir(), true);
            $ftpUserDir = '/'.$user->getID().'/'; 
            // створює паку
            if (!$ftp->isDir($ftpUserDir)) { 
                $ftp->mkdir($ftpUserDir, true);
            }
            $ftp->put($ftpUserDir.$currentOrder->getModel(), $modelFile, FTP_BINARY);*/
            //unlink($modelFile);
            
            $this->mailService->sendConfirmOrderMail($currentOrder);
            
            $this->session->remove("order_id");
            
            return $this->redirectToRoute('category',['category_name'=>'order_confirm_ok']);
        }

        $data["orderConfirmForm"] = $form->createView();

        return $this->render('default/template/' . $data['category']->getTemplate() . '.html', ['data' => $data]);

    }

}
