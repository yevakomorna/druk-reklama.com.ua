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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller {

    /**
     * @Inject("SectionDAO")
     */
    private $SectionDAO;

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request) {
        // get the login error if there is one
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html', array(
            'last_username' => $lastUsername,
            'error' => $error,
            ));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {

        return [];
    }
    /**
     * @Template("default/admin.html")
     * @Route("/admin", name="admin")
     */
    public function adminAction() {

        return [];
    }

    /**
     * @Template("default/index.html")
     * @Route("/", name="home")
     */
    public function home() {
        return $this->categoryPage('home');
    }

    /**
     * @Route("/{category_name}", name="category")
     * 
     */
    public function categoryPage($category_name) {

        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);

        $data = ['category' => $category];

        return $this->render('default/template/' . $category->getTemplate() . '.html', ['data' => $data]);
    }

    /**
     * @Template("default/edit_category.html")
     * @Route("/edit_category/", defaults={"category_name" = "home"})
     * @Route("/edit_category/{category_name}", name="edit_category")
     */
    public function categoryPageEdit(Request $request, $category_name) {

        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);

        $builder = $this->createFormBuilder($category);

        $builder->add('description', CKEditorType::class, ['attr' => ['class' => 'tinymce'], 'config' => ['uiColor' => '#ffffff', 'filebrowserBrowseRoute' => 'elfinder', 'filebrowserBrowseRouteParameters' => ['instance' => 'default', 'homeFolder' => 'uploads']], ]);

        $builder->add('template', ChoiceType::class, ['choices' => ['simple_text_page' => 'simple_text_page', 'home_page' => 'home_page', ], ]);

        $builder->add('save', SubmitType::class, ['label' => 'save']);
        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $this->SectionDAO->save($category);

            return $this->redirectToRoute('edit_category', ['category_name' => $category_name]);
        }

        $data = ['category' => $category];

        return ["form" => $form->createView(), 'data' => $data];
    }

}
