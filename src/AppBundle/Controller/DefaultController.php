<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
    public function home() {
        // replace this example code with whatever you need
        return $this->categoryPage('home');
    }

    /**
     * @Route("/{category_nick}", name="category_nick")
     * 
     */
    public function categoryPage($category_nick) {

        $category = $this->SectionDAO->findOneBy(['name' => $category_nick]);

        $data = [];

        return $this->render('default/body/' . $category->getTemplateBody() . '.html', ['data' => $data]);
    }

    /**
     * @Template("default/edit_category.html")
     * @Route("/edit_category/", defaults={"category" = "home"})
     * @Route("/edit_category/{category}", name="edit_category")
     */
    public function categoryPageEdit($category) {
        var_dump($category);
        $category_item = $this->SectionDAO->findOneBy(['name' => $category]);

        $builder = $this->createFormBuilder();

        $builder->add('introtext', CKEditorType::class, ['attr' => ['class' => 'tinymce'], 'config' => ['uiColor' => '#ffffff', 'filebrowserBrowseRoute' => 'elfinder', 'filebrowserBrowseRouteParameters' => ['instance' => 'default', 'homeFolder' => 'uploads']], ]);
        $builder->add('save', SubmitType::class, ['label' => 'save']);
        $form = $builder->getForm();

        return ["form" => $form->createView()];
    }

}
