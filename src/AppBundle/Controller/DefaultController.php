<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;

class DefaultController extends Controller {

    /**
     * @Inject("SectionDAO")
     */
    private $SectionDAO;

    /**
     * @Template("default/index.html")
     * @Route("/", name="home")
     */
    public function home(Request $request) {
        var_dump($this->SectionDAO->findAll()[0]);
        // replace this example code with whatever you need
        return ['base_dir' => realpath($this->container->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR, ];
    }

    /**
     * @Template("default/admin.html")
     * @Route("/admin", name="admin")
     */
    public function admin() {
        return [];
    }

    /**
     * @Template("default/category.html")
     * @Route("/{category}", name="category")
     */
    public function categoryPage($category) {
        var_dump($category);
        return [];
    }

}
