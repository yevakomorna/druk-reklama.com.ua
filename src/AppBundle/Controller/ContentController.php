<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\DiExtraBundle\Annotation\Inject;

class ContentController extends Controller {
    
    /**
	 * @Inject
	 */
	private $session;
    
    /**
     * @Inject("CategoryDataService")
     */
    public $categoryDataService;

    /**
     * @Route("/{category_name}", requirements={"category_name": "^(?!login|logout).+"} , name="category")
     */
    public function categoryPage($category_name) {
        $data = $this->categoryDataService->getCategoryData($category_name);
        if (!$this->session->isStarted()) {
            $this->session->set('t', '1000');
        }
         
        return $this->render('default/template/' . $data['category']->getTemplate() . '.html', ['data' => $data]);
    }

}
