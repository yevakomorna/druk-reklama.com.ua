<?php
namespace AppBundle\EventListener;

use Oneup\UploaderBundle\Event\PostUploadEvent;
use AppBundle\Entity\OrderEntity;

class UploadListener {
    private $imagineCacheManager;
    private $kernelRootDir;
    private $tokenStorage;
    private $OrderDAO;
    private $session;
    private $orphanageManager;

    public function __construct($imagineCacheManager, $kernelRootDir, $tokenStorage, $OrderDAO, $session, $orphanageManager) {
        $this->imagineCacheManager = $imagineCacheManager;
        $this->kernelRootDir = $kernelRootDir;
        $this->tokenStorage = $tokenStorage;
        $this->OrderDAO = $OrderDAO;
        $this->session = $session;
        $this->orphanageManager = $orphanageManager;
    }

    public function onUpload(PostUploadEvent $event) {
        $order = null;
        $user = $this->tokenStorage->getToken()->getUser();

        if ($orderID = $this->session->get("order_id")) {
            $order = $this->OrderDAO->findBy(["id" => $orderID, "user" => $user]);
            $order = (count($order) > 0) ? $order[0] : null;
        }

        if (!$order) {
            $order = new OrderEntity();
            $order->setUser($user);
        }

        $file = $event->getFile();
        //$request = $event->getRequest();

        $order->setModel($file->getBasename());
        $this->OrderDAO->save($order);
        $this->session->set("order_id", $order->getID());

        $fileInStorage = $this->orphanageManager->get("gallery")->uploadFiles([$file]);
        $file = $fileInStorage[0];
        
        $pathname = $file->getPathname();
        $path = str_replace($this->kernelRootDir . "/..", "", $pathname);
        $path = str_replace("\\", "/", $path);
        $resolvedPath = $this->imagineCacheManager->getBrowserPath($path, "order_tiff");

        $response = $event->getResponse();
        $response["success"] = true;
        $response["pathname"] = $pathname;
        $response["path"] = $resolvedPath;
        return $response;
    }
}
?>