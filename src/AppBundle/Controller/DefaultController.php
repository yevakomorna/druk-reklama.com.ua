<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * @Route("/", name="home")
     */
    public function home() {

        return $this->forward('AppBundle\Controller\ContentController::categoryPage', ['category_name' => 'home']);
    }
}
