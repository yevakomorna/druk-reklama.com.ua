<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        // replace this example code with whatever you need
        return ['base_dir' => realpath($this->container->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR];
    }

    /**
     * @Template("default/category.html")
     * @Route("/{category}", name="category")
     */
    public function categoryPage($category) {
        var_dump($category);
        $category_item = $this->SectionDAO->findOneBy(['name' => $category]);
        var_dump($category_item->name);

        return [];
    }

    /**
     * @Template("default/category.html")
     * @Route("/edit_category/", defaults={"category" = "home"})
     * @Route("/edit_category/{category}", name="edit_category")
     */
    public function categoryPageEdit($category) {
        var_dump($category);
        $category_item = $this->SectionDAO->findOneBy(['name' => $category]);

        $builder = $this->createFormBuilder();

        $builder->add('introtext', CKEditorType::class, ['attr' => ['class' => 'tinymce'], 'config' => ['uiColor' => '#ffffff', ], ]);
        $builder->add('save', SubmitType::class, ['label' => 'save']);
        $form = $builder->getForm();

        return ["form" => $form->createView()];
    }

}
