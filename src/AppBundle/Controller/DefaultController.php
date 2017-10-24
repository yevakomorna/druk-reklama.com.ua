<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Template("default/index.html")
     * @Route("/", name="home")
     */
    public function home(Request $request)
    {
        // replace this example code with whatever you need
        return ['base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,];
    }
    
    /**
     * @Template("default/admin.html")
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return [];
    }
    
        
    /**
     * @Template("default/category.html")
     * @Route("/{category}", name="category")
    */
    public function categoryPage($category)
    {
        var_dump($category);       
        return [];
    }
    
}
